<?php

namespace App\Controller\Admin;

use App\Repository\UserRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DashboardController extends AbstractController
{
    #[Route('/admin', name: 'app_admin_dashboard')]
    public function index(
        UserRepository $userRepository,
        ProductRepository $productRepository
    ): Response {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // Для разработки разрешаем доступ любому авторизованному пользователю
        // В продакшене используйте: $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // Получаем статистику для дашборда
        $totalUsers = $userRepository->count([]);
        $totalProducts = $productRepository->count(['isActive' => true]);
        $activeProducts = $productRepository->count(['isActive' => true]);
        $inactiveProducts = $productRepository->count(['isActive' => false]);

        // Получаем последние зарегистрированных пользователей
        $recentUsers = $userRepository->findBy([], ['createdAt' => 'DESC'], 5);

        // Получаем товары с низким остатком
        $lowStockProducts = $productRepository->createQueryBuilder('p')
            ->where('p.isActive = :isActive')
            ->andWhere('p.stock <= :lowStock')
            ->setParameter('isActive', true)
            ->setParameter('lowStock', 10)
            ->orderBy('p.stock', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();

        return $this->render('admin/dashboard/index.html.twig', [
            'totalUsers' => $totalUsers,
            'totalProducts' => $totalProducts,
            'activeProducts' => $activeProducts,
            'inactiveProducts' => $inactiveProducts,
            'recentUsers' => $recentUsers,
            'lowStockProducts' => $lowStockProducts,
        ]);
    }
}
