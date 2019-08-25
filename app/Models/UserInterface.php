<?php


namespace App\Models;


interface UserInterface
{
    /**
     * @return int|null
     */
    public function getId(): ?int;

    /**
     * @return string
     */
    public function getFirstName(): string;

    /**
     * @return string
     */
    public function getLastName(): string;

    /**
     * @return string
     */
    public function getNickname(): string;

    /**
     * @return int
     */
    public function getAge(): int;

    /**
     * @return string
     */
    public function getPassword(): string;
}
