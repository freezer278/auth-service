<?php


namespace App\Utils\AnalyticsStorage;


use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use SocialTech\StorageInterface;

class SlowAnalyticsStorage implements AnalyticsStorage
{
    /**
     * @var StorageInterface
     */
    private $storage;

    /**
     * SlowAnalyticsStorage constructor.
     * @param StorageInterface $storage
     */
    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @param array $params
     * @return array
     */
    public function create(array $params): array
    {
        $params['id'] = $this->getNewItemId();
        $params['id_user'] = $params['id_user'] ?? Auth::id() ?? $this->getNotAuthenticatedUserId();
        $params['date_created'] = Carbon::now()->toDateTimeString();

        $this->storage->store(storage_path('analytics/' . $params['id'] . '.json'), json_encode($params));
        $this->setNewItemId($params['id']);

        return $params;
    }

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
            return json_decode($this->storage->load('analytics/meta.json'));
        } catch (\RuntimeException $exception) {
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

        $this->saveMeta($meta);

        return $meta;
    }

    /**
     * @param array $meta
     * @return void
     */
    private function saveMeta(array $meta): void
    {
        $this->storage->store('analytics/meta.json', json_encode($meta));
    }

    /**
     * @return int
     */
    private function getNotAuthenticatedUserId(): int
    {
        // todo: add here logic for getting unique user id
        return -1;
    }
}
