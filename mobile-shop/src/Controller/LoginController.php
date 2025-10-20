<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

final class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        // Если пользователь уже авторизован, перенаправляем на главную
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        // Получаем ошибки аутентификации, если они есть
        $error = $authenticationUtils->getLastAuthenticationError();
        // Получаем последний введенный email
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('login/index.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route('/check-login', name: 'app_check_login', methods: ['POST'])]
    public function checkLogin(): Response
    {
        // Этот метод обрабатывает форму аутентификации
        // Symfony Security обрабатывает аутентификацию автоматически
        // Если аутентификация успешна, пользователь будет перенаправлен на default_target_path
        // Если неудачна, Symfony автоматически перенаправит на login_path с ошибкой

        throw new \LogicException('Этот метод не должен вызываться напрямую. Symfony Security обрабатывает аутентификацию автоматически.');
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): Response
    {
        // Symfony Security обрабатывает logout автоматически
        throw new \LogicException('Этот метод не должен вызываться напрямую. Symfony Security обрабатывает logout автоматически.');
    }
}
