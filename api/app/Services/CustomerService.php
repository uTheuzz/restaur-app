<?php

namespace App\Services;

use App\Models\Customer;

class CustomerService
{

    private static function build(array $data)
    {
        $data = [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'document_type' => $data['document_type'],
            'document' => $data['document'],
            'gender' => $data['gender']
        ];

        return $data;
    }

    public static function store(array $data)
    {
        $data = self::build($data);

        $customer = Customer::create($data);

        return $customer;
    }

    public static function update(array $data, Customer $customer)
    {
        $data = self::build($data);

        $customer->update($data);

        return $customer;
    }

}