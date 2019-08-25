<?php


namespace App\DataMappers\Token;


use App\Models\Token;
use App\Models\TokenInterface;
use App\Utils\Database\Database;

class TokenMapper implements TokenMapperInterface
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
        $this->database = $database->useTable('tokens');
    }

    /**
     * @param int $id
     * @return TokenInterface
     */
    public function findById(string $id): ?TokenInterface
    {
        $data = $this->database->findByField('id', $id);

        return $data ? $this->mapSingleToken($data) : null;
        // TODO: Implement findById() method.
    }

    /**
     * @param TokenInterface $token
     * @return TokenInterface
     */
    public function create(TokenInterface $token): TokenInterface
    {
        $this->database->create($token->toArray());

        return $token;
    }

    /**
     * @param array $data
     * @return TokenInterface
     */
    private function mapSingleToken(array $data): TokenInterface
    {
        return new Token($data);
    }
}
