<?php

namespace App\Controller;

use App\Entity\Wishlist;
use App\Entity\WishlistItem;
use App\Entity\Product;
use App\Repository\WishlistRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class WishlistController extends AbstractController
{
    #[Route('/wishlist', name: 'app_wishlist')]
    public function index(WishlistRepository $wishlistRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $wishlist = $wishlistRepository->findOneBy(['user' => $user]);

        if (!$wishlist) {
            $wishlist = new Wishlist();
            $wishlist->setUser($user);
            $wishlistRepository->save($wishlist, true);
        }

        return $this->render('wishlist/index.html.twig', [
            'wishlist' => $wishlist,
        ]);
    }

    #[Route('/wishlist/add/{id}', name: 'app_wishlist_add', methods: ['POST'])]
    public function add(
        Product $product,
        WishlistRepository $wishlistRepository
    ): JsonResponse {
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['success' => false, 'message' => 'Необходимо авторизоваться'], 401);
        }

        $wishlist = $wishlistRepository->findOneBy(['user' => $user]);

        if (!$wishlist) {
            $wishlist = new Wishlist();
            $wishlist->setUser($user);
            $wishlistRepository->save($wishlist, true);
        }

        // Проверяем, есть ли уже этот товар в избранном
        if ($wishlist->containsProduct($product)) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Товар уже добавлен в избранное'
            ]);
        }

        // Создаем новый элемент избранного
        $wishlistItem = new WishlistItem();
        $wishlistItem->setWishlist($wishlist);
        $wishlistItem->setProduct($product);

        $wishlist->addItem($wishlistItem);
        $wishlistRepository->save($wishlist, true);

        return new JsonResponse([
            'success' => true,
            'message' => 'Товар добавлен в избранное',
            'wishlistCount' => $wishlist->getTotalItems()
        ]);
    }

    #[Route('/wishlist/remove/{id}', name: 'app_wishlist_remove', methods: ['POST'])]
    public function remove(
        WishlistItem $wishlistItem,
        WishlistRepository $wishlistRepository
    ): JsonResponse {
        $user = $this->getUser();
        if (!$user || $wishlistItem->getWishlist()->getUser() !== $user) {
            return new JsonResponse(['success' => false, 'message' => 'Доступ запрещен'], 403);
        }

        $wishlist = $wishlistItem->getWishlist();
        $wishlist->removeItem($wishlistItem);

        $wishlistRepository->save($wishlist, true);

        return new JsonResponse([
            'success' => true,
            'message' => 'Товар удален из избранного',
            'wishlistCount' => $wishlist->getTotalItems()
        ]);
    }

    #[Route('/wishlist/toggle/{id}', name: 'app_wishlist_toggle', methods: ['POST'])]
    public function toggle(
        Product $product,
        WishlistRepository $wishlistRepository
    ): JsonResponse {
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['success' => false, 'message' => 'Необходимо авторизоваться'], 401);
        }

        $wishlist = $wishlistRepository->findOneBy(['user' => $user]);

        if (!$wishlist) {
            $wishlist = new Wishlist();
            $wishlist->setUser($user);
            $wishlistRepository->save($wishlist, true);
        }

        // Проверяем, есть ли уже этот товар в избранном
        if ($wishlist->containsProduct($product)) {
            // Удаляем товар из избранного
            foreach ($wishlist->getItems() as $item) {
                if ($item->getProduct() === $product) {
                    $wishlist->removeItem($item);
                    $wishlistRepository->save($wishlist, true);
                    break;
                }
            }

            return new JsonResponse([
                'success' => true,
                'message' => 'Товар удален из избранного',
                'inWishlist' => false,
                'wishlistCount' => $wishlist->getTotalItems()
            ]);
        } else {
            // Добавляем товар в избранное
            $wishlistItem = new WishlistItem();
            $wishlistItem->setWishlist($wishlist);
            $wishlistItem->setProduct($product);

            $wishlist->addItem($wishlistItem);
            $wishlistRepository->save($wishlist, true);

            return new JsonResponse([
                'success' => true,
                'message' => 'Товар добавлен в избранное',
                'inWishlist' => true,
                'wishlistCount' => $wishlist->getTotalItems()
            ]);
        }
    }
}