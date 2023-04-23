<?php

namespace App\Services;

use App\Models\CodeGeneratorSetting;
use App\Models\Url;
use App\Repositories\UrlRepository;
use Exception;
use Illuminate\Support\Facades\Cache;

class UrlService
{
    public function __construct(
        private UrlRepository $repository,
    ) {}

    public function getPaginated($perPage = 10)
    {
        return $this->repository->paginated($perPage);
    }

    public function create(array $data)
    {
        $data['code'] = $this->generateUrlCode();
        return $this->repository->create($data);
    }

    public function update(Url $url, array $data)
    {
        return $this->repository->update($data, $url);
    }

    public function delete(Url $url)
    {
        return $this->repository->delete($url);
    }

    public function generateUrlCode($tries = 0): string
    {
        $config = Cache::rememberForever('code_generator_configuration', function () {
            // Should refactor this to add the correct repository
            return CodeGeneratorSetting::firstOrFail();
        });

        for ($i = 0; $i < $config->max_attempts; $i++) {
            $max_length = $config->max_length;

            $code = $this->generateUniqueCode($max_length);

            if (!$this->repository->findOneBy(['code' => $code])) {
                return $code;
            }
        }

        $config->update(['max_length' => $config->max_length + 1]);
        Cache::put('code_generator_configuration', $config);

        if ($tries < $config->max_retries) {
            return $this->generateUrlCode($tries + 1);
        }

        throw new Exception('Unable to generate unique code.');
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
