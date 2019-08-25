<?php


namespace App\Utils\Database;


class JsonDatabase implements Database
{
    /**
     * @var string
     */
    private $table;

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

        $data['id'] = $this->getNewId();

        $dbData = $this->getDbContents();
        $dbData[$data['id']] = $data;
        file_put_contents($this->getPath('db.json'), json_encode($dbData));

        $this->setNewItemId($data['id']);

        return $data;
    }

    /**
     * @return int
     */
    private function getNewId(): int
    {
        $meta = $this->getMeta();
        return (int)$meta['next_id'];
    }

    /**
     * @param int $lastSavedId
     */
    private function setNewItemId(int $lastSavedId): void
    {
        $meta = $this->getMeta();
        $meta['next_id'] = $lastSavedId + 1;

        $this->saveMeta($meta);
    }

    /**
     * @return array
     */
    private function getMeta(): array
    {
        try {
            return json_decode(file_get_contents($this->getPath('meta.json')), true);
        } catch (\Exception $exception) {
            return $this->generateInitialMeta();
        }
    }

    /**
     * @return array
     */
    private function generateInitialMeta(): array
    {
        $meta = [
            'next_id' => 1,
        ];

        return $meta;
    }

    /**
     * @param array $meta
     * @return void
     */
    private function saveMeta(array $meta): void
    {
        file_put_contents($this->getPath('meta.json'), json_encode($meta));
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

    /**
     * @param string $fileName
     * @return string
     */
    private function getPath(string $fileName): string
    {
        $folderPath = storage_path($this->table);

        if (!file_exists($folderPath)) {
            mkdir($folderPath);
        }

        return $folderPath . '/' . $fileName;
    }
}
