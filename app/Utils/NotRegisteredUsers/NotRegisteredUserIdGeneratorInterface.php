<?php


namespace App\Utils\NotRegisteredUsers;


interface NotRegisteredUserIdGeneratorInterface
{
    /**
     * @return int
     */
    public function getUniqueId(): int;
}