<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginControllerTest extends WebTestCase
{
    public function testLoginPageLoads(): void
    {
        $client = static::createClient();
        $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');
    }

    public function testSuccessfulLoginRedirectsHome(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Войти')->form([
            '_username' => 'user@mobilshop.ru',
            '_password' => 'user123',
        ]);
        $client->submit($form);

        $this->assertResponseRedirects('/');
    }

    public function testFailedLoginShowsError(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Войти')->form([
            '_username' => 'user@mobilshop.ru',
            '_password' => 'wrong-password',
        ]);
        $client->submit($form);
        $client->followRedirect();

        $this->assertSelectorExists('.alert-danger');
    }

    public function testProfileRequiresLogin(): void
    {
        $client = static::createClient();
        $client->request('GET', '/profile');

        $this->assertResponseRedirects('/login');
    }
}
