<?php


namespace App\Utils\Database;


class JsonDatabase implements Database
{
    use JsonDatabaseTrait;

    /**
     * @param string $tableName
     * @return Database
     */
    public function useTable(string $tableName): Database
    {
        $this->table = $tableName;
        return $this;
    }

    /**
     * @param array $data
     * @return array
     */
    public function create(array $data): array
    {
        // TODO: add exception when table not set

        $data['id'] = $this->getNewItemId();

        $dbData = $this->getDbContents();
        $dbData[$data['id']] = $data;
        file_put_contents($this->getPath('db.json'), json_encode($dbData));

        $this->setNewItemId($data['id']);

        return $data;
    }

    /**
     * @param string $field
     * @param $value
     * @return array|null
     */
    public function findByField(string $field, $value): ?array
    {
        // TODO: add exception when table not set

        if ($field === 'id') {
            return $this->searchById($value);
        }

        return $this->searchByNotIndexedField($field, $value);
    }

    /**
     * @param $value
     * @return array|null
     */
    private function searchById($value): ?array
    {
        // TODO: add exception when table not set

        $contents = $this->getDbContents();
        return $contents[$value] ?? null;
    }

    /**
     * @param string $field
     * @param $value
     * @return array|null
     */
    private function searchByNotIndexedField(string $field, $value): ?array
    {
        $contents = $this->getDbContents();

        foreach ($contents as $key => $content) {
            if ($content[$field] == $value) {
                return $content;
            }
        }

        return null;
    }

    /**
     * @return array
     */
    private function getDbContents(): array
    {
        try {
            $dbData = json_decode(file_get_contents($this->getPath('db.json')), true);
            if (!$dbData) {
                $dbData = [];
            }
        } catch (\Exception $exception) {
            $dbData = [];
            file_put_contents($this->getPath('db.json'), json_encode($dbData));
        }

        return $dbData;
    }
}
