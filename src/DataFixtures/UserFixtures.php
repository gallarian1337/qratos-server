<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserFixtures extends Fixture
{
    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        // admin 1
        $admin1 = new User();
        $admin1->setEmail('admin1@quizz.test');
        $admin1->setNickname('admin1');
        $admin1->setRoles(['ROLE_ADMIN']);
        $admin1->setPassword($this->passwordHasher->hashPassword($admin1, '1234567891011'));
        $this->addReference('admin1', $admin1);

        $manager->persist($admin1);

        // admin 2
        $admin2 = new User();
        $admin2->setEmail('admin2@quizz.test');
        $admin2->setNickname('admin2');
        $admin2->setRoles(['ROLE_ADMIN']);
        $admin2->setPassword($this->passwordHasher->hashPassword($admin2, '1234567891011'));
        $this->addReference('admin2', $admin2);

        $manager->persist($admin2);

        $manager->flush();
    }
}
