<?php

namespace App\DataFixtures;

use App\Entity\Brand;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BrandFixtures extends Fixture
{
    public const BRAND_APPLE = 'brand-apple';
    public const BRAND_SAMSUNG = 'brand-samsung';
    public const BRAND_XIAOMI = 'brand-xiaomi';
    public const BRAND_HUAWEI = 'brand-huawei';
    public const BRAND_GOOGLE = 'brand-google';
    public const BRAND_ONEPLUS = 'brand-oneplus';

    public function load(ObjectManager $manager): void
    {
        $brands = [
            [
                'name' => 'Apple',
                'slug' => 'apple',
                'description' => 'Американская компания, производитель смартфонов iPhone, планшетов iPad и другой электроники.',
                'logo' => 'apple-logo.png',
                'reference' => self::BRAND_APPLE,
            ],
            [
                'name' => 'Samsung',
                'slug' => 'samsung',
                'description' => 'Южнокорейская компания, один из крупнейших производителей электроники и смартфонов.',
                'logo' => 'samsung-logo.png',
                'reference' => self::BRAND_SAMSUNG,
            ],
            [
                'name' => 'Xiaomi',
                'slug' => 'xiaomi',
                'description' => 'Китайская компания, известная своими смартфонами с отличным соотношением цена-качество.',
                'logo' => 'xiaomi-logo.png',
                'reference' => self::BRAND_XIAOMI,
            ],
            [
                'name' => 'Huawei',
                'slug' => 'huawei',
                'description' => 'Китайская компания, специализирующаяся на телекоммуникационном оборудовании и смартфонах.',
                'logo' => 'huawei-logo.png',
                'reference' => self::BRAND_HUAWEI,
            ],
            [
                'name' => 'Google Pixel',
                'slug' => 'google-pixel',
                'description' => 'Смартфоны от Google с чистой версией Android и отличными камерами.',
                'logo' => 'google-pixel-logo.png',
                'reference' => self::BRAND_GOOGLE,
            ],
            [
                'name' => 'OnePlus',
                'slug' => 'oneplus',
                'description' => 'Китайская компания, известная флагманскими смартфонами с высокой производительностью.',
                'logo' => 'oneplus-logo.png',
                'reference' => self::BRAND_ONEPLUS,
            ],
        ];

        foreach ($brands as $brandData) {
            $brand = new Brand();
            $brand->setName($brandData['name']);
            $brand->setSlug($brandData['slug']);
            $brand->setDescription($brandData['description']);
            $brand->setLogo($brandData['logo']);
            $brand->setIsActive(true);

            $manager->persist($brand);
            $this->addReference($brandData['reference'], $brand);
        }

        $manager->flush();
    }
}