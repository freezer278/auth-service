<?php


namespace App\Utils\Database;


trait JsonDatabaseTrait
{
    /**
     * @var string
     */
    private $table;

    /**
     * @return int
     */
    private function getNewItemId(): int
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
     * @param string $fileName
     * @return string
     */
    protected function getPath(string $fileName): string
    {
        $folderPath = storage_path($this->table);

        if (!file_exists($folderPath)) {
            mkdir($folderPath);
        }

        return $folderPath . '/' . $fileName;
    }
}