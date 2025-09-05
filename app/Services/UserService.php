<?php

namespace App\Services;

use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class UserService
{
    /**
     * Repositório para operações de persistência de usuários.
     *
     * @var UserRepositoryInterface
     */
    protected UserRepositoryInterface $userRepository;

    /**
     * Construtor do UserService.
     *
     * @param UserRepositoryInterface $userRepository Repositório de usuários.
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Recupera todos os usuários do sistema.
     *
     * Este método é responsável por buscar todos os registros de usuários
     * através do repositório, sem aplicar filtros ou paginação adicionais.
     * É útil para listagens gerais ou para operações administrativas.
     *
     * @return Collection<int, Model> Uma coleção de modelos de usuário.
     */
    public function getAllUsers(): Collection
    {
        return $this->userRepository->getAll();
    }

    /**
     * Encontra um usuário pelo ID.
     *
     * @param int $id ID do usuário.
     * @return Model|null O modelo do usuário ou null se não encontrado.
     */
    public function findUserById(int $id): ?Model
    {
        return $this->userRepository->findById($id);
    }

    /**
     * Cria um novo usuário no sistema.
     *
     * Este método encapsula a lógica de criação de um usuário, delegando
     * a persistência ao repositório. Futuras regras de negócio, como
     * envio de e-mail de boas-vindas ou integração com outros serviços,
     * seriam adicionadas aqui.
     *
     * @param array $data Dados do usuário a serem criados.
     * @return Model O modelo do usuário recém-criado.
     */
    public function createUser(array $data): Model
    {
        // Exemplo de lógica de negócio: enviar e-mail de boas-vindas
        // Mail::to($data['email'])->send(new WelcomeEmail($data['name']));

        // Exemplo de lógica de negócio: registrar evento de criação
        // event(new UserCreated($user));

        return $this->userRepository->create($data);
    }

    /**
     * Atualiza um usuário existente.
     *
     * @param int $id ID do usuário a ser atualizado.
     * @param array $data Dados para atualização.
     * @return Model|null O modelo do usuário atualizado ou null se não encontrado.
     */
    public function updateUser(int $id, array $data): ?Model
    {
        // Exemplo de lógica de negócio: auditar alterações
        // Log::info("User {$id} updated.", $data);

        return $this->userRepository->update($id, $data);
    }

    /**
     * Exclui um usuário.
     *
     * @param int $id ID do usuário a ser excluído.
     * @return bool True se o usuário foi excluído com sucesso, false caso contrário.
     */
    public function deleteUser(int $id): bool
    {
        // Exemplo de lógica de negócio: desativar contas relacionadas
        // $this->userRepository->findById($id)->relatedAccounts()->deactivate();

        return $this->userRepository->delete($id);
    }
}