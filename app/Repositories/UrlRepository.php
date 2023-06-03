<?php

namespace App\Repositories;

use App\Contracts\UrlRepositoryInterface;
use App\Models\Url;
use App\Models\User;

class UrlRepository implements UrlRepositoryInterface
{
    public function __construct(
        private Url $model,
    ) {
    }

    public function all()
    {
        return $this->model->all();
    }

    public function paginated($perPage = 10)
    {
        return $this->model->paginate($perPage);
    }

    public function create($urlable, array $data)
    {
        return $urlable->urls()->create($data);
    }

    public function update(array $data, $model)
    {
        return $model->update($data);
    }

    public function delete($model)
    {
        return $model->delete();
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function findBy(array $whereList, array $withList = [], array $columns = ['*'])
    {
        if (isset($whereList[0]) && !is_array($whereList[0])) {
            $whereList = [$whereList];
        }

        return $this->model->with($withList)->where($whereList)->get($columns);
    }

    public function findOneBy(array $whereList, array $withList = [], array $columns = ['*'])
    {
        if (isset($whereList[0]) && !is_array($whereList[0])) {
            $whereList = [$whereList];
        }

        return $this->model->with($withList)->where($whereList)->first($columns);
    }

    public function findOneByCodeForUsers(string $code)
    {
        return $this->model->where([
            ['code', $code],
            ['urlable_type', User::class],
        ])->first();
    }

    public function findOneByUrlableAndOriginalUrl($urlable, string $originalUrl)
    {
        return $urlable->urls()->where('original_url', $originalUrl)->first();
    }

    public function findByUrlablePaginated($urlable, $perPage = 10, $search = null)
    {
        if ($search) {
            return $urlable->urls()->where(function ($query) use ($search) {
                $query->where('original_url', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            })->orderBy('created_at', 'desc')->paginate($perPage);
        }

        return $urlable->urls()->orderBy('created_at', 'desc')->paginate($perPage);
    }
}
