<?php

namespace App\Http\Requests;

use App\Rules\CpfFormat;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // Permite que qualquer pessoa crie um novo usuário (registro).
        // Se a criação de usuários fosse restrita (ex: apenas por admin),
        // a lógica de autorização seria implementada aqui.
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'phone' => ['nullable', 'string', 'max:20'],
            'cpf' => ['required', 'string', 'max:14', 'unique:users', new CpfFormat()],
            'role' => ['nullable', 'string', 'in:admin,user'],
        ];
    }
}