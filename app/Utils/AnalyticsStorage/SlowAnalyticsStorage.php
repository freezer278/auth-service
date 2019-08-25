<?php


namespace App\Utils\AnalyticsStorage;

use App\Utils\Jwt\AuthUserResolverInterface;
use App\Utils\NotRegisteredUsers\NotRegisteredUserIdGeneratorInterface;
use Carbon\Carbon;
use SocialTech\StorageInterface;

class SlowAnalyticsStorage implements AnalyticsStorage
{
    /**
     * @var StorageInterface
     */
    private $storage;
    /**
     * @var AuthUserResolverInterface
     */
    private $authUserResolver;
    /**
     * @var NotRegisteredUserIdGeneratorInterface
     */
    private $notRegisteredUserIdGenerator;

    /**
     * SlowAnalyticsStorage constructor.
     * @param StorageInterface $storage
     * @param AuthUserResolverInterface $authUserResolver
     * @param NotRegisteredUserIdGeneratorInterface $notRegisteredUserIdGenerator
     */
    public function __construct(
        StorageInterface $storage,
        AuthUserResolverInterface $authUserResolver,
        NotRegisteredUserIdGeneratorInterface $notRegisteredUserIdGenerator
    ) {
        $this->storage = $storage;
        $this->authUserResolver = $authUserResolver;
        $this->notRegisteredUserIdGenerator = $notRegisteredUserIdGenerator;
    }

    /**
     * @param array $params
     * @return array
     */
    public function create(array $params): array
    {
        $params['id'] = $this->getNewItemId();
        $params['id_user'] = $params['id_user'] ?? $this->getCurrentUserId() ?? $this->getNotAuthenticatedUserId();
        $params['date_created'] = Carbon::now()->toDateTimeString();

        dispatch(new SaveToAnalyticsStorageJob(
            $this->storage,
            storage_path('analytics/' . $params['id'] . '.json'),
            json_encode($params)
        ));
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
            return json_decode($this->storage->load(storage_path('analytics/meta.json')), true);
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

        return $meta;
    }

    /**
     * @param array $meta
     * @return void
     */
    private function saveMeta(array $meta): void
    {
        dispatch(new SaveToAnalyticsStorageJob(
            $this->storage,
            storage_path('analytics/meta.json'),
            json_encode($meta)
        ));
    }

    /**
     * @return int
     */
    private function getCurrentUserId(): ?int
    {
        return $this->authUserResolver->getId();
    }

    /**
     * @return int
     */
    private function getNotAuthenticatedUserId(): int
    {
        return $this->notRegisteredUserIdGenerator->getUniqueId();
    }
}
