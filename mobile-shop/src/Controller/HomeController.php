<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        ProductRepository $productRepository,
        CategoryRepository $categoryRepository
    ): Response {
        // Получаем рекомендуемые товары (featured продукты)
        $featuredProducts = $productRepository->findFeatured();

        // Получаем популярные категории
        $popularCategories = $categoryRepository->findBy(
            ['isActive' => true],
            ['sortOrder' => 'ASC', 'name' => 'ASC'],
            4 // только 4 категории для главной страницы
        );

        return $this->render('home/index.html.twig', [
            'featured_products' => $featuredProducts,
            'popular_categories' => $popularCategories,
        ]);
    }
}
