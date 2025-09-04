<?php

namespace App\Repositories\Eloquent;

use App\Models\Address;
use App\Repositories\Contracts\AddressRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class AddressRepository implements AddressRepositoryInterface
{
    /**
     * Get all addresses for a specific user.
     *
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getByUser(int $userId): Collection
    {
        return Address::where('user_id', $userId)->get();
    }

    /**
     * Find an address by ID.
     *
     * @param int $id
     * @return \App\Models\Address|null
     */
    public function findById(int $id): ?Address
    {
        return Address::find($id);
    }

    /**
     * Create a new address.
     *
     * @param array $data
     * @return \App\Models\Address
     */
    public function create(array $data): Address
    {
        return Address::create($data);
    }

    /**
     * Update an existing address.
     *
     * @param int $id
     * @param array $data
     * @return \App\Models\Address|null
     */
    public function update(int $id, array $data): ?Address
    {
        $address = Address::find($id);
        if ($address) {
            $address->update($data);
        }
        return $address;
    }

    /**
     * Delete an address by ID.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return Address::destroy($id) > 0;
    }
}
