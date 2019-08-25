<?php

namespace App\Utils\AnalyticsStorage;

use App\Jobs\Job;
use SocialTech\StorageInterface;

class SaveToAnalyticsStorageJob extends Job
{
    /**
     * @var StorageInterface
     */
    private $storage;
    /**
     * @var string
     */
    private $path;
    /**
     * @var string
     */
    private $content;

    /**
     * Create a new job instance.
     *
     * @param StorageInterface $storage
     * @param string $path
     * @param string $content
     */
    public function __construct(StorageInterface $storage, string $path, string $content)
    {
        $this->storage = $storage;
        $this->path = $path;
        $this->content = $content;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->storage->store($this->path, $this->content);
    }
}
