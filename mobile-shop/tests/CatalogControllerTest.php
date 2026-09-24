<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CatalogControllerTest extends WebTestCase
{
    public function testCatalogPageLoadsAndShowsProducts(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/catalog');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('.card');
    }

    public function testCatalogSearchFiltersProducts(): void
    {
        $client = static::createClient();
        $client->request('GET', '/catalog', ['search' => 'iPhone 15 Pro Max']);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('body', 'iPhone 15 Pro Max');
    }

    public function testCatalogCategoryPageLoads(): void
    {
        $client = static::createClient();
        $client->request('GET', '/catalog/category/smartfony');

        $this->assertResponseIsSuccessful();
    }

    public function testCatalogCategoryPageNotFoundForUnknownSlug(): void
    {
        $client = static::createClient();
        $client->request('GET', '/catalog/category/does-not-exist');

        $this->assertResponseStatusCodeSame(404);
    }

    public function testProductPageLoads(): void
    {
        $client = static::createClient();
        $client->request('GET', '/product/iphone-15-pro-max');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('body', 'iPhone 15 Pro Max');
    }

    public function testProductPageNotFoundForUnknownSlug(): void
    {
        $client = static::createClient();
        $client->request('GET', '/product/does-not-exist');

        $this->assertResponseStatusCodeSame(404);
    }
}
