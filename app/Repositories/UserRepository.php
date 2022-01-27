<?php

namespace App\Repositories;

use App\Interfaces\Repositories\BaseRepositoryInterface;
use App\Models\User;

class UserRepository implements BaseRepositoryInterface
{
    /**
     * @param array $data
     * @return User
     */
    public function create(array $data): User
    {
        return User::create($data);
    }

    /**
     * @param int $id
     * @return User
     */
    public function getById(int $id): User
    {
        return User::findOrFail($id);
    }

    /**
     * @param int $id
     * @param array $data
     * @return int
     */
    public function updateById(int $id, array $data): int
    {
        return User::whereId($id)->update($data);
    }
}
