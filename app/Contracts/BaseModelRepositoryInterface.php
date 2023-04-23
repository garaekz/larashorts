<?php

namespace App\Contracts;

interface BaseModelRepositoryInterface
{
    public function all();

    public function paginated($perPage = 10);

    public function create(array $data);

    public function update(array $data, $id);

    public function delete($id);

    public function find($id);

    public function findBy(array $whereList, array $withList = [], array $columns = ['*']);
}
