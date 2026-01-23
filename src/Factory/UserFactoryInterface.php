<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\User;

interface UserFactoryInterface
{
    public function create(array $data): User;
    public function update(User $user): User;
    public function delete(User $user): bool;
    //public function linkPicture(User $user, Picture $picture): User;
}
