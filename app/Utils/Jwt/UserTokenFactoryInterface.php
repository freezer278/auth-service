<?php


namespace App\Utils\Jwt;


use App\Models\UserInterface;
use Lcobucci\JWT\Token;

interface UserTokenFactoryInterface
{
    /**
     * @param UserInterface $user
     * @return Token
     */
    public function create(UserInterface $user);

    /**
     * @return int
     */
    public function getExpiresIn(): int;

    /**
     * @return string
     */
    public function getSignKey(): string;
}
