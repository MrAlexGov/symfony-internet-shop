<?php

namespace App\Tests;

use App\Entity\Product;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CartControllerTest extends WebTestCase
{
    public function testCartPageRequiresLogin(): void
    {
        $client = static::createClient();
        $client->request('GET', '/cart');

        $this->assertResponseRedirects('/login');
    }

    public function testAddToCartRequiresLogin(): void
    {
        $client = static::createClient();
        $client->request('POST', '/cart/add/1');

        // access_control (^/cart) перехватывает и /cart/add/*, поэтому анонимный
        // запрос редиректится на логин раньше, чем доходит до ручной проверки
        // $this->getUser() внутри CartController::add() — контроллерный 401 на
        // практике недостижим для анонима, это не баг, но стоит иметь в виду.
        $this->assertResponseRedirects('/login');
    }

    public function testLoggedInUserCanAddProductToCart(): void
    {
        $client = static::createClient();
        $container = static::getContainer();

        $user = $container->get('doctrine')->getRepository(User::class)
            ->findOneBy(['email' => 'user@mobilshop.ru']);
        $product = $container->get('doctrine')->getRepository(Product::class)
            ->findOneBy(['slug' => 'iphone-15-pro-max']);

        $client->loginUser($user);

        $crawler = $client->request('GET', '/product/' . $product->getSlug());
        $csrfToken = $crawler->filter('meta[name="csrf-token"]')->attr('content');

        $client->request('POST', '/cart/add/' . $product->getId(), [
            'quantity' => 1,
            '_token' => $csrfToken,
        ]);

        $this->assertResponseIsSuccessful();
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertTrue($response['success']);

        $client->request('GET', '/cart');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('body', 'iPhone 15 Pro Max');
    }
}
