<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
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
            'product_id' => 'required|exists:products,id',
            'name'       => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'Product ID wajib diisi.',
            'product_id.exists'   => 'Product ID tidak ditemukan.',
            'name.required'       => 'Nama kategori wajib diisi.',
            'name.max'            => 'Nama kategori tidak boleh lebih dari 255 karakter.',
        ];
    }
}
