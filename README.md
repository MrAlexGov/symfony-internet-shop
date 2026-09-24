# Mobile Shop — интернет-магазин мобильных телефонов (Symfony)

Пет-проект: витрина интернет-магазина мобильных телефонов и аксессуаров на
Symfony 7.3 / PHP 8.3 / Doctrine ORM / MySQL. Сделан для отработки стека
Symfony на практике (DDD-friendly структура контроллеров/сервисов/репозиториев,
формы, Doctrine-миграции, фикстуры, функциональные тесты).

## Что реально реализовано

- Каталог товаров: фильтры (поиск, категория, бренд, диапазон цены), сортировка,
  пагинация (KnpPaginatorBundle).
- Карточка товара, категории (древовидные), бренды.
- Регистрация/авторизация пользователей (Symfony Security, ролевая модель
  `ROLE_USER`/`ROLE_ADMIN`/`ROLE_MANAGER`), восстановление доступа к разделам
  через `access_control`.
- Корзина: добавление/обновление/удаление товаров (AJAX, CSRF-защищённые
  запросы), пересчёт суммы.
- Избранное (Wishlist) и сравнение товаров (Compare).
- Docker Compose для локального окружения (nginx + php-fpm + MySQL, опционально
  Redis/Adminer).
- Функциональные тесты (PHPUnit + Symfony WebTestCase) на ключевые сценарии:
  главная страница, каталог с фильтрами, страница товара (404 для несуществующих
  slug), логин (успешный/неуспешный, редирект неавторизованных), добавление
  товара в корзину end-to-end (логин → CSRF-токен → добавление → проверка в
  корзине). CI (GitHub Actions) прогоняет тесты на каждый push.

## Чего нет (осознанно, честно обозначаю)

- **Оформление заказа и оплата — только каркас/заглушка.** `CheckoutController`
  и `PaymentService` формируют структуру интеграции со Stripe/ЮKassa, но
  реального обращения к их API нет — это демонстрация архитектуры платёжного
  сервиса (стратегия по методу оплаты, обработка вебхука), не рабочий платёж.
  В базе также нет отдельной сущности `Order` — оформленный заказ не
  персистится отдельно от корзины.
- Административная панель — минимальный дашборд, полноценного CRUD для
  заказов/пользователей/отчётов нет.
- Характеристики товара и отзывы — есть в модели данных (`ProductSpecification`,
  `Review`), но фикстуры их не заполняют, поэтому на карточке товара эти блоки
  пустые без ручного наполнения БД.
- Изображения товаров — только плейсхолдер (`no-photo.png`), реальных фото
  товаров нет.

## Технический стек

- **Фреймворк**: Symfony 7.3, PHP 8.3
- **ORM**: Doctrine ORM 3 + Doctrine Migrations
- **База данных**: MySQL 8.0
- **Фронтенд**: Twig, Bootstrap 5, Symfony UX (Turbo, Stimulus), Symfony Asset
  Mapper (без сборщика вроде Webpack)
- **Тесты**: PHPUnit 11, Symfony WebTestCase, Doctrine Fixtures Bundle
- **Деплой**: Docker + Docker Compose, nginx

## Быстрый старт (Docker)

```bash
cd mobile-shop
docker compose up -d
docker compose exec php php bin/console doctrine:migrations:migrate --no-interaction
docker compose exec php php bin/console doctrine:fixtures:load --no-interaction
```

Приложение: http://localhost:8080, Adminer: http://localhost:8085.

## Быстрый старт (без Docker)

Требуется PHP 8.2+, Composer, MySQL 8.0+/MariaDB.

```bash
cd mobile-shop
composer install
cp .env .env.local   # и поправьте DATABASE_URL под свою БД
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate --no-interaction
php bin/console doctrine:fixtures:load --no-interaction   # тестовые товары/юзеры
symfony server:start   # или php -S 127.0.0.1:8000 -t public
```

Тестовые пользователи из фикстур: `user@mobilshop.ru` / `user123` (обычный
пользователь), `admin@mobilshop.ru` / `admin123` (роль `ROLE_ADMIN`).

## Тесты

```bash
cd mobile-shop
composer install
php bin/console doctrine:database:create --env=test
php bin/console doctrine:migrations:migrate --no-interaction --env=test
php bin/console doctrine:fixtures:load --no-interaction --env=test   # часть тестов опирается на фикстуры
php bin/phpunit
```

## Структура проекта

```
mobile-shop/
├── config/              # Конфигурация Symfony/Doctrine/Security
├── migrations/          # Doctrine-миграции
├── src/
│   ├── Controller/      # Home/Catalog/Cart/Compare/Wishlist/Checkout/Login/...
│   ├── DataFixtures/    # Тестовые категории/бренды/товары/пользователи
│   ├── Entity/          # Product, Category, Brand, Cart, User, Review и др.
│   ├── Repository/      # Doctrine-репозитории с кастомными выборками/фильтрами
│   └── Service/         # PaymentService (заглушка), ImageService
├── templates/           # Twig-шаблоны (Bootstrap 5)
├── tests/               # Функциональные тесты (PHPUnit + WebTestCase)
└── compose.yaml         # Docker Compose (nginx + php-fpm + MySQL)
```

## Лицензия

MIT.
