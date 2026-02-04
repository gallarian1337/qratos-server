<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\User;
use App\Service\Picture\PictureInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

readonly class UserFactory implements UserFactoryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private PictureInterface $picture,
        private UserPasswordHasherInterface $passwordHasher
    ) {
    }

    public function create(array $data): User
    {
        $avatar = $data['avatar']->getData();
        $user = new User();
        $user->setEmail($data['email']->getData());
        $user->setNickname($data['nickname']->getData());
        $user->setPassword($this->passwordHasher->hashPassword($user, $data['password']->getData()));

        if (!is_null($avatar)) {
            $this->linkPicture($user, $avatar);
        }

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

    public function linkPicture(User $user, UploadedFile $file): void
    {
        $user->setAvatar($this->picture->upload($file));
    }

    /*public function linkPicture(User $user, Picture $picture): User
    {
        $user->setPicture($picture);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }*/
}
