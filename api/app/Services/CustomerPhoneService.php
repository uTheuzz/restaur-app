<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\CustomerPhone;

class CustomerPhoneService
{

    private static function build(array $data, Customer $customer = null)
    {
        $data = [
            'type' => $data['type'],
            'country_code' => $data['country_code'],
            'number' => $data['number'],
            'customer_id' => $customer['id']
        ];

        return $data;
    }

    public static function store(array $data, Customer $customer)
    {
        $data = self::build($data, $customer);
        CustomerPhone::create($data);

        return $customer;
    }

    public static function update(array $data, Customer $customer, CustomerPhone $customerPhone)
    {
        $data = self::build($data, $customer);
        $customerPhone->update($data);

        return $customerPhone;
    }

}