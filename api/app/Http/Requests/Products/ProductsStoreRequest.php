<?php

namespace App\Http\Requests\Products;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Config;


class ProductsStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $connection = Config::get('ConnectionName');

        return [
            'name'  => 'required|string',
            'desciption' => 'nullable|string',
            'price' => 'required|numeric',
            // 'image' => 'nullable|file'
            'category_id' => 'nullable|integer'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Product name is required',

            'price.required' => 'Product price is required',
            'price.numeric' => 'Product price must be numeric'
            
        ];
    }

    public function failedValidation(Validator $validator)
    {
        $errors = [];

        foreach ($validator->errors()->toArray() as $error) {
            array_push($errors, $error);
        }

        throw new HttpResponseException(response()->json([
            'status'   => 'error',
            'message'   => 'Error in one or more form fields',
            'errors' => $errors,
        ], 400));
    }
}
