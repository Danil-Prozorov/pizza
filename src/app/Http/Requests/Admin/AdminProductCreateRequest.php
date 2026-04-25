<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AdminProductCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = auth('api')->user();

        if($user->is_admin){
            return true;
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
        return [
            'name' => 'required|string|max:255',
            'image' => 'mimes:jpeg,png,jpg,gif,svg|max:2048',
            'price' => 'required|integer',
            'description' => 'string|nullable',
            'recipe' => 'string|nullable',
            'short_desc' => 'string|nullable|max:255',
            'category' => 'required|integer',
            'stock' => 'integer',
            'active' => 'integer',
            'status' => 'integer|exists:product_statuses,id',
        ];
    }
}
