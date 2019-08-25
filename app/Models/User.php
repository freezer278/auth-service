<?php


namespace App\Models;


use InvalidArgumentException;

class User implements UserInterface
{
    /**
     * @var array
     */
    private $data;

    /**
     * User constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->data['id'];
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->data['firstname'];
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->data['lastname'];
    }

    /**
     * @return string
     */
    public function getNickname(): string
    {
        return $this->data['nickname'];
    }

    /**
     * @return int
     */
    public function getAge(): int
    {
        return $this->data['age'];
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->data['password'];
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->data;
    }
}
