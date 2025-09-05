<?php

namespace App\Services;

use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class UserService
{
    /**
     * @var UserRepositoryInterface
     */
    protected UserRepositoryInterface $userRepository;

    /**
     * UserService constructor.
     *
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Get all users.
     *
     * @return Collection<int, Model>
     */
    public function getAllUsers(): Collection
    {
        return $this->userRepository->getAll();
    }

    /**
     * Find a user by ID.
     *
     * @param int $id
     * @return Model|null
     */
    public function findUserById(int $id): ?Model
    {
        return $this->userRepository->findById($id);
    }

    /**
     * Create a new user.
     *
     * @param array $data
     * @return Model
     */
    public function createUser(array $data): Model
    {
        return $this->userRepository->create($data);
    }

    /**
     * Update an existing user.
     *
     * @param int $id
     * @param array $data
     * @return Model|null
     */
    public function updateUser(int $id, array $data): ?Model
    {
        return $this->userRepository->update($id, $data);
    }

    /**
     * Delete a user.
     *
     * @param int $id
     * @return bool
     */
    public function deleteUser(int $id): bool
    {
        return $this->userRepository->delete($id);
    }
}