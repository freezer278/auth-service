<?php


namespace App\DataMappers\User;


use App\Exceptions\ModelNotFoundException;
use App\Models\User;
use App\Models\UserInterface;
use App\Utils\Database\Database;

class UserMapper implements UserMapperInterface
{
    /**
     * @var Database
     */
    private $database;

    /**
     * UserMapper constructor.
     * @param Database $database]
     */
    public function __construct(Database $database)
    {
        $this->database = $database->useTable('users');
    }

    /**
     * @param int $id
     * @return UserInterface
     * @throws ModelNotFoundException
     */
    public function findById(int $id): UserInterface
    {
        $result = $this->database->findByField('id', $id);

        if (!$result) {
            throw new ModelNotFoundException();
        }

        return $this->mapSingleEntity($result);
    }

    /**
     * @param string $nickname
     * @return UserInterface
     * @throws ModelNotFoundException
     */
    public function findByNickname(string $nickname): UserInterface
    {
        $result = $this->database->findByField('nickname', $nickname);

        if (!$result) {
            throw new ModelNotFoundException();
        }

        return $this->mapSingleEntity($result);
    }

    /**
     * @param UserInterface $user
     * @return UserInterface
     */
    public function create(UserInterface $user): UserInterface
    {
        return $this->mapSingleEntity($this->database->create($user->toArray()));
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
