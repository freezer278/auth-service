<?php


namespace App\Utils\Jwt;


use App\Models\UserInterface;

interface AuthUserResolverInterface
{
    /**
     * @return int
     */
    public function getId(): ?int;

    /**
     * @return UserInterface
     */
    public function getUser(): ?UserInterface;
}
