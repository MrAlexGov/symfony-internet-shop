# 🚀 Быстрый запуск Mobile Shop

## Автоматический запуск (рекомендуется)

```bash
cd mobile-shop
./start.sh
```

## Ручной запуск

### 1. Запуск базы данных и сервисов
```bash
cd mobile-shop
docker compose up -d
```

### 2. Установка зависимостей и миграции
```bash
# Выполнить миграции
docker compose exec php php bin/console doctrine:migrations:migrate

# Установить ассеты (если нужно)
docker compose exec php npm install
docker compose exec php npm run build
```

### 3. Доступ к приложению
- **Главная страница**: http://localhost:8080
- **Админ панель**: http://localhost:8080/admin
- **База данных**: http://localhost:8081

## Первый запуск

1. **Регистрация администратора**:
   - Перейдите на http://localhost:8080/registration
   - Создайте учетную запись
   - Войдите в админ панель: http://localhost:8080/admin

2. **Добавление товаров**:
   - В админ панели создайте категории и бренды
   - Добавьте мобильные телефоны в каталог

3. **Начало покупок**:
   - Просматривайте каталог товаров
   - Добавляйте товары в корзину
   - Оформляйте заказы

## Проверка работоспособности

```bash
# Проверка настройки системы
./test-setup.sh

# Запуск тестов
docker compose exec php php bin/phpunit
```

## Полезные команды

```bash
# Остановка всех сервисов
docker compose down

# Просмотр логов
docker compose logs -f

# Перезапуск конкретного сервиса
docker compose restart php

# Очистка всех данных (внимание!)
docker compose down -v
```

## Структура проекта

- `src/Controller/` - Контроллеры (маршруты)
- `src/Entity/` - Модели данных (Doctrine)
- `templates/` - HTML шаблоны (Twig)
- `migrations/` - Миграции базы данных
- `config/` - Конфигурационные файлы

## Поддержка

При возникновении проблем:
1. Проверьте логи: `docker compose logs`
2. Убедитесь что порты 8080, 8081, 3308 свободны
3. Перезапустите контейнеры: `docker compose restart`

---

🎉 **Готово! Ваш интернет-магазин мобильных телефонов запущен и готов к использованию!**