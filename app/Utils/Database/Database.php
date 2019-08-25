<?php


namespace App\Utils\Database;

interface Database
{
    /**
     * @param string $tableName
     * @return Database
     */
    public function useTable(string $tableName): self;

    /**
     * @param array $data
     * @return array
     */
    public function create(array $data): array;

    /**
     * @param string $field
     * @param $value
     * @return array|null
     */
    public function findByField(string $field, $value): ?array;
}
