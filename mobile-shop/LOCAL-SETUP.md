# 🚀 Локальный запуск без Docker

Поскольку у вас нет Docker, но установлен PHP 8.3, Symfony CLI и Composer, вы можете запустить проект локально.

## 📋 Требования (уже установлены у вас)

✅ **PHP 8.3** - установлена
✅ **Composer** - установлена
✅ **Symfony CLI** - установлена

## 🛠 Настройка проекта

### 1. Установка зависимостей

```bash
cd mobile-shop
composer install
```

### 2. Настройка базы данных

Отредактируйте файл `.env` и укажите настройки вашей базы данных:

```bash
# Если у вас локальная MySQL
DATABASE_URL="mysql://username:password@127.0.0.1:3306/mobile_shop?serverVersion=8.0&charset=utf8mb4"

# Или используйте SQLite для тестов
DATABASE_URL="sqlite:///%kernel.project_dir%/var/data.db"
```

### 3. Создание базы данных

```bash
# Создание базы данных
php bin/console doctrine:database:create

# Выполнение миграций
php bin/console doctrine:migrations:migrate
```

### 4. Установка ассетов

```bash
# Установка веб-ассетов
php bin/console assets:install public

# Если используете Webpack Encore (опционально)
npm install
npm run build
```

## 🚀 Запуск сервера разработки

### Способ 1: Symfony CLI (рекомендуется)

```bash
symfony server:start
```

После запуска откройте браузер и перейдите на http://localhost:8000

### Способ 2: Встроенный PHP сервер

```bash
php bin/console cache:clear
php -S localhost:8000 -t public
```

## 🔧 Настройка веб-сервера (продакшн)

Для продакшн окружения настройте веб-сервер (Apache/Nginx) для работы с Symfony.

### Nginx конфигурация:

```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /path/to/mobile-shop/public;

    location / {
        try_files $uri /index.php$is_args$args;
    }

    location ~ ^/index\.php(/|$) {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_split_path_info ^(.+\.php)(/.*)$;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        fastcgi_param DOCUMENT_ROOT $realpath_root;
        internal;
    }

    location ~ \.php$ {
        return 404;
    }

    error_log /var/log/nginx/mobile_shop_error.log;
    access_log /var/log/nginx/mobile_shop_access.log;
}
```

### Apache конфигурация (.htaccess уже создан в public/):

```apache
<VirtualHost *:80>
    ServerName your-domain.com
    DocumentRoot /path/to/mobile-shop/public

    <Directory /path/to/mobile-shop/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/mobile_shop_error.log
    CustomLog ${APACHE_LOG_DIR}/mobile_shop_access.log combined
</VirtualHost>
```

## 🗄️ Настройка базы данных

### MySQL (рекомендуется для продакшн)

```sql
CREATE DATABASE mobile_shop CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'mobile_shop_user'@'localhost' IDENTIFIED BY 'secure_password';
GRANT ALL PRIVILEGES ON mobile_shop.* TO 'mobile_shop_user'@'localhost';
FLUSH PRIVILEGES;
```

### SQLite (для разработки)

Проект уже настроен для работы с SQLite. Просто измените DATABASE_URL в .env:

```env
DATABASE_URL="sqlite:///%kernel.project_dir%/var/data.db"
```

## 🔐 Создание администратора

После запуска приложения:

1. Зарегистрируйте нового пользователя: http://localhost:8000/registration
2. Войдите в админ панель: http://localhost:8000/admin
3. Используйте учетные данные созданного пользователя

## 🧪 Тестирование

```bash
# Запуск всех тестов
php bin/phpunit

# Запуск конкретного теста
php bin/phpunit tests/HomeControllerTest.php

# Проверка настроек системы
./test-setup.sh
```

## 📊 Админ панель

После запуска и входа в систему:
- **Админ панель**: http://localhost:8000/admin
- **Управление товарами**: Добавляйте мобильные телефоны и категории
- **Аналитика**: Просматривайте статистику продаж
- **Пользователи**: Управляйте учетными записями

## 🔧 Полезные команды

```bash
# Очистка кеша
php bin/console cache:clear

# Проверка безопасности
php bin/console security:check

# Генерация нового секретного ключа
php bin/console secrets:generate-keys

# Создание контроллера
php bin/console make:controller

# Создание сущности
php bin/console make:entity
```

## 🚀 Продакшн деплой

1. **Установите зависимости:**
   ```bash
   composer install --no-dev --optimize-autoloader
   ```

2. **Соберите ассеты:**
   ```bash
   npm run build
   ```

3. **Настройте переменные окружения:**
   ```bash
   composer dump-env prod
   ```

4. **Выполните миграции:**
   ```bash
   php bin/console doctrine:migrations:migrate
   ```

5. **Настройте веб-сервер** (Nginx/Apache) как описано выше

## 🆘 Решение проблем

### Ошибка подключения к базе данных:
- Проверьте настройки в `.env`
- Убедитесь что база данных создана и доступна
- Проверьте учетные данные пользователя БД

### Ошибка прав доступа:
```bash
chmod -R 755 var/
chmod -R 755 public/
```

### Ошибка кеша:
```bash
php bin/console cache:clear
rm -rf var/cache/*
```

## 📞 Поддержка

При возникновении проблем:
1. Проверьте логи Symfony: `var/log/`
2. Запустите `./test-setup.sh` для диагностики
3. Обратитесь к документации: `README.md`

---

🎉 **Готово! Ваш интернет-магазин мобильных телефонов запущен локально!**

Перейдите на http://localhost:8000 и начните использовать приложение!