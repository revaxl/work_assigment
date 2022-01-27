<?php

namespace App\Interfaces\Repositories;

interface BaseRepositoryInterface
{
    public function getById(int $id);
    public function create(array $data);
    public function updateById(int $id, array $data);
}
