<?php

namespace App\Services;

use App\Repositories\Contracts\AddressRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class AddressService
{
    /**
     * @var AddressRepositoryInterface
     */
    protected AddressRepositoryInterface $addressRepository;

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
     * @return Collection<int, Model>
     */
    public function getAddressesByUser(int $userId): Collection
    {
        return $this->addressRepository->getByUser($userId);
    }

    /**
     * Find an address by ID.
     *
     * @param int $id
     * @return Model|null
     */
    public function findAddressById(int $id): ?Model
    {
        return $this->addressRepository->findById($id);
    }

    /**
     * Create a new address.
     *
     * @param array $data
     * @return Model
     */
    public function createAddress(array $data): Model
    {
        return $this->addressRepository->create($data);
    }

    /**
     * Update an existing address.
     *
     * @param int $id
     * @param array $data
     * @return Model|null
     */
    public function updateAddress(int $id, array $data): ?Model
    {
        return $this->addressRepository->update($id, $data);
    }

    /**
     * Delete an address.
     *
     * @param int $id
     * @return bool
     */
    public function deleteAddress(int $id): bool
    {
        return $this->addressRepository->delete($id);
    }
}