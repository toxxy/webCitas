<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{

    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'RFC' => ['required'],
            'orden' => ['required'],
            'status' => ['required'],
            'Nombre' => ['required'],
            'descripcion' => ['required'],
            'FechaRealizada' => ['required'],
            'FechaProgramada' => ['required'],
        ];
    }
}
