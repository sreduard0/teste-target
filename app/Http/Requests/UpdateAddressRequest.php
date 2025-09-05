<?php

namespace App\Http\Requests;

use App\Rules\CepFormat;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // A autorização será adicionada aqui posteriormente
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $addressId = $this->route('address');

        return [
            'street' => ['sometimes', 'string', 'max:255'],
            'number' => ['sometimes', 'string', 'max:10'],
            'neighborhood' => ['sometimes', 'string', 'max:255'],
            'complement' => ['nullable', 'string', 'max:255'],
            <?php

namespace App\Http\Requests;

use App\Models\Address;
use App\Models\User;
use App\Rules\CepFormat;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateAddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // Obtém o usuário e o endereço alvo da rota (via Route Model Binding)
        $user = $this->route('user');
        $address = $this->route('address');

        // Se o usuário ou endereço alvo não for encontrado,
        // ou se não houver usuário autenticado, nega a autorização.
        if (! $user instanceof User || ! $address instanceof Address || ! $this->user()) {
            return false;
        }

        // Primeiro, verifica se o usuário autenticado tem permissão para atualizar o usuário alvo.
        // Isso cobre casos onde um admin pode atualizar o endereço de qualquer usuário.
        if (Gate::allows('update', $user)) {
            // Se o usuário autenticado é o próprio usuário alvo, ou um admin,
            // então verifica se o endereço pertence ao usuário alvo.
            return $address->user_id === $user->id;
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $addressId = $this->route('address');

        return [
            'street' => ['sometimes', 'string', 'max:255'],
            'number' => ['sometimes', 'string', 'max:10'],
            'neighborhood' => ['sometimes', 'string', 'max:255'],
            'complement' => ['nullable', 'string', 'max:255'],
            'zip_code' => ['sometimes', 'string', 'max:9', new CepFormat()],
        ];
    }
}
        ];
    }
}
