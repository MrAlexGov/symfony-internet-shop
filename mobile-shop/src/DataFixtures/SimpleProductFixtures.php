<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\Brand;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SimpleProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Получаем существующие бренды и категории из базы данных
        $appleBrand = $manager->getRepository(Brand::class)->findOneBy(['slug' => 'apple']);
        $samsungBrand = $manager->getRepository(Brand::class)->findOneBy(['slug' => 'samsung']);
        $xiaomiBrand = $manager->getRepository(Brand::class)->findOneBy(['slug' => 'xiaomi']);
        $huaweiBrand = $manager->getRepository(Brand::class)->findOneBy(['slug' => 'huawei']);
        $googleBrand = $manager->getRepository(Brand::class)->findOneBy(['slug' => 'google-pixel']);
        $oneplusBrand = $manager->getRepository(Brand::class)->findOneBy(['slug' => 'oneplus']);

        $smartphonesCategory = $manager->getRepository(Category::class)->findOneBy(['slug' => 'smartfony']);
        $featurePhonesCategory = $manager->getRepository(Category::class)->findOneBy(['slug' => 'knopochnye-telefony']);
        $tabletsCategory = $manager->getRepository(Category::class)->findOneBy(['slug' => 'planshety']);
        $accessoriesCategory = $manager->getRepository(Category::class)->findOneBy(['slug' => 'aksessuary']);
        $smartwatchesCategory = $manager->getRepository(Category::class)->findOneBy(['slug' => 'smart-chasy']);

        $products = [
            // Смартфоны Apple (6 товаров)
            ['iPhone 15 Pro Max', 'iphone-15-pro-max', 'IP15PM-001', 'Флагманский смартфон Apple с титановым корпусом', '129990', 8, $appleBrand, true, $smartphonesCategory],
            ['iPhone 15 Pro', 'iphone-15-pro-official', 'IP15P-002', 'Профессиональный смартфон Apple', '109990', 12, $appleBrand, true, $smartphonesCategory],
            ['iPhone 15', 'iphone-15', 'IP15-001', 'Современный смартфон Apple', '89990', 20, $appleBrand, false, $smartphonesCategory],
            ['iPhone 14 Pro Max', 'iphone-14-pro-max', 'IP14PM-001', 'Мощный смартфон предыдущего поколения', '99990', 15, $appleBrand, false, $smartphonesCategory],
            ['iPhone 14', 'iphone-14', 'IP14-001', 'Надежный смартфон Apple', '79990', 25, $appleBrand, false, $smartphonesCategory],
            ['iPhone 13', 'iphone-13', 'IP13-001', 'Популярная модель iPhone', '69990', 30, $appleBrand, false, $smartphonesCategory],

            // Смартфоны Samsung (9 товаров)
            ['Samsung Galaxy S24 Ultra', 'samsung-galaxy-s24-ultra', 'SGS24U-001', 'Флагман Samsung с S Pen', '119990', 10, $samsungBrand, true, $smartphonesCategory],
            ['Samsung Galaxy S24+', 'samsung-galaxy-s24-plus-premium', 'SGS24P-002', 'Премиальный смартфон Samsung', '99990', 15, $samsungBrand, true, $smartphonesCategory],
            ['Samsung Galaxy S24', 'samsung-galaxy-s24-compact', 'SGS24-002', 'Компактный флагман Samsung', '89990', 20, $samsungBrand, false, $smartphonesCategory],
            ['Samsung Galaxy S23 Ultra', 'samsung-galaxy-s23-ultra', 'SGS23U-001', 'Мощный смартфон Samsung', '99990', 18, $samsungBrand, false, $smartphonesCategory],
            ['Samsung Galaxy A54', 'samsung-galaxy-a54', 'SGA54-001', 'Среднебюджетный смартфон', '39990', 35, $samsungBrand, false, $smartphonesCategory],
            ['Samsung Galaxy A34', 'samsung-galaxy-a34', 'SGA34-001', 'Доступный смартфон Samsung', '29990', 40, $samsungBrand, false, $smartphonesCategory],
            ['Samsung Galaxy A14', 'samsung-galaxy-a14', 'SGA14-001', 'Бюджетный смартфон', '19990', 50, $samsungBrand, false, $smartphonesCategory],
            ['Samsung Galaxy Z Fold5', 'samsung-galaxy-z-fold5', 'SGZF5-001', 'Складной смартфон Samsung', '179990', 5, $samsungBrand, true, $smartphonesCategory],
            ['Samsung Galaxy Z Flip5', 'samsung-galaxy-z-flip5', 'SGZF5F-001', 'Компактный складной смартфон', '99990', 8, $samsungBrand, false, $smartphonesCategory],

            // Смартфоны Xiaomi (8 товаров)
            ['Xiaomi 14 Ultra', 'xiaomi-14-ultra', 'X14U-001', 'Флагман Xiaomi с камерой Leica', '89990', 12, $xiaomiBrand, true, $smartphonesCategory],
            ['Xiaomi 14', 'xiaomi-14-premium', 'X14-002', 'Премиальный смартфон Xiaomi', '79990', 15, $xiaomiBrand, false, $smartphonesCategory],
            ['Xiaomi 13T Pro', 'xiaomi-13t-pro', 'X13TP-001', 'Производительный смартфон', '59990', 20, $xiaomiBrand, false, $smartphonesCategory],
            ['Xiaomi 13T', 'xiaomi-13t', 'X13T-001', 'Смартфон с отличной камерой', '49990', 25, $xiaomiBrand, false, $smartphonesCategory],
            ['Xiaomi Redmi Note 13 Pro', 'xiaomi-redmi-note-13-pro', 'XRN13P-001', 'Популярный смартфон Xiaomi', '34990', 30, $xiaomiBrand, false, $smartphonesCategory],
            ['Xiaomi Redmi Note 13', 'xiaomi-redmi-note-13', 'XRN13-001', 'Бюджетный смартфон Xiaomi', '24990', 40, $xiaomiBrand, false, $smartphonesCategory],
            ['Xiaomi Redmi 13C', 'xiaomi-redmi-13c', 'XR13C-001', 'Доступный смартфон', '14990', 50, $xiaomiBrand, false, $smartphonesCategory],
            ['Xiaomi 13 Lite', 'xiaomi-13-lite', 'X13L-001', 'Компактный смартфон Xiaomi', '39990', 25, $xiaomiBrand, false, $smartphonesCategory],

            // Смартфоны Huawei (5 товаров)
            ['Huawei P60 Pro', 'huawei-p60-pro', 'HP60P-001', 'Флагман Huawei с отличной камерой', '79990', 10, $huaweiBrand, true, $smartphonesCategory],
            ['Huawei Mate 50 Pro', 'huawei-mate-50-pro', 'HM50P-001', 'Премиальный смартфон Huawei', '69990', 12, $huaweiBrand, false, $smartphonesCategory],
            ['Huawei nova 11 Pro', 'huawei-nova-11-pro', 'HN11P-001', 'Стильный смартфон Huawei', '49990', 20, $huaweiBrand, false, $smartphonesCategory],
            ['Huawei nova 11', 'huawei-nova-11', 'HN11-001', 'Доступный смартфон Huawei', '39990', 25, $huaweiBrand, false, $smartphonesCategory],
            ['Huawei Mate X3', 'huawei-mate-x3', 'HMX3-001', 'Складной смартфон Huawei', '149990', 3, $huaweiBrand, true, $smartphonesCategory],

            // Смартфоны Google Pixel (4 товара)
            ['Google Pixel 8 Pro', 'google-pixel-8-pro', 'GP8P-001', 'Флагман Google с чистым Android', '79990', 8, $googleBrand, true, $smartphonesCategory],
            ['Google Pixel 8', 'google-pixel-8-compact', 'GP8-002', 'Компактный флагман Google', '69990', 12, $googleBrand, false, $smartphonesCategory],
            ['Google Pixel 7a', 'google-pixel-7a', 'GP7A-001', 'Доступный Pixel с отличной камерой', '49990', 20, $googleBrand, false, $smartphonesCategory],
            ['Google Pixel Fold', 'google-pixel-fold', 'GPF-001', 'Складной смартфон Google', '159990', 2, $googleBrand, true, $smartphonesCategory],

            // Смартфоны OnePlus (4 товара)
            ['OnePlus 12', 'oneplus-12', 'OP12-001', 'Флагман OnePlus с быстрой зарядкой', '69990', 15, $oneplusBrand, true, $smartphonesCategory],
            ['OnePlus 11', 'oneplus-11', 'OP11-001', 'Мощный смартфон OnePlus', '59990', 18, $oneplusBrand, false, $smartphonesCategory],
            ['OnePlus Nord 3', 'oneplus-nord-3', 'OPN3-001', 'Среднебюджетный OnePlus', '39990', 25, $oneplusBrand, false, $smartphonesCategory],
            ['OnePlus 10T', 'oneplus-10t', 'OP10T-001', 'Быстрый смартфон OnePlus', '49990', 20, $oneplusBrand, false, $smartphonesCategory],

            // Планшеты (6 товаров)
            ['Apple iPad Pro 12.9"', 'apple-ipad-pro-129', 'AIPP129-001', 'Профессиональный планшет Apple', '109990', 10, $appleBrand, true, $tabletsCategory],
            ['Apple iPad Air', 'apple-ipad-air', 'AIPA-001', 'Легкий и мощный планшет', '69990', 15, $appleBrand, false, $tabletsCategory],
            ['Samsung Galaxy Tab S9 Ultra', 'samsung-galaxy-tab-s9-ultra', 'SGTS9U-001', 'Большой планшет Samsung', '119990', 8, $samsungBrand, true, $tabletsCategory],
            ['Samsung Galaxy Tab A8', 'samsung-galaxy-tab-a8', 'SGTA8-001', 'Доступный планшет Samsung', '29990', 25, $samsungBrand, false, $tabletsCategory],
            ['Xiaomi Pad 6', 'xiaomi-pad-6', 'XP6-001', 'Производительный планшет Xiaomi', '39990', 20, $xiaomiBrand, false, $tabletsCategory],
            ['Huawei MatePad Pro', 'huawei-matepad-pro', 'HMP-001', 'Премиальный планшет Huawei', '59990', 12, $huaweiBrand, false, $tabletsCategory],

            // Смарт-часы (6 товаров)
            ['Apple Watch Ultra 2', 'apple-watch-ultra-2', 'AWU2-001', 'Профессиональные смарт-часы', '79990', 10, $appleBrand, true, $smartwatchesCategory],
            ['Apple Watch Series 9', 'apple-watch-series-9', 'AWS9-001', 'Современные смарт-часы Apple', '49990', 20, $appleBrand, false, $smartwatchesCategory],
            ['Samsung Galaxy Watch6 Classic', 'samsung-galaxy-watch6-classic', 'SGW6C-001', 'Классические смарт-часы Samsung', '39990', 15, $samsungBrand, false, $smartwatchesCategory],
            ['Samsung Galaxy Watch6', 'samsung-galaxy-watch6', 'SGW6-001', 'Спортивные смарт-часы Samsung', '34990', 18, $samsungBrand, false, $smartwatchesCategory],
            ['Xiaomi Watch S1 Pro', 'xiaomi-watch-s1-pro', 'XWS1P-001', 'Премиальные смарт-часы Xiaomi', '29990', 25, $xiaomiBrand, false, $smartwatchesCategory],
            ['Huawei Watch GT 4', 'huawei-watch-gt-4', 'HWGT4-001', 'Смарт-часы с длительной батареей', '24990', 30, $huaweiBrand, false, $smartwatchesCategory],

            // Кнопочные телефоны (2 товара)
            ['Nokia 3310', 'nokia-3310', 'N3310-001', 'Легендарный кнопочный телефон', '4990', 50, $samsungBrand, false, $featurePhonesCategory],
            ['Philips Xenium E182', 'philips-xenium-e182', 'PXE182-001', 'Телефон с мощной батареей', '3990', 40, $samsungBrand, false, $featurePhonesCategory],

            // Аксессуары (6 товаров)
            ['Apple AirPods Pro 2', 'apple-airpods-pro-2', 'AAP2-001', 'Беспроводные наушники Apple', '24990', 30, $appleBrand, true, $accessoriesCategory],
            ['Samsung Galaxy Buds2 Pro', 'samsung-galaxy-buds2-pro', 'SGB2P-001', 'Премиальные наушники Samsung', '19990', 35, $samsungBrand, false, $accessoriesCategory],
            ['Xiaomi Wireless Charging Pad', 'xiaomi-wireless-charging-pad', 'XWCP-001', 'Беспроводная зарядка Xiaomi', '2990', 50, $xiaomiBrand, false, $accessoriesCategory],
            ['Huawei FreeBuds Pro 3', 'huawei-freebuds-pro-3', 'HFBP3-001', 'Наушники с активным шумоподавлением', '15990', 25, $huaweiBrand, false, $accessoriesCategory],
            ['Apple MagSafe Charger', 'apple-magsafe-charger', 'AMC-001', 'Магнитная зарядка Apple', '4990', 40, $appleBrand, false, $accessoriesCategory],
            ['Samsung 25W Super Fast Charger', 'samsung-25w-super-fast-charger', 'S25WF-001', 'Быстрая зарядка Samsung', '2990', 60, $samsungBrand, false, $accessoriesCategory],

            // Дополнительные популярные смартфоны (25 товаров)
            ['Nothing Phone (2a)', 'nothing-phone-2a', 'NP2A-001', 'Уникальный смартфон Nothing', '39990', 15, $oneplusBrand, false, $smartphonesCategory],
            ['ASUS ROG Phone 8', 'asus-rog-phone-8', 'ARGP8-001', 'Игровой смартфон ASUS', '89990', 8, $xiaomiBrand, true, $smartphonesCategory],
            ['Sony Xperia 1 V', 'sony-xperia-1-v', 'SXP1V-001', 'Флагман Sony с отличной камерой', '99990', 5, $samsungBrand, false, $smartphonesCategory],
            ['Motorola Edge 40 Pro', 'motorola-edge-40-pro', 'ME40P-001', 'Премиальный смартфон Motorola', '69990', 12, $huaweiBrand, false, $smartphonesCategory],
            ['Realme C55', 'realme-c55', 'RC55-001', 'Бюджетный смартфон Realme', '15990', 45, $xiaomiBrand, false, $smartphonesCategory],
            ['Tecno Camon 20', 'tecno-camon-20', 'TC20-001', 'Смартфон Tecno с хорошей камерой', '18990', 35, $huaweiBrand, false, $smartphonesCategory],
            ['Infinix Hot 30', 'infinix-hot-30', 'IH30-001', 'Доступный смартфон Infinix', '12990', 50, $xiaomiBrand, false, $smartphonesCategory],
            ['POCO X5 Pro', 'poco-x5-pro', 'PX5P-001', 'Производительный бюджетный смартфон', '29990', 25, $xiaomiBrand, false, $smartphonesCategory],
            ['Honor 90', 'honor-90', 'H90-001', 'Смартфон Honor с AMOLED экраном', '39990', 20, $huaweiBrand, false, $smartphonesCategory],
            ['Vivo V27', 'vivo-v27', 'VV27-001', 'Смартфон Vivo с отличным дизайном', '34990', 22, $huaweiBrand, false, $smartphonesCategory],
            ['OPPO Reno10 Pro', 'oppo-reno10-pro', 'OR10P-001', 'Смартфон OPPO с портретной камерой', '49990', 18, $huaweiBrand, false, $smartphonesCategory],
            ['Meizu 20 Pro', 'meizu-20-pro', 'M20P-001', 'Флагман Meizu с отличным звуком', '59990', 10, $xiaomiBrand, false, $smartphonesCategory],
            ['Lenovo Legion Y90', 'lenovo-legion-y90', 'LLY90-001', 'Игровой смартфон Lenovo', '69990', 8, $xiaomiBrand, true, $smartphonesCategory],
            ['ZTE Axon 50 Ultra', 'zte-axon-50-ultra', 'ZA50U-001', 'Премиальный смартфон ZTE', '79990', 6, $huaweiBrand, false, $smartphonesCategory],
            ['iQOO 12', 'iqoo-12', 'I12-001', 'Высокопроизводительный смартфон', '65990', 12, $xiaomiBrand, false, $smartphonesCategory],
            ['Black Shark 5 Pro', 'black-shark-5-pro', 'BS5P-001', 'Игровой смартфон Black Shark', '59990', 10, $xiaomiBrand, true, $smartphonesCategory],
            ['Red Magic 8 Pro', 'red-magic-8-pro', 'RM8P-001', 'Мощный игровой смартфон', '69990', 8, $xiaomiBrand, false, $smartphonesCategory],
            ['Apple iPhone 15 Plus', 'apple-iphone-15-plus', 'AIP15P-001', 'iPhone с большим экраном', '109990', 12, $appleBrand, false, $smartphonesCategory],
            ['Samsung Galaxy S24+', 'samsung-galaxy-s24-plus', 'SGS24P-001', 'Samsung с увеличенным экраном', '99990', 15, $samsungBrand, false, $smartphonesCategory],
            ['Xiaomi 14 Pro', 'xiaomi-14-pro', 'X14P-001', 'Xiaomi с премиальным экраном', '79990', 10, $xiaomiBrand, false, $smartphonesCategory],
            ['Samsung Galaxy S24', 'samsung-galaxy-s24', 'SGS24-001', 'Компактный флагман Samsung', '89990', 20, $samsungBrand, false, $smartphonesCategory],
            ['Google Pixel 8', 'google-pixel-8', 'GP8-001', 'Компактный Pixel', '69990', 15, $googleBrand, false, $smartphonesCategory],
            ['Xiaomi 14', 'xiaomi-14', 'X14-001', 'Компактный флагман Xiaomi', '79990', 12, $xiaomiBrand, false, $smartphonesCategory],
            ['Honor Magic5 Pro', 'honor-magic5-pro', 'HM5P-001', 'Смартфон Honor с большой батареей', '69990', 14, $huaweiBrand, false, $smartphonesCategory],
            ['Motorola Moto G Stylus', 'motorola-moto-g-stylus', 'MMGS-001', 'Смартфон Motorola со стилусом', '44990', 20, $huaweiBrand, false, $smartphonesCategory],
        ];

        foreach ($products as $productData) {
            $product = new Product();
            $product->setName($productData[0]);
            $product->setSlug($productData[1]);
            $product->setSku($productData[2]);
            $product->setDescription($productData[3]);
            $product->setPrice($productData[4]);
            $product->setStock($productData[5]);
            $product->setIsActive(true);
            $product->setIsFeatured($productData[7]);
            $product->setBrand($productData[6]);
            $product->setCategory($productData[8]);

            $manager->persist($product);
        }

        $manager->flush();
    }
}