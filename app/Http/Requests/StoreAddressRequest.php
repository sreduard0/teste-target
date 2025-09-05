<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Rules\CepFormat;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreAddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // Obtém o usuário alvo da rota (via Route Model Binding)
        $user = $this->route('user');

        // Se o usuário alvo não for encontrado (o que Route Model Binding já faria),
        // ou se não houver usuário autenticado, nega a autorização.
        if (! $user instanceof User || ! $this->user()) {
            return false;
        }

        // Autoriza se o usuário autenticado pode adicionar um endereço para o usuário alvo
        // (ex: próprio perfil ou se for admin).
        return Gate::allows('update', $user);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:users,id'],
            'street' => ['required', 'string', 'max:255'],
            'number' => ['required', 'string', 'max:10'],
            'neighborhood' => ['required', 'string', 'max:255'],
            'complement' => ['nullable', 'string', 'max:255'],
            'zip_code' => ['required', 'string', 'max:9', new CepFormat()],
        ];
    }
}