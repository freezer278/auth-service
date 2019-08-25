<?php


namespace App\DataMappers\User;


use App\Exceptions\ModelNotFoundException;
use App\Models\User;
use App\Models\UserInterface;

class UserMapper implements UserMapperInterface
{

    /**
     * @param int $id
     * @return UserInterface
     * @throws ModelNotFoundException
     */
    public function findById(int $id): UserInterface
    {
        // TODO: Implement findById() method.
    }

    /**
     * @param string $nickname
     * @return UserInterface
     * @throws ModelNotFoundException
     */
    public function findByNickname(string $nickname): UserInterface
    {
        // TODO: Implement findByNickname() method.
    }

    /**
     * @param UserInterface $user
     * @return UserInterface
     */
    public function create(UserInterface $user): UserInterface
    {
        // TODO: Implement create() method.
    }

    /**
     * @param array $data
     * @return UserInterface
     */
    private function mapSingleEntity(array $data): UserInterface
    {
        return new User($data);
    }
}
