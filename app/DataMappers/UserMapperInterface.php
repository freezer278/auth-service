<?php


namespace App\DataMappers;

use App\Exceptions\ModelNotFoundException;
use App\Models\UserInterface;

interface UserMapperInterface
{
    /**
     * @param int $id
     * @return UserInterface
     * @throws ModelNotFoundException
     */
    public function findById(int $id): UserInterface;

    /**
     * @param string $nickname
     * @return UserInterface
     * @throws ModelNotFoundException
     */
    public function findByNickname(string $nickname): UserInterface;

    /**
     * @param UserInterface $user
     * @return UserInterface
     */
    public function create(UserInterface $user): UserInterface;
}
