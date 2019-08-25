<?php


namespace App\Utils\Jwt;


use App\Models\UserInterface;

interface UserTokenFactoryInterface
{
    /**
     * @param UserInterface $user
     * @return mixed
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