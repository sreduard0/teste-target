<?php

namespace App\Services;

use App\Repositories\Contracts\AddressRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Address;

class AddressService
{
    /**
     * @var AddressRepositoryInterface
     */
    protected $addressRepository;

    /**
     * AddressService constructor.
     *
     * @param AddressRepositoryInterface $addressRepository
     */
    public function __construct(AddressRepositoryInterface $addressRepository)
    {
        $this->addressRepository = $addressRepository;
    }

    /**
     * Get all addresses for a specific user.
     *
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAddressesByUser(int $userId): Collection
    {
        return $this->addressRepository->getByUser($userId);
    }

    /**
     * Find an address by ID.
     *
     * @param int $id
     * @return \App\Models\Address|null
     */
    public function findAddressById(int $id): ?Address
    {
        return $this->addressRepository->findById($id);
    }

    /**
     * Create a new address.
     *
     * @param array $data
     * @return \App\Models\Address
     */
    public function createAddress(array $data): Address
    {
        return $this->addressRepository->create($data);
    }

    /**
     * Update an existing address.
     *
     * @param int $id
     * @param array $data
     * @return \App\Models\Address|null
     */
    public function updateAddress(int $id, array $data): ?Address
    {
        return $this->addressRepository->update($id, $data);
    }

    /**
     * Delete an address by ID.
     *
     * @param int $id
     * @return bool
     */
    public function deleteAddress(int $id): bool
    {
        return $this->addressRepository->delete($id);
    }
}
