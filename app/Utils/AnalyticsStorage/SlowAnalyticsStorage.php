<?php


namespace App\Utils\AnalyticsStorage;

use App\Utils\Database\JsonDatabaseTrait;
use App\Utils\Jwt\AuthUserResolverInterface;
use App\Utils\NotRegisteredUsers\NotRegisteredUserIdGeneratorInterface;
use Carbon\Carbon;
use SocialTech\StorageInterface;

class SlowAnalyticsStorage implements AnalyticsStorage
{
    use JsonDatabaseTrait;

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

        $this->table = 'analytics';
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
            $this->getPath($params['id'] . '.json'),
            json_encode($params)
        ));
        $this->setNewItemId($params['id']);

        return $params;
    }

    /**
     * @param array $meta
     * @return void
     */
    protected function saveMeta(array $meta): void
    {
        dispatch(new SaveToAnalyticsStorageJob(
            $this->storage,
            $this->getPath('meta.json'),
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
