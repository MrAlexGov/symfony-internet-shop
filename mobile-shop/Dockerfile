FROM php:8.2-fpm

# Устанавливаем системные зависимости
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    nodejs \
    npm \
    libicu-dev

# Очищаем кэш
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Устанавливаем PHP расширения
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip intl

# Устанавливаем Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Устанавливаем рабочую директорию
WORKDIR /var/www/html

# Копируем composer файлы сначала для лучшего кеширования
COPY composer.* ./

# Устанавливаем зависимости PHP (используем composer update для совместимости с PHP 8.2)
RUN composer update --no-dev --optimize-autoloader

# Копируем остальные файлы проекта
COPY . .

# Устанавливаем зависимости Node.js для assets (опционально)
# RUN npm install
# RUN npm run build

# Создаем необходимые директории
RUN mkdir -p var/log var/cache public/uploads

# Устанавливаем права доступа
RUN chown -R www-data:www-data var public/uploads
RUN chmod -R 755 var public/uploads

# Экспонируем порт
EXPOSE 9000

CMD ["php-fpm"]