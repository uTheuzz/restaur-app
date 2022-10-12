<?php

namespace App\Http\Requests\Categories;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Config;

class CategoriesStoreRequest extends FormRequest
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
            'name'  => "required|string|unique:$connection.product_categories",
            'description' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Category name is required',
            'name.unique' => 'Category already exists',
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
