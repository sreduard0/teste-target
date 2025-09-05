<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
    /**
     * Get all users.
     *
     * @return Collection<int, Model>
     */
    public function getAll(): Collection
    {
        return User::all();
    }

    /**
     * Find a user by ID.
     *
     * @param int $id
     * @return Model|null
     */
    public function findById(int $id): ?Model
    {
        return User::find($id);
    }

    /**
     * Create a new user.
     *
     * @param array $data
     * @return Model
     */
    public function create(array $data): Model
    {
        $data['password'] = Hash::make($data['password']);
        return User::create($data);
    }

    /**
     * Update an existing user.
     *
     * @param int $id
     * @param array $data
     * @return Model|null
     */
    public function update(int $id, array $data): ?Model
    {
        $user = User::find($id);
        if ($user) {
            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }
            $user->update($data);
        }
        return $user;
    }

    /**
     * Delete a user.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $user = User::find($id);
        if ($user) {
            return $user->delete();
        }
        return false;
    }
}