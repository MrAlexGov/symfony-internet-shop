<?php

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;

class ImageService
{
    private string $uploadDir;
    private string $placeholderImage;
    private Filesystem $filesystem;

    public function __construct(string $projectDir)
    {
        $this->uploadDir = $projectDir . '/public/uploads/products';
        $this->placeholderImage = $projectDir . '/public/images/no-photo.png';
        $this->filesystem = new Filesystem();
    }

    /**
     * Проверяет существование изображения товара
     */
    public function imageExists(string $imageName): bool
    {
        if (!$imageName) {
            return false;
        }

        $imagePath = $this->uploadDir . '/' . $imageName;
        return $this->filesystem->exists($imagePath);
    }

    /**
     * Возвращает путь к изображению или заглушке
     */
    public function getImagePath(string $imageName): string
    {
        if ($this->imageExists($imageName)) {
            return '/uploads/products/' . $imageName;
        }

        return '/images/no-photo.png';
    }

    /**
     * Возвращает путь к заглушке для изображений
     */
    public function getPlaceholderPath(): string
    {
        return '/images/no-photo.png';
    }

    /**
     * Создает безопасное имя файла изображения
     */
    public function sanitizeImageName(string $filename): string
    {
        // Удаляем пробелы и специальные символы, оставляем только буквы, цифры, дефисы и точки
        $filename = preg_replace('/[^a-zA-Z0-9\-\.]/', '_', $filename);

        // Добавляем временную метку для уникальности
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $nameWithoutExtension = pathinfo($filename, PATHINFO_FILENAME);

        return $nameWithoutExtension . '_' . time() . '.' . $extension;
    }

    /**
     * Проверяет, является ли файл изображением
     */
    public function isImage(File $file): bool
    {
        $mimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

        return in_array($file->getMimeType(), $mimeTypes);
    }

    /**
     * Создает миниатюру изображения
     */
    public function createThumbnail(string $sourcePath, string $thumbnailPath, int $width = 300, int $height = 300): bool
    {
        try {
            $sourcePath = $this->uploadDir . '/' . basename($sourcePath);
            $thumbnailPath = $this->uploadDir . '/thumbnails/' . basename($thumbnailPath);

            // Создаем директорию для миниатюр если она не существует
            $this->filesystem->mkdir(dirname($thumbnailPath));

            // Здесь можно добавить логику создания миниатюр с помощью GD или ImageMagick
            // Пока просто копируем оригинал
            if ($this->filesystem->exists($sourcePath)) {
                $this->filesystem->copy($sourcePath, $thumbnailPath);
                return true;
            }

            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Удаляет изображение товара
     */
    public function deleteImage(string $imageName): bool
    {
        try {
            $imagePath = $this->uploadDir . '/' . $imageName;

            if ($this->filesystem->exists($imagePath)) {
                $this->filesystem->remove($imagePath);

                // Также удаляем миниатюру если она существует
                $thumbnailPath = $this->uploadDir . '/thumbnails/' . $imageName;
                if ($this->filesystem->exists($thumbnailPath)) {
                    $this->filesystem->remove($thumbnailPath);
                }

                return true;
            }

            return false;
        } catch (\Exception $e) {
            return false;
        }
    }
}