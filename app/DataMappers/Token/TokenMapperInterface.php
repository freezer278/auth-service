<?php

namespace App\DataMappers\Token;

use App\Models\TokenInterface;

interface TokenMapperInterface
{
    /**
     * @param string $id
     * @return TokenInterface
     */
    public function findById(string $id): ?TokenInterface;

    /**
     * @param TokenInterface $token
     * @return TokenInterface
     */
    public function create(TokenInterface $token): ?TokenInterface;
}
