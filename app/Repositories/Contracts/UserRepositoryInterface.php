<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface UserRepositoryInterface
{
    /**
     * Get all users.
     *
     * @return Collection<int, Model>
     */
    public function getAll(): Collection;

    /**
     * Find a user by ID.
     *
     * @param int $id
     * @return Model|null
     */
    public function findById(int $id): ?Model;

    /**
     * Create a new user.
     *
     * @param array $data
     * @return Model
     */
    public function create(array $data): Model;

    /**
     * Update an existing user.
     *
     * @param int $id
     * @param array $data
     * @return Model|null
     */
    public function update(int $id, array $data): ?Model;

    /**
     * Delete a user.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;
}