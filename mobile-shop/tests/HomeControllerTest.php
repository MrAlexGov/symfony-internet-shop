<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    public function testHomePageLoadsSuccessfully(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('title', 'Mobile Shop - Интернет-магазин мобильных телефонов');
    }

    public function testHomePageHasCorrectStructure(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();

        // Проверяем наличие основных элементов
        $this->assertSelectorExists('nav.navbar');
        $this->assertSelectorTextContains('.navbar-brand', 'Mobile Shop');
        $this->assertSelectorExists('.jumbotron');
        $this->assertSelectorExists('footer');
    }

    public function testCatalogLinkIsPresent(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();

        // Проверяем наличие ссылок на каталог
        $catalogLink = $crawler->filter('a[href="#catalog"]');
        $this->assertGreaterThan(0, $catalogLink->count());
    }

    public function testFeaturesSectionExists(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();

        // Проверяем наличие секции преимуществ
        $features = $crawler->filter('.card .fa-shield-alt, .card .fa-truck, .card .fa-headset');
        $this->assertEquals(3, $features->count());
    }
}
