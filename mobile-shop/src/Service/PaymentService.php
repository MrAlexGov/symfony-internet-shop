<?php

namespace App\Service;

use App\Entity\Cart;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class PaymentService
{
    private HttpClientInterface $httpClient;
    private string $stripeSecretKey;
    private string $yookassaShopId;
    private string $yookassaSecretKey;

    public function __construct(
        HttpClientInterface $httpClient,
        string $stripeSecretKey = '',
        string $yookassaShopId = '',
        string $yookassaSecretKey = ''
    ) {
        $this->httpClient = $httpClient;
        $this->stripeSecretKey = $stripeSecretKey;
        $this->yookassaShopId = $yookassaShopId;
        $this->yookassaSecretKey = $yookassaSecretKey;
    }

    public function createPayment(Cart $cart, string $paymentMethod): PaymentLink
    {
        switch ($paymentMethod) {
            case 'stripe':
                return $this->createStripePayment($cart);
            case 'yookassa':
                return $this->createYookassaPayment($cart);
            default:
                throw new \InvalidArgumentException('Неподдерживаемый метод оплаты');
        }
    }

    private function createStripePayment(Cart $cart): PaymentLink
    {
        // Заглушка для Stripe интеграции
        // В реальном проекте здесь будет интеграция с Stripe API

        $paymentLink = new PaymentLink();
        $paymentLink->setUrl('https://stripe-payment-demo.com');
        $paymentLink->setPaymentId('stripe_' . uniqid());

        return $paymentLink;
    }

    private function createYookassaPayment(Cart $cart): PaymentLink
    {
        // Заглушка для ЮKassa интеграции
        // В реальном проекте здесь будет интеграция с ЮKassa API

        $paymentLink = new PaymentLink();
        $paymentLink->setUrl('https://yookassa-payment-demo.com');
        $paymentLink->setPaymentId('yookassa_' . uniqid());

        return $paymentLink;
    }

    public function handleWebhook(array $data): void
    {
        // Обработка вебхуков от платежных систем
        // Заглушка для демонстрации
    }
}

class PaymentLink
{
    private string $url;
    private string $paymentId;

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;
        return $this;
    }

    public function getPaymentId(): string
    {
        return $this->paymentId;
    }

    public function setPaymentId(string $paymentId): self
    {
        $this->paymentId = $paymentId;
        return $this;
    }
}