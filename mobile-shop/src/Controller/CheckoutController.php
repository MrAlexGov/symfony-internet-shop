<?php

namespace App\Controller;

use App\Repository\CartRepository;
use App\Service\PaymentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CheckoutController extends AbstractController
{
    #[Route('/checkout', name: 'app_checkout')]
    public function index(Request $request, PaymentService $paymentService, CartRepository $cartRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $cart = $cartRepository->findOneBy(['user' => $user]);
        if (!$cart || $cart->isEmpty()) {
            $this->addFlash('warning', 'Ваша корзина пуста');
            return $this->redirectToRoute('app_home');
        }

        if ($request->isMethod('POST')) {
            $paymentMethod = $request->request->get('payment_method');

            try {
                $paymentLink = $paymentService->createPayment($cart, $paymentMethod);

                return $this->redirect($paymentLink->getUrl());
            } catch (\Exception $e) {
                $this->addFlash('error', 'Ошибка при создании платежа: ' . $e->getMessage());
            }
        }

        return $this->render('checkout/index.html.twig', [
            'cart' => $cart,
            'total' => $cart->getTotal(),
        ]);
    }

    #[Route('/payment/success', name: 'app_payment_success')]
    public function success(): Response
    {
        $this->addFlash('success', 'Оплата прошла успешно! Ваш заказ обрабатывается.');
        return $this->redirectToRoute('app_profile');
    }

    #[Route('/payment/cancel', name: 'app_payment_cancel')]
    public function cancel(): Response
    {
        $this->addFlash('warning', 'Оплата была отменена');
        return $this->redirectToRoute('app_checkout');
    }
}
