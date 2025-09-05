<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Rules\CpfFormat;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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

        // Autoriza se o usuário autenticado pode atualizar o usuário alvo
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
        $userId = $this->route('user');
        if ($userId instanceof \App\Models\User) {
            $userId = $userId->id;
        }

        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'string', 'email', 'max:255', Rule::unique('users')->ignore($userId)],
            'password' => ['sometimes', 'string', 'min:8'],
            'phone' => ['nullable', 'string', 'max:20'],
            'cpf' => ['sometimes', 'string', 'max:14', Rule::unique('users')->ignore($userId), new CpfFormat()],
            'role' => ['sometimes', 'string', 'in:admin,user'],
        ];
    }
}