<?php

namespace App\Services;

use App\Repositories\Contracts\AddressRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class AddressService
{
    /**
     * Repositório para operações de persistência de endereços.
     *
     * @var AddressRepositoryInterface
     */
    protected AddressRepositoryInterface $addressRepository;

    /**
     * Construtor do AddressService.
     *
     * @param AddressRepositoryInterface $addressRepository Repositório de endereços.
     */
    public function __construct(AddressRepositoryInterface $addressRepository)
    {
        $this->addressRepository = $addressRepository;
    }

    /**
     * Recupera todos os endereços para um usuário específico.
     *
     * @param int $userId ID do usuário.
     * @return Collection<int, Model> Uma coleção de modelos de endereço.
     */
    public function getAddressesByUser(int $userId): Collection
    {
        return $this->addressRepository->getByUser($userId);
    }

    /**
     * Encontra um endereço pelo ID.
     *
     * @param int $id ID do endereço.
     * @return Model|null O modelo do endereço ou null se não encontrado.
     */
    public function findAddressById(int $id): ?Model
    {
        return $this->addressRepository->findById($id);
    }

    /**
     * Cria um novo endereço.
     *
     * Este método encapsula a lógica de criação de um endereço, delegando
     * a persistência ao repositório. Pode incluir lógica para geocodificação
     * ou validações adicionais antes da criação.
     *
     * @param array $data Dados do endereço a serem criados.
     * @return Model O modelo do endereço recém-criado.
     */
    public function createAddress(array $data): Model
    {
        // Exemplo de lógica de negócio: geocodificar o endereço
        // $data['latitude'] = Geocoder::getLatitude($data);
        // $data['longitude'] = Geocoder::getLongitude($data);

        return $this->addressRepository->create($data);
    }

    /**
     * Atualiza um endereço existente.
     *
     * @param int $id ID do endereço a ser atualizado.
     * @param array $data Dados para atualização.
     * @return Model|null O modelo do endereço atualizado ou null se não encontrado.
     */
    public function updateAddress(int $id, array $data): ?Model
    {
        // Exemplo de lógica de negócio: registrar histórico de alterações
        // AuditLog::create(['address_id' => $id, 'changes' => $data]);

        return $this->addressRepository->update($id, $data);
    }

    /**
     * Exclui um endereço.
     *
     * @param int $id ID do endereço a ser excluído.
     * @return bool True se o endereço foi excluído com sucesso, false caso contrário.
     */
    public function deleteAddress(int $id): bool
    {
        // Exemplo de lógica de negócio: verificar dependências antes de excluir
        // if ($this->addressRepository->findById($id)->hasActiveOrders()) { return false; }

        return $this->addressRepository->delete($id);
    }
}