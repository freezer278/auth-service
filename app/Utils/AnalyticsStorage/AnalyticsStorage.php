<?php


namespace App\Utils\AnalyticsStorage;


interface AnalyticsStorage
{
    /**
     * @param array $params
     * @return array
     */
    public function create(array $params): array ;
}
