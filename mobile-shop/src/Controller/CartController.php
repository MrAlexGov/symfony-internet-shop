<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Product;
use App\Repository\CartRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Csrf\CsrfToken;

final class CartController extends AbstractController
{
    #[Route('/cart', name: 'app_cart', methods: ['GET'])]
    public function index(CartRepository $cartRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $cart = $cartRepository->findOneBy(['user' => $user]);

        if (!$cart) {
            $cart = new Cart();
            $cart->setUser($user);
            $cartRepository->save($cart, true);
        }

        return $this->render('cart/index.html.twig', [
            'cart' => $cart,
        ]);
    }

    #[Route('/cart/add/{id}', name: 'app_cart_add', methods: ['POST'])]
    public function add(
        Product $product,
        Request $request,
        CartRepository $cartRepository,
        CsrfTokenManagerInterface $csrfTokenManager
    ): JsonResponse {
        try {
            $user = $this->getUser();
            if (!$user) {
                return new JsonResponse(['success' => false, 'message' => 'Необходимо авторизоваться'], 401);
            }

            // Проверяем CSRF токен
            $token = $request->request->get('_token') ?: $request->headers->get('X-CSRF-TOKEN');
            if (!$csrfTokenManager->isTokenValid(new CsrfToken('cart_add', $token))) {
                return new JsonResponse(['success' => false, 'message' => 'Неверный токен безопасности'], 403);
            }

            $quantity = $request->request->getInt('quantity', 1);

            if ($quantity < 1) {
                return new JsonResponse(['success' => false, 'message' => 'Некорректное количество'], 400);
            }

            if ($quantity > 99) {
                return new JsonResponse(['success' => false, 'message' => 'Максимальное количество - 99 шт.'], 400);
            }

            // Проверяем, что товар активен и доступен
            if (!$product || !$product->isActive()) {
                return new JsonResponse(['success' => false, 'message' => 'Товар не найден или недоступен'], 404);
            }
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Ошибка валидации: ' . $e->getMessage()
            ], 400);
        }

        $cart = $cartRepository->findOneBy(['user' => $user]);

        if (!$cart) {
            $cart = new Cart();
            $cart->setUser($user);
            $cartRepository->save($cart, true);
        }

        // Проверяем, есть ли уже этот товар в корзине
        $existingItem = null;
        foreach ($cart->getItems() as $item) {
            if ($item->getProduct() === $product) {
                $existingItem = $item;
                break;
            }
        }

        if ($existingItem) {
            // Увеличиваем количество существующего товара
            $newQuantity = $existingItem->getQuantity() + $quantity;
            $existingItem->setQuantity($newQuantity);
        } else {
            // Создаем новый элемент корзины
            $cartItem = new CartItem();
            $cartItem->setCart($cart);
            $cartItem->setProduct($product);
            $cartItem->setQuantity($quantity);
            $cartItem->setPrice($product->getPrice());

            $cart->addItem($cartItem);
        }

        try {
            $cartRepository->save($cart, true);
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Ошибка при сохранении корзины: ' . $e->getMessage()
            ], 500);
        }

        return new JsonResponse([
            'success' => true,
            'message' => 'Товар добавлен в корзину',
            'cartTotal' => $cart->getTotal(),
            'cartItemsCount' => $cart->getTotalItems()
        ]);
    }

    #[Route('/cart/remove/{id}', name: 'app_cart_remove', methods: ['POST'])]
    public function remove(
        CartItem $cartItem,
        Request $request,
        CartRepository $cartRepository,
        CsrfTokenManagerInterface $csrfTokenManager
    ): JsonResponse {
        try {
            $user = $this->getUser();
            if (!$user) {
                return new JsonResponse(['success' => false, 'message' => 'Необходимо авторизоваться'], 401);
            }

            if ($cartItem->getCart()->getUser() !== $user) {
                return new JsonResponse(['success' => false, 'message' => 'Доступ запрещен'], 403);
            }

            // Проверяем CSRF токен
            $token = $request->request->get('_token') ?: $request->headers->get('X-CSRF-TOKEN');
            if (!$csrfTokenManager->isTokenValid(new CsrfToken('cart_remove', $token))) {
                return new JsonResponse(['success' => false, 'message' => 'Неверный токен безопасности'], 403);
            }

            $cart = $cartItem->getCart();

            // Удаляем товар из корзины
            $cart->removeItem($cartItem);

            // Сохраняем изменения
            $cartRepository->save($cart, true);

            return new JsonResponse([
                'success' => true,
                'message' => 'Товар удален из корзины',
                'cartTotal' => $cart->getTotal(),
                'cartItemsCount' => $cart->getTotalItems()
            ]);

        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Ошибка при удалении товара: ' . $e->getMessage()
            ], 500);
        }
    }

    #[Route('/cart/update/{id}', name: 'app_cart_update', methods: ['POST'])]
    public function update(
        CartItem $cartItem,
        Request $request,
        CartRepository $cartRepository,
        CsrfTokenManagerInterface $csrfTokenManager
    ): JsonResponse {
        $user = $this->getUser();
        if (!$user || $cartItem->getCart()->getUser() !== $user) {
            return new JsonResponse(['success' => false, 'message' => 'Доступ запрещен'], 403);
        }

        // Проверяем CSRF токен
        $token = $request->request->get('_token') ?: $request->headers->get('X-CSRF-TOKEN');
        if (!$csrfTokenManager->isTokenValid(new CsrfToken('cart_update', $token))) {
            return new JsonResponse(['success' => false, 'message' => 'Неверный токен безопасности'], 403);
        }

        $quantity = $request->request->getInt('quantity', 1);

        if ($quantity < 1) {
            return new JsonResponse(['success' => false, 'message' => 'Некорректное количество'], 400);
        }

        $cartItem->setQuantity($quantity);
        $cartRepository->save($cartItem->getCart(), true);

        return new JsonResponse([
            'success' => true,
            'message' => 'Количество обновлено',
            'itemTotal' => $cartItem->getTotal(),
            'cartTotal' => $cartItem->getCart()->getTotal(),
            'cartItemsCount' => $cartItem->getCart()->getTotalItems()
        ]);
    }

    #[Route('/cart/clear', name: 'app_cart_clear', methods: ['POST'])]
    public function clear(
        Request $request,
        CartRepository $cartRepository,
        CsrfTokenManagerInterface $csrfTokenManager
    ): JsonResponse {
        try {
            $user = $this->getUser();
            if (!$user) {
                return new JsonResponse(['success' => false, 'message' => 'Необходимо авторизоваться'], 401);
            }

            // Проверяем CSRF токен
            $token = $request->request->get('_token') ?: $request->headers->get('X-CSRF-TOKEN');
            if (!$csrfTokenManager->isTokenValid(new CsrfToken('cart_clear', $token))) {
                return new JsonResponse(['success' => false, 'message' => 'Неверный токен безопасности'], 403);
            }

            $cart = $cartRepository->findOneBy(['user' => $user]);

            if ($cart) {
                // Очищаем корзину
                $cart->getItems()->clear();
                $cartRepository->save($cart, true);
            }

            return new JsonResponse([
                'success' => true,
                'message' => 'Корзина очищена',
                'cartTotal' => '0.00',
                'cartItemsCount' => 0
            ]);

        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Ошибка при очистке корзины: ' . $e->getMessage()
            ], 500);
        }
    }
}