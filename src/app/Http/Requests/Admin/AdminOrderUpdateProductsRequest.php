<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AdminOrderUpdateProductsRequest extends FormRequest
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
            'order_id'          => 'required|int',
            'product_id'        => 'required|int',
            'name'              => 'string|min:3',
            'image'             => 'string|min:3',
            'short_description' => 'string|max:255',
            'price'             => 'int|min:1',
            'product_amount'    => 'int',
        ];
    }
}
