<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class NavigationController extends AbstractController
{
    #[Route('/navigation/counters', name: 'app_navigation_counters', methods: ['GET'])]
    public function counters(): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse([
                'cartCount' => 0,
                'wishlistCount' => 0,
                'compareCount' => 0
            ]);
        }

        $cartCount = 0;
        $wishlistCount = 0;
        $compareCount = 0;

        if ($user->getCart() && !$user->getCart()->isEmpty()) {
            $cartCount = $user->getCart()->getTotalItems();
        }

        if ($user->getWishlist() && !$user->getWishlist()->isEmpty()) {
            $wishlistCount = $user->getWishlist()->getTotalItems();
        }

        if ($user->getCompare() && !$user->getCompare()->isEmpty()) {
            $compareCount = $user->getCompare()->getTotalItems();
        }

        return new JsonResponse([
            'cartCount' => $cartCount,
            'wishlistCount' => $wishlistCount,
            'compareCount' => $compareCount
        ]);
    }
}