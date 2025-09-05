<?php

namespace App\Repositories\Eloquent;

use App\Models\Address;
use App\Repositories\Contracts\AddressRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class AddressRepository implements AddressRepositoryInterface
{
    /**
     * Get all addresses for a specific user.
     *
     * @param int $userId
     * @return Collection<int, Model>
     */
    public function getByUser(int $userId): Collection
    {
        return Address::where('user_id', $userId)->get();
    }

    /**
     * Find an address by ID.
     *
     * @param int $id
     * @return Model|null
     */
    public function findById(int $id): ?Model
    {
        return Address::find($id);
    }

    /**
     * Create a new address.
     *
     * @param array $data
     * @return Model
     */
    public function create(array $data): Model
    {
        return Address::create($data);
    }

    /**
     * Update an existing address.
     *
     * @param int $id
     * @param array $data
     * @return Model|null
     */
    public function update(int $id, array $data): ?Model
    {
        $address = Address::find($id);
        if ($address) {
            $address->update($data);
        }
        return $address;
    }

    /**
     * Delete an address.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $address = Address::find($id);
        if ($address) {
            return $address->delete();
        }
        return false;
    }
}