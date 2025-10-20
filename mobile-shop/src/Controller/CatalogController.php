<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use App\Repository\BrandRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CatalogController extends AbstractController
{
    #[Route('/catalog', name: 'app_catalog')]
    public function index(
        Request $request,
        ProductRepository $productRepository,
        CategoryRepository $categoryRepository,
        BrandRepository $brandRepository,
        PaginatorInterface $paginator
    ): Response {
        // Получаем параметры фильтрации из запроса
        $search = $request->query->get('search');
        $categoryId = $request->query->get('category');
        $brandId = $request->query->get('brand');
        $minPrice = $request->query->get('min_price');
        $maxPrice = $request->query->get('max_price');
        $sort = $request->query->get('sort', 'name');
        $order = $request->query->get('order', 'ASC');
        $page = $request->query->getInt('page', 1);

        // Получаем все категории и бренды для фильтров
        $categories = $categoryRepository->findBy(['isActive' => true], ['sortOrder' => 'ASC', 'name' => 'ASC']);
        $brands = $brandRepository->findBy(['isActive' => true], ['name' => 'ASC']);

        // Получаем товары с примененными фильтрами (без пагинации для подсчета общего количества)
        $allProducts = $productRepository->findWithFilters(
            $search,
            $categoryId,
            $brandId,
            $minPrice,
            $maxPrice,
            $sort,
            $order
        );

        // Применяем пагинацию
        $products = $paginator->paginate(
            $allProducts,
            $page,
            12 // товаров на страницу
        );

        return $this->render('catalog/index.html.twig', [
            'products' => $products,
            'categories' => $categories,
            'brands' => $brands,
            'currentSearch' => $search,
            'currentCategory' => $categoryId,
            'currentBrand' => $brandId,
            'currentMinPrice' => $minPrice,
            'currentMaxPrice' => $maxPrice,
            'currentSort' => $sort,
            'currentOrder' => $order,
        ]);
    }

    #[Route('/catalog/category/{slug}', name: 'app_catalog_category')]
    public function category(
        string $slug,
        CategoryRepository $categoryRepository,
        ProductRepository $productRepository
    ): Response {
        $category = $categoryRepository->findOneBy(['slug' => $slug, 'isActive' => true]);

        if (!$category) {
            throw $this->createNotFoundException('Категория не найдена');
        }

        $products = $productRepository->findBy([
            'category' => $category,
            'isActive' => true
        ], ['name' => 'ASC']);

        return $this->render('catalog/category.html.twig', [
            'category' => $category,
            'products' => $products,
        ]);
    }

    #[Route('/product/{slug}', name: 'app_product_show')]
    public function product(
        string $slug,
        ProductRepository $productRepository
    ): Response {
        $product = $productRepository->findOneBy(['slug' => $slug, 'isActive' => true]);

        if (!$product) {
            throw $this->createNotFoundException('Товар не найден');
        }

        return $this->render('catalog/product.html.twig', [
            'product' => $product,
        ]);
    }
}
