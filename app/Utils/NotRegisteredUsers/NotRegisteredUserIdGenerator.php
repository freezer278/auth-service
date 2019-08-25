<?php


namespace App\Utils\NotRegisteredUsers;

/**
 * Class NotRegisteredUserIdGenerator
 * @package App\Utils
 */
class NotRegisteredUserIdGenerator implements NotRegisteredUserIdGeneratorInterface
{
    /**
     * @return int
     */
    public function getUniqueId(): int
    {
        $contents = $this->getDataFromStorage();
        $newId = $contents['new_id']--;

        $this->saveDataToStorage($contents);

        return $newId;
    }

    /**
     * @return array
     */
    private function getDataFromStorage(): array
    {
        $contents = json_decode(file_get_contents(storage_path('users/not_registered.json')), true);
        if (!$contents) {
            $contents = [
                'new_id' => -1,
            ];
        }

        return $contents;
    }

    /**
     * @param array $data
     */
    private function saveDataToStorage(array $data): void
    {
        file_put_contents(storage_path('users/not_registered.json'), json_encode($data));
    }
}