#!/bin/bash

echo "🚀 Запуск интернет-магазина мобильных телефонов..."
echo "================================================="

# Проверка наличия Docker
if ! command -v docker >/dev/null 2>&1; then
    echo "❌ Docker не установлен. Пожалуйста, установите Docker и Docker Compose."
    echo ""
    echo "📋 Альтернативный запуск без Docker:"
    echo "   cd mobile-shop"
    echo "   composer install"
    echo "   php bin/console doctrine:database:create"
    echo "   php bin/console doctrine:migrations:migrate"
    echo "   symfony server:start"
    exit 1
fi

# Проверка наличия Docker Compose
if ! command -v docker-compose >/dev/null 2>&1 && ! docker compose version >/dev/null 2>&1; then
    echo "❌ Docker Compose не установлен. Пожалуйста, установите Docker Compose."
    exit 1
fi

echo "📦 Сборка и запуск контейнеров..."
docker compose up -d --build

if [ $? -eq 0 ]; then
    echo "✅ Контейнеры запущены успешно!"

    echo ""
    echo "🔗 Доступ к приложению:"
    echo "   🌐 Главная страница: http://localhost:8080"
    echo "   🔐 Админ панель: http://localhost:8080/admin"
    echo "   🗄️  База данных (Adminer): http://localhost:8081"

    echo ""
    echo "📋 Полезные команды:"
    echo "   Остановить: docker compose down"
    echo "   Логи: docker compose logs -f"
    echo "   Перезапустить: docker compose restart"

    echo ""
    echo "🎯 Следующие шаги:"
    echo "   1. Откройте браузер и перейдите на http://localhost:8080"
    echo "   2. Зарегистрируйте нового пользователя"
    echo "   3. Добавьте товары через админ панель (/admin)"
    echo "   4. Начните делать покупки!"

else
    echo "❌ Ошибка при запуске контейнеров. Проверьте логи: docker compose logs"
    exit 1
fi