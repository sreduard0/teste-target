<?php

namespace App\Repositories\Contracts;

interface AddressRepositoryInterface
{
    /**
     * Get all addresses for a specific user.
     *
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getByUser(int $userId);

    /**
     * Find an address by ID.
     *
     * @param int $id
     * @return \App\Models\Address|null
     */
    public function findById(int $id);

    /**
     * Create a new address.
     *
     * @param array $data
     * @return \App\Models\Address
     */
    public function create(array $data);

    /**
     * Update an existing address.
     *
     * @param int $id
     * @param array $data
     * @return \App\Models\Address|null
     */
    public function update(int $id, array $data);

    /**
     * Delete an address by ID.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id);
}
