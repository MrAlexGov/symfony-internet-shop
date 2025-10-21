<?php

namespace App\Controller;

use App\Entity\Compare;
use App\Entity\CompareItem;
use App\Entity\Product;
use App\Repository\CompareRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class CompareController extends AbstractController
{
    #[Route('/compare', name: 'app_compare')]
    public function index(CompareRepository $compareRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $compare = $compareRepository->findOneBy(['user' => $user]);

        if (!$compare) {
            $compare = new Compare();
            $compare->setUser($user);
            $compareRepository->save($compare, true);
        }

        return $this->render('compare/index.html.twig', [
            'compare' => $compare,
        ]);
    }

    #[Route('/compare/add/{id}', name: 'app_compare_add', methods: ['POST'])]
    public function add(
        Product $product,
        CompareRepository $compareRepository
    ): JsonResponse {
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['success' => false, 'message' => 'Необходимо авторизоваться'], 401);
        }

        $compare = $compareRepository->findOneBy(['user' => $user]);

        if (!$compare) {
            $compare = new Compare();
            $compare->setUser($user);
            $compareRepository->save($compare, true);
        }

        // Проверяем лимит товаров для сравнения
        if (!$compare->canAddMoreProducts()) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Максимум 4 товара для сравнения'
            ]);
        }

        // Проверяем, есть ли уже этот товар в сравнении
        if ($compare->containsProduct($product)) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Товар уже добавлен в сравнение'
            ]);
        }

        // Создаем новый элемент сравнения
        $compareItem = new CompareItem();
        $compareItem->setCompare($compare);
        $compareItem->setProduct($product);

        $compare->addItem($compareItem);
        $compareRepository->save($compare, true);

        return new JsonResponse([
            'success' => true,
            'message' => 'Товар добавлен в сравнение',
            'compareCount' => $compare->getTotalItems()
        ]);
    }

    #[Route('/compare/remove/{id}', name: 'app_compare_remove', methods: ['POST'])]
    public function remove(
        CompareItem $compareItem,
        CompareRepository $compareRepository
    ): JsonResponse {
        $user = $this->getUser();
        if (!$user || $compareItem->getCompare()->getUser() !== $user) {
            return new JsonResponse(['success' => false, 'message' => 'Доступ запрещен'], 403);
        }

        $compare = $compareItem->getCompare();
        $compare->removeItem($compareItem);

        // Полностью удаляем элемент из базы данных
        $entityManager = $compareRepository->getEntityManager();
        $entityManager->remove($compareItem);
        $compareRepository->save($compare, true);

        return new JsonResponse([
            'success' => true,
            'message' => 'Товар удален из сравнения',
            'compareCount' => $compare->getTotalItems()
        ]);
    }

    #[Route('/compare/toggle/{id}', name: 'app_compare_toggle', methods: ['POST'])]
    public function toggle(
        Product $product,
        CompareRepository $compareRepository
    ): JsonResponse {
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['success' => false, 'message' => 'Необходимо авторизоваться'], 401);
        }

        $compare = $compareRepository->findOneBy(['user' => $user]);

        if (!$compare) {
            $compare = new Compare();
            $compare->setUser($user);
            $compareRepository->save($compare, true);
        }

        // Проверяем, есть ли уже этот товар в сравнении
        if ($compare->containsProduct($product)) {
            // Удаляем товар из сравнения
            foreach ($compare->getItems() as $item) {
                if ($item->getProduct() === $product) {
                    $compare->removeItem($item);
                    // Полностью удаляем элемент из базы данных
                    $entityManager = $compareRepository->getEntityManager();
                    $entityManager->remove($item);
                    $compareRepository->save($compare, true);
                    break;
                }
            }

            return new JsonResponse([
                'success' => true,
                'message' => 'Товар удален из сравнения',
                'inCompare' => false,
                'compareCount' => $compare->getTotalItems()
            ]);
        } else {
            // Проверяем лимит товаров для сравнения
            if (!$compare->canAddMoreProducts()) {
                return new JsonResponse([
                    'success' => false,
                    'message' => 'Максимум 4 товара для сравнения'
                ]);
            }

            // Добавляем товар в сравнение
            $compareItem = new CompareItem();
            $compareItem->setCompare($compare);
            $compareItem->setProduct($product);

            $compare->addItem($compareItem);
            $compareRepository->save($compare, true);

            return new JsonResponse([
                'success' => true,
                'message' => 'Товар добавлен в сравнение',
                'inCompare' => true,
                'compareCount' => $compare->getTotalItems()
            ]);
        }
    }

    #[Route('/compare/clear', name: 'app_compare_clear', methods: ['POST'])]
    public function clear(CompareRepository $compareRepository): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['success' => false, 'message' => 'Необходимо авторизоваться'], 401);
        }

        $compare = $compareRepository->findOneBy(['user' => $user]);

        if ($compare) {
            $compare->getItems()->clear();
            $compareRepository->save($compare, true);
        }

        return new JsonResponse([
            'success' => true,
            'message' => 'Сравнение очищено',
            'compareCount' => 0
        ]);
    }
}