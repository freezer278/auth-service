<?php


namespace App\Models;


interface TokenInterface
{
    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @return string
     */
    public function getExpiresAt(): string;

    /**
     * @return array
     */
    public function toArray(): array;
}