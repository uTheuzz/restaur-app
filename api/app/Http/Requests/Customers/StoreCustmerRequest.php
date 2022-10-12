<?php

namespace App\Http\Requests\Customers;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Config;

class StoreCustmerRequest extends FormRequest
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
            'first_name'  => 'required|string',
            'email' => "email|nullable|unique:$connection.customers",
            'document_type' => 'required|string',
            'document' => "required|string|unique:$connection.customers",
        ];
    }

    public function messages()
    {
        return [
            'first_name.required' => 'First Name is required',
            
            'email.unique' => 'The email already exists',
            'email.email' => 'Email is not valid',

            'document_type.required' => 'Document Type is required',
            'document.required' => 'Document is required',
            'document.unique' => 'The document already exists',
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
