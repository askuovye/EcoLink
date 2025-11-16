<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCollectionPointRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'            => 'required|string|max:100|min:2',
            'latitude'        => 'required|numeric|between:-90,90',
            'longitude'       => 'required|numeric|between:-180,180',
            'address'         => 'nullable|string|max:255',
            'operating_hours' => 'nullable|string|max:255',
            'phone'           => 'nullable|string|max:30',
            'verified'        => 'sometimes|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O nome do ponto é obrigatório.',
            'name.string'   => 'O nome deve ser um texto válido.',
            'name.max'      => 'O nome pode ter no máximo :max caracteres.',
            'latitude.required'  => 'A latitude é obrigatória.',
            'longitude.required' => 'A longitude é obrigatória.',
            'latitude.numeric'   => 'Latitude deve ser numérica.',
            'longitude.numeric'  => 'Longitude deve ser numérica.',
        ];
    }
}
