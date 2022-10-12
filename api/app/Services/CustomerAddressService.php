<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\CustomerAddress;

class CustomerAddressService
{

    private static function build(array $data, Customer $customer = null)
    {
        $data = [
            'street' => $data['street'],
            'number' => $data['number'],
            'complement' => $data['complement'],
            'neighborhood' => $data['neighborhood'],
            'city' => $data['city'],
            'state' => $data['state'],
            'zip_code' => $data['zip_code'],
            'country' => $data['country'],
            'customer_id' => $customer['id']
        ];

        return $data;
    }

    public static function store(array $data, Customer $customer)
    {
        $data = self::build($data, $customer);

        $teste = CustomerAddress::create($data);

        return $teste;
    }

    public static function update(array $data, Customer $customer, CustomerAddress $customerAddress)
    {
        $data = self::build($data, $customer);
        
        $customerAddress->update($data);

        return $customerAddress;
    }

}