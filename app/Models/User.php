<?php


namespace App\Models;


use InvalidArgumentException;

class User implements UserInterface
{
    /**
     * @var int|null
     */
    private $id;
    /**
     * @var string
     */
    private $firstname;
    /**
     * @var string
     */
    private $lastname;
    /**
     * @var string
     */
    private $nickname;
    /**
     * @var int
     */
    private $age;
    /**
     * @var string
     */
    private $password;

    /**
     * User constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? null;
        $this->firstname = $data['firstname'] ?? '';
        $this->lastname = $data['lastname'] ?? '';
        $this->nickname = $data['nickname'];
        $this->age = $data['age'] ?? null;
        $this->password = $data['password'] ?? null;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstname;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastname;
    }

    /**
     * @return string
     */
    public function getNickname(): string
    {
        return $this->nickname;
    }

    /**
     * @return int
     */
    public function getAge(): int
    {
        return $this->age;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }
}
