<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAddressRequest;
use App\Http\Resources\AddressResource;
use App\Services\AddressService;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AddressController extends Controller
{
    /**
     * @var AddressService
     */
    protected AddressService $addressService;

    /**
     * @var UserService
     */
    protected UserService $userService;

    /**
     * AddressController constructor.
     *
     * @param AddressService $addressService
     * @param UserService $userService
     */
    public function __construct(AddressService $addressService, UserService $userService)
    {
        $this->addressService = $addressService;
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param int $userId
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection|JsonResponse
     */
    public function index(int $userId)
    {
        $user = $this->userService->findUserById($userId);

        if (! $user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        Gate::authorize('view', $user);

        $addresses = $this->addressService->getAddressesByUser($userId);
        return AddressResource::collection($addresses);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreAddressRequest $request
     * @param int $userId
     * @return AddressResource|JsonResponse
     */
    public function store(StoreAddressRequest $request, int $userId)
    {
        $user = $this->userService->findUserById($userId);

        if (! $user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        Gate::authorize('update', $user);

        $data = $request->validated();
        $data['user_id'] = $userId;
        $address = $this->addressService->createAddress($data);

        return new AddressResource($address);
    }

    /**
     * Display the specified resource.
     *
     * @param int $userId
     * @param int $addressId
     * @return AddressResource|JsonResponse
     */
    public function show(int $userId, int $addressId)
    {
        $user = $this->userService->findUserById($userId);

        if (! $user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        Gate::authorize('view', $user);

        $address = $this->addressService->findAddressById($addressId);

        if (! $address || $address->user_id !== $userId) {
            return response()->json(['message' => 'Address not found for this user'], 404);
        }

        return new AddressResource($address);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $userId
     * @param int $addressId
     * @return AddressResource|JsonResponse
     */
    public function update(Request $request, int $userId, int $addressId)
    {
        $user = $this->userService->findUserById($userId);

        if (! $user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        Gate::authorize('update', $user);

        $address = $this->addressService->findAddressById($addressId);

        if (! $address || $address->user_id !== $userId) {
            return response()->json(['message' => 'Address not found for this user'], 404);
        }

        $updatedAddress = $this->addressService->updateAddress($addressId, $request->all());

        return new AddressResource($updatedAddress);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $userId
     * @param int $addressId
     * @return JsonResponse
     */
    public function destroy(int $userId, int $addressId): JsonResponse
    {
        $user = $this->userService->findUserById($userId);

        if (! $user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        Gate::authorize('delete', $user);

        $address = $this->addressService->findAddressById($addressId);

        if (! $address || $address->user_id !== $userId) {
            return response()->json(['message' => 'Address not found for this user'], 404);
        }

        $this->addressService->deleteAddress($addressId);

        return response()->json(['message' => 'Address deleted successfully'], 204);
    }
}