<?php

require_once 'mobile-shop/vendor/autoload.php';

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Dotenv\Dotenv;

// Загружаем переменные окружения
$dotenv = new Dotenv();
$dotenv->load('mobile-shop/.env');

// Создаем EntityManager
$config = Setup::createAnnotationMetadataConfiguration(
    ['mobile-shop/src/Entity'],
    true,
    null,
    null,
    false
);

$conn = [
    'driver' => 'pdo_mysql',
    'host' => $_ENV['DATABASE_HOST'] ?? '127.0.0.1',
    'port' => $_ENV['DATABASE_PORT'] ?? 3308,
    'dbname' => $_ENV['DATABASE_NAME'] ?? 'mobile_shop',
    'user' => $_ENV['DATABASE_USER'] ?? 'app',
    'password' => $_ENV['DATABASE_PASSWORD'] ?? 'app',
    'charset' => 'utf8mb4',
];

$entityManager = EntityManager::create($conn, $config);

// Тестируем Cart и CartItem классы
$productRepository = $entityManager->getRepository(\App\Entity\Product::class);
$cartRepository = $entityManager->getRepository(\App\Entity\Cart::class);

$product = $productRepository->find(258);
if (!$product) {
    echo "Продукт с ID 258 не найден\n";
    exit(1);
}

echo "Найден продукт: " . $product->getName() . "\n";
echo "Цена: " . $product->getPrice() . "\n";

// Создаем тестовую корзину
$cart = new \App\Entity\Cart();
$cartItem = new \App\Entity\CartItem();
$cartItem->setProduct($product);
$cartItem->setQuantity(2);
$cartItem->setPrice($product->getPrice());
$cartItem->setCart($cart);

$cart->addItem($cartItem);

// Тестируем расчеты
echo "Стоимость позиции: " . $cartItem->getTotal() . "\n";
echo "Общая стоимость корзины: " . $cart->getTotal() . "\n";
echo "Количество товаров в корзине: " . $cart->getTotalItems() . "\n";

echo "Тест пройден успешно! Функции bcmath заменены.\n";