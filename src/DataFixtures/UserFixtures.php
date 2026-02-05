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

        // fake users Twitch
        for ($i = 1; $i <= 5; $i++) {
            $userTwitch = new User();
            $userTwitch->setEmail('twitch-' . $i . '@quizz.test');
            $userTwitch->setNickname('twitch-' . $i);
            $userTwitch->setRoles(['ROLE_USER']);
            $userTwitch->setAccessToken('twitch-' . $i . '-token');

            $manager->persist($userTwitch);
        }

        // simple user
        for ($i = 1; $i <= 5; $i++) {
            $user = new User();
            $user->setEmail('user-' . $i . '@quizz.test');
            $user->setNickname('user-' . $i);
            $user->setRoles(['ROLE_USER']);
            $user->setPassword($this->passwordHasher->hashPassword($user, '12345'));

            $manager->persist($user);
        }

        $manager->flush();
    }
}
