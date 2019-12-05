<?php

namespace WSocket\Apps;

interface AppProvider
{
    /**  @return array[WSocket\AppProviders\App] */
    public function all(): array;

    public function findById($appId): ?App;

    public function findByKey(string $appKey): ?App;

    public function findBySecret(string $appSecret): ?App;
}
