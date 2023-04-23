<?php

namespace App\Repositories;

use App\Contracts\UrlRepositoryInterface;
use App\Models\Url;

class UrlRepository implements UrlRepositoryInterface
{
    public function __construct(
        private Url $model,
    ) {}

    public function all()
    {
        return $this->model->all();
    }

    public function paginated($perPage = 10)
    {
        return $this->model->paginate($perPage);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
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
}
