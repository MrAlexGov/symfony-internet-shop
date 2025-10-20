<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SimpleUserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Создаем простого пользователя для тестирования
        $user = new User();
        $user->setEmail('user@mobilshop.ru');
        $user->setFirstName('Тестовый');
        $user->setLastName('Пользователь');
        $user->setPhone('+7 (495) 987-65-43');
        $user->setIsVerified(true);
        $user->setVerifiedAt(new \DateTimeImmutable());
        $user->setRoles(['ROLE_USER']);
        $user->setPassword(password_hash('user123', PASSWORD_BCRYPT));

        $manager->persist($user);
        $manager->flush();
    }
}