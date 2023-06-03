<?php

namespace App\Services;

use App\Models\Application;
use App\Models\Url;
use App\Models\User;
use App\Repositories\UrlRepository;
use Exception;
use Illuminate\Support\Facades\Cache;

class UrlService
{
    public function __construct(
        private UrlRepository $repository,
    ) {
    }

    public function getPaginatedByUrlable($urlable, $perPage = 10, $search = null)
    {
        return $this->repository->findByUrlablePaginated($urlable, $perPage, $search);
    }

    public function createByUser(User $user, array $data): Url
    {
        $data['code'] = $this->generateUrlCode($user);
        return $this->repository->create($user, $data);
    }

    public function update(Url $url, array $data)
    {
        return $this->repository->update($data, $url);
    }

    public function delete(Url $url)
    {
        return $this->repository->delete($url);
    }

    public function shortExists($urlable, string $originalUrl): bool
    {
        return $this->repository->findOneByUrlableAndOriginalUrl($urlable, $originalUrl) ? true : false;
    }

    public function fetchByCode(string $code, $base_url)
    {
        return $this->repository->findOneBy(['code' => $code, 'base_url' => $base_url]);
    }

    public function generateUrlCode(User|Application $urlable): string
    {
        $maxCodeLength = $urlable->min_code_length;
        $maxAllowedCodeLength = config('app.code_generation.max_length');
        $maxAttempts = config('app.code_generation.max_attempts');

        for ($attempt = 0; $attempt < $maxAttempts; $attempt++) {
            $code = $this->generateUniqueCode($maxCodeLength);

            if ($urlable instanceof User) {
                if (!$this->repository->findOneByCodeForUsers($code)) {
                    return $code;
                }
            } elseif ($urlable instanceof Application) {
                if (!$this->repository->findOneByUrlableAndOriginalUrl($code, $urlable)) {
                    return $code;
                }
            }

            $maxCodeLength++;
        }

        $urlable->min_code_length = $maxCodeLength;
        $urlable->save();

        if ($maxCodeLength > $maxAllowedCodeLength) {
            throw new Exception('Unable to generate unique code.');
        }

        return $this->generateUrlCode($urlable);
    }


    public function generateUniqueCode($length): string
    {
        $runes = '123456789abcdefghijkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ';
        $base = strlen($runes);

        $code = '';
        $random = random_bytes($length);

        for ($i = 0; $i < $length; $i++) {
            $index = ord($random[$i]) % $base;
            $code .= $runes[$index];
        }

        return $code;
    }
}
