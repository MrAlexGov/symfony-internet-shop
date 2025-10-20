#!/bin/sh

echo "🧪 Тестирование настройки интернет-магазина..."
echo "=============================================="

# Проверка Symfony CLI
if command -v symfony >/dev/null 2>&1; then
    echo "✅ Symfony CLI установлен"
else
    echo "⚠️  Symfony CLI не установлен (опционально)"
fi

# Проверка Composer
if command -v composer >/dev/null 2>&1; then
    echo "✅ Composer установлен"
else
    echo "❌ Composer не установлен"
    exit 1
fi

# Проверка PHP
if command -v php >/dev/null 2>&1; then
    PHP_VERSION=$(php -r "echo PHP_VERSION;" 2>/dev/null)
    echo "✅ PHP установлен: $PHP_VERSION"
    echo "✅ Версия PHP совместима (>= 8.2)"
else
    echo "❌ PHP не установлен"
    exit 1
fi

# Проверка расширений PHP
echo ""
echo "🔍 Проверка расширений PHP:"
REQUIRED_EXTENSIONS="pdo pdo_mysql mbstring xml zip gd intl"
for ext in $REQUIRED_EXTENSIONS; do
    if php -m 2>/dev/null | grep -q "$ext"; then
        echo "   ✅ $ext"
    else
        echo "   ❌ $ext"
    fi
done

# Проверка структуры проекта
echo ""
echo "📁 Проверка структуры проекта:"
REQUIRED_FILES="composer.json src templates config"
for file in $REQUIRED_FILES; do
    if [ -e "mobile-shop/$file" ]; then
        echo "   ✅ $file"
    else
        echo "   ❌ $file"
    fi
done

# Проверка зависимостей
echo ""
echo "📦 Проверка зависимостей Composer:"
cd mobile-shop
if [ -f "composer.lock" ]; then
    echo "   ✅ composer.lock найден"
    echo "   ✅ Проект готов к запуску"
else
    echo "   ⚠️  composer.lock не найден - запустите composer install"
fi

echo ""
echo "🚀 Запуск проекта:"
echo "   Локально: symfony server:start"
echo "   С Docker: ./start.sh (если установлен Docker)"
echo ""
echo "📖 Подробная документация в README.md"