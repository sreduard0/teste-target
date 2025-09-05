<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAddressRequest;
use App\Http\Requests\UpdateAddressRequest;
use App\Http\Resources\AddressResource;
use App\Models\Address;
use App\Models\User;
use App\Services\AddressService;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class AddressController extends Controller
{
    /**
     * Serviço para operações relacionadas a endereços.
     *
     * @var AddressService
     */
    protected AddressService $addressService;

    /**
     * Serviço para operações relacionadas a usuários.
     *
     * @var UserService
     */
    protected UserService $userService;

    /**
     * Construtor do AddressController.
     *
     * @param AddressService $addressService Serviço de endereços.
     * @param UserService $userService Serviço de usuários.
     */
    public function __construct(AddressService $addressService, UserService $userService)
    {
        $this->addressService = $addressService;
        $this->userService = $userService;
    }

    /**
     * Lista todos os endereços de um usuário específico.
     *
     * Este método recupera todos os endereços associados a um determinado usuário.
     * A autorização é verificada para garantir que o usuário autenticado tem permissão
     * para visualizar os endereços do usuário alvo.
     *
     * @param User $user O modelo do usuário (resolvido via Route Model Binding).
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection Coleção de recursos de endereço.
     */
    public function index(User $user)
    {
        // Autoriza a visualização dos endereços do usuário. A política de usuário
        // deve definir quem pode ver os endereços de quem (ex: admin ou o próprio usuário).
        Gate::authorize('view', $user);

        $addresses = $this->addressService->getAddressesByUser($user->id);
        return AddressResource::collection($addresses);
    }

    /**
     * Armazena um novo endereço para um usuário específico.
     *
     * Este método cria um novo endereço e o associa ao usuário fornecido.
     * A validação é feita via StoreAddressRequest e a autorização garante
     * que o usuário autenticado pode adicionar endereços para o usuário alvo.
     *
     * @param StoreAddressRequest $request Requisição de armazenamento de endereço.
     * @param User $user O modelo do usuário (resolvido via Route Model Binding).
     * @return AddressResource Recurso do endereço recém-criado.
     */
    public function store(StoreAddressRequest $request, User $user)
    {
        // Autoriza a atualização do usuário (que inclui adicionar endereços).
        Gate::authorize('update', $user);

        $data = $request->validated();
        $data['user_id'] = $user->id; // Garante que o user_id correto é associado
        $address = $this->addressService->createAddress($data);

        return new AddressResource($address);
    }

    /**
     * Exibe um endereço específico de um usuário.
     *
     * Este método recupera e exibe um endereço específico, garantindo que ele
     * pertença ao usuário correto e que o usuário autenticado tenha permissão para visualizá-lo.
     *
     * @param User $user O modelo do usuário (resolvido via Route Model Binding).
     * @param Address $address O modelo do endereço (resolvido via Route Model Binding).
     * @return AddressResource Recurso do endereço.
     */
    public function show(User $user, Address $address)
    {
        // Autoriza a visualização do usuário (e implicitamente seus endereços).
        Gate::authorize('view', $user);

        // Garante que o endereço pertence ao usuário correto.
        // Esta verificação é crucial para evitar que um usuário acesse o endereço de outro
        // mesmo que a política de 'view' do usuário permita ver o usuário alvo.
        if ($address->user_id !== $user->id) {
            return response()->json(['message' => 'Endereço não encontrado para este usuário'], 404);
        }

        return new AddressResource($address);
    }

    /**
     * Atualiza um endereço específico de um usuário.
     *
     * Este método atualiza um endereço existente, utilizando validação via
     * UpdateAddressRequest e garantindo que o usuário autenticado tem permissão
     * para modificar o endereço do usuário alvo.
     *
     * @param UpdateAddressRequest $request Requisição de atualização de endereço.
     * @param User $user O modelo do usuário (resolvido via Route Model Binding).
     * @param Address $address O modelo do endereço (resolvido via Route Model Binding).
     * @return AddressResource Recurso do endereço atualizado.
     */
    public function update(UpdateAddressRequest $request, User $user, Address $address)
    {
        // Autoriza a atualização do usuário (que inclui modificar endereços).
        Gate::authorize('update', $user);

        // Garante que o endereço pertence ao usuário correto.
        if ($address->user_id !== $user->id) {
            return response()->json(['message' => 'Endereço não encontrado para este usuário'], 404);
        }

        $updatedAddress = $this->addressService->updateAddress($address->id, $request->validated());

        return new AddressResource($updatedAddress);
    }

    /**
     * Remove um endereço específico de um usuário.
     *
     * Este método exclui um endereço existente, garantindo que o usuário autenticado
     * tem permissão para remover endereços do usuário alvo.
     *
     * @param User $user O modelo do usuário (resolvido via Route Model Binding).
     * @param Address $address O modelo do endereço (resolvido via Route Model Binding).
     * @return \Illuminate\Http\Response Resposta vazia com status 204.
     */
    public function destroy(User $user, Address $address): Response | JsonResponse
    {
        // Autoriza a exclusão do usuário (que inclui remover endereços).
        Gate::authorize('delete', $user);

        // Garante que o endereço pertence ao usuário correto.
        if ($address->user_id !== $user->id) {
            return response()->json(['message' => 'Endereço não encontrado para este usuário'], 404);
        }

        $this->addressService->deleteAddress($address->id);

        // Retorna 204 No Content, sem corpo, conforme especificação HTTP.
        return response()->noContent();
    }
}
