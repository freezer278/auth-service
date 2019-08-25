<?php


namespace App\Models;


class Token implements TokenInterface
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
     * @return string
     */
    public function getId(): string
    {
       return $this->data['id'];
    }

    /**
     * @return string
     */
    public function getExpiresAt(): string
    {
        return $this->data['expires_at'];
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->data;
    }
}