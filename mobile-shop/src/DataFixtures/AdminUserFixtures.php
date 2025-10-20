<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AdminUserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Создаем администратора для тестирования
        $admin = new User();
        $admin->setEmail('admin@mobilshop.ru');
        $admin->setFirstName('Администратор');
        $admin->setLastName('Сайта');
        $admin->setPhone('+7 (495) 123-45-67');
        $admin->setIsVerified(true);
        $admin->setVerifiedAt(new \DateTimeImmutable());
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword(password_hash('admin123', PASSWORD_BCRYPT));

        $manager->persist($admin);
        $manager->flush();
    }
}