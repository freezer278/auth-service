<?php


namespace App\DataMappers\User;

use App\Exceptions\ModelNotFoundException;
use App\Models\UserInterface;

interface UserMapperInterface
{
    /**
     * @param int $id
     * @return UserInterface
     */
    public function findById(int $id): ?UserInterface;

    /**
     * @param string $nickname
     * @return UserInterface
     */
    public function findByNickname(string $nickname): ?UserInterface;

    /**
     * @param UserInterface $user
     * @return UserInterface
     */
    public function create(UserInterface $user): UserInterface;
}
