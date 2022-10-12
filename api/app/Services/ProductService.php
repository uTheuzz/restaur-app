<?php

namespace App\Services;

use App\Models\Product;

class ProductService
{

    private static function build(array $data)
    {
        $data = [
            'name' => $data['name'],
            'description' => $data['description'],
            'price' => $data['price'],
            'image' => $data['image'],
            'category_id' => $data['category_id'],
        ];

        return $data;
    }

    public static function store(array $data)
    {
        $data = self::build($data);

        $product = Product::create($data);

        return $product;
    }

    public static function update(array $data, Product $product)
    {
        $data = self::build($data);

        $product->update($data);

        return $product;
    }

}