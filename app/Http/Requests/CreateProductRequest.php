<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'code' => 'required|string|max:50|unique:products',
            'name' => 'required|string|max:255',
            'unit' => 'required|in:kg,lb,unidad',
            'description' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'code.required' => 'El código del producto es obligatorio.',
            'code.unique' => 'Este código de producto ya existe.',
            'name.required' => 'El nombre del producto es obligatorio.',
            'unit.required' => 'La unidad de medida es obligatoria.',
            'unit.in' => 'La unidad de medida debe ser kg, lb o unidad.',
        ];
    }
}