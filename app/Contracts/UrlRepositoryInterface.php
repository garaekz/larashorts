<?php

namespace App\Contracts;

interface UrlRepositoryInterface extends BaseModelRepositoryInterface
{
    public function findOneByCodeForUsers(string $code);
    public function findOneByUrlableAndOriginalUrl($urlable, string $originalUrl);
    public function findByUrlablePaginated($urlable, $perPage = 10);
}
