<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    /**
     * Serviço para operações relacionadas a usuários.
     *
     * @var UserService
     */
    protected UserService $userService;

    /**
     * Construtor do UserController.
     *
     * @param UserService $userService Serviço de usuários.
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Lista todos os usuários do sistema.
     *
     * Este método recupera todos os usuários. A autorização é verificada
     * para garantir que o usuário autenticado tem permissão para visualizar
     * a lista completa de usuários (ex: apenas administradores).
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection Coleção de recursos de usuário.
     */
    public function index()
    {
        Gate::authorize('viewAny', User::class);
        $users = $this->userService->getAllUsers();
        return UserResource::collection($users);
    }

    /**
     * Armazena um novo usuário no sistema.
     *
     * Este método cria um novo usuário. A validação é feita via StoreUserRequest.
     * Esta rota é geralmente pública para permitir o registro de novos usuários.
     *
     * @param StoreUserRequest $request Requisição de armazenamento de usuário.
     * @return UserResource Recurso do usuário recém-criado.
     */
    public function store(StoreUserRequest $request): UserResource
    {
        $user = $this->userService->createUser($request->validated());
        return new UserResource($user);
    }

    /**
     * Exibe um usuário específico.
     *
     * Este método recupera e exibe um usuário específico. A autorização é verificada
     * para garantir que o usuário autenticado tem permissão para visualizar o usuário alvo.
     *
     * @param User $user O modelo do usuário (resolvido via Route Model Binding).
     * @return UserResource Recurso do usuário.
     */
    public function show(User $user)
    {
        Gate::authorize('view', $user);

        return new UserResource($user);
    }

    /**
     * Atualiza um usuário específico.
     *
     * Este método atualiza um usuário existente. A validação é feita via UpdateUserRequest
     * e a autorização garante que o usuário autenticado tem permissão para modificar o usuário alvo.
     *
     * @param UpdateUserRequest $request Requisição de atualização de usuário.
     * @param User $user O modelo do usuário (resolvido via Route Model Binding).
     * @return UserResource Recurso do usuário atualizado.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        Gate::authorize('update', $user);

        $updatedUser = $this->userService->updateUser($user->id, $request->validated());

        return new UserResource($updatedUser);
    }

    /**
     * Remove um usuário específico.
     *
     * Este método exclui um usuário existente. A autorização garante que o usuário autenticado
     * tem permissão para remover o usuário alvo.
     *
     * @param User $user O modelo do usuário (resolvido via Route Model Binding).
     * @return JsonResponse Resposta JSON vazia com status 204.
     */
    public function destroy(User $user): JsonResponse
    {
        Gate::authorize('delete', $user);

        $this->userService->deleteUser($user->id);

        // Retorna 204 No Content, sem corpo, conforme especificação HTTP.
        return response()->noContent();
    }
}