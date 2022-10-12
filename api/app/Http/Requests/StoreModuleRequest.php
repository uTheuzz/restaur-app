<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class StoreModuleRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name'  => 'required|string|unique:modules',
            'description' => 'required|string'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Name is required',
            'name.unique' => 'The module already exists',

            'description.required' => 'Description is required',
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
