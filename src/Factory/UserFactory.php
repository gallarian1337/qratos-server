<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

readonly class UserFactory implements UserFactoryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher
    ) {
    }

    public function create(array $data): User
    {
        $user = new User();
        $user->setEmail($data['email']->getData());
        $user->setNickname($data['nickname']->getData());
        $user->setPassword($this->passwordHasher->hashPassword($user, $data['password']->getData()));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    public function update(User $user): User
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new User();
    }

    public function delete(User $user): bool
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();

        return false;
    }

    /*public function linkPicture(User $user, Picture $picture): User
    {
        $user->setPicture($picture);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }*/
}
