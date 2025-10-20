<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public const CATEGORY_SMARTPHONES = 'category-smartphones';
    public const CATEGORY_FEATURE_PHONES = 'category-feature-phones';
    public const CATEGORY_ACCESSORIES = 'category-accessories';
    public const CATEGORY_TABLETS = 'category-tablets';
    public const CATEGORY_SMARTWATCHES = 'category-smartwatches';

    public function load(ObjectManager $manager): void
    {
        $categories = [
            [
                'name' => 'Смартфоны',
                'slug' => 'smartfony',
                'description' => 'Современные мобильные телефоны с сенсорными экранами и множеством функций.',
                'icon' => 'fas fa-mobile-alt',
                'sort_order' => 1,
                'reference' => self::CATEGORY_SMARTPHONES,
            ],
            [
                'name' => 'Кнопочные телефоны',
                'slug' => 'knopochnye-telefony',
                'description' => 'Классические мобильные телефоны с кнопочным управлением.',
                'icon' => 'fas fa-mobile',
                'sort_order' => 2,
                'reference' => self::CATEGORY_FEATURE_PHONES,
            ],
            [
                'name' => 'Планшеты',
                'slug' => 'planshety',
                'description' => 'Портативные устройства для работы и развлечений.',
                'icon' => 'fas fa-tablet-alt',
                'sort_order' => 3,
                'reference' => self::CATEGORY_TABLETS,
            ],
            [
                'name' => 'Аксессуары',
                'slug' => 'aksessuary',
                'description' => 'Чехлы, защитные стекла, зарядные устройства и другие аксессуары.',
                'icon' => 'fas fa-headphones',
                'sort_order' => 4,
                'reference' => self::CATEGORY_ACCESSORIES,
            ],
            [
                'name' => 'Смарт-часы',
                'slug' => 'smart-chasy',
                'description' => 'Умные часы и фитнес-браслеты для спорта и повседневного использования.',
                'icon' => 'fas fa-clock',
                'sort_order' => 5,
                'reference' => self::CATEGORY_SMARTWATCHES,
            ],
        ];

        foreach ($categories as $categoryData) {
            $category = new Category();
            $category->setName($categoryData['name']);
            $category->setSlug($categoryData['slug']);
            $category->setDescription($categoryData['description']);
            $category->setIcon($categoryData['icon']);
            $category->setSortOrder($categoryData['sort_order']);
            $category->setIsActive(true);

            $manager->persist($category);
            $this->addReference($categoryData['reference'], $category);
        }

        $manager->flush();
    }
}