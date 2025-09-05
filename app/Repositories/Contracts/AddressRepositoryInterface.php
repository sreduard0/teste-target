<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface AddressRepositoryInterface
{
    /**
     * Get all addresses for a specific user.
     *
     * @param int $userId
     * @return Collection<int, Model>
     */
    public function getByUser(int $userId): Collection;

    /**
     * Find an address by ID.
     *
     * @param int $id
     * @return Model|null
     */
    public function findById(int $id): ?Model;

    /**
     * Create a new address.
     *
     * @param array $data
     * @return Model
     */
    public function create(array $data): Model;

    /**
     * Update an existing address.
     *
     * @param int $id
     * @param array $data
     * @return Model|null
     */
    public function update(int $id, array $data): ?Model;

    /**
     * Delete an address.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;
}