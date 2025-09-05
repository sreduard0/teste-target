<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    /**
     * @var UserService
     */
    protected UserService $userService;

    /**
     * UserController constructor.
     *
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        Gate::authorize('viewAny', \App\Models\User::class);
        $users = $this->userService->getAllUsers();
        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreUserRequest $request
     * @return UserResource
     */
    public function store(StoreUserRequest $request): UserResource
    {
        $user = $this->userService->createUser($request->validated());
        return new UserResource($user);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return UserResource|JsonResponse
     */
    public function show(int $id)
    {
        $user = $this->userService->findUserById($id);

        if (! $user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        Gate::authorize('view', $user);

        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateUserRequest $request
     * @param int $id
     * @return UserResource|JsonResponse
     */
    public function update(UpdateUserRequest $request, int $id)
    {
        $user = $this->userService->findUserById($id);

        if (! $user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        Gate::authorize('update', $user);

        $updatedUser = $this->userService->updateUser($id, $request->validated());

        return new UserResource($updatedUser);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $user = $this->userService->findUserById($id);

        if (! $user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        Gate::authorize('delete', $user);

        $this->userService->deleteUser($id);

        return response()->json(['message' => 'User deleted successfully'], 204);
    }
}