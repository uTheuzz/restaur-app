<?php

namespace App\Services;

use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductOrdered;

class OrderService
{

    private static function build(array $data)
    {
        $data = [
            'amount' => array_key_exists('amount', $data) ? $data['amount'] : 0.0,
            'customer_id' => array_key_exists('customer_id', $data) ? $data['customer_id'] : null,
            'payment_type_id' => array_key_exists('payment_type_id', $data) ? $data['payment_type_id'] : null,
            'delivery_id' => array_key_exists('delivery_id', $data) ? $data['delivery_id'] : null
        ];

        return $data;
    }

    public static function store(array $data)
    {
        $data = self::build($data);

        $order = Order::create($data);

        return $order;
    }

    public static function storeOrderProducts(array $products, Order $order)
    {
        foreach ($products as $product) { 
            ProductOrdered::create([
                'order_id' => $order->id,
                'product_id' => $product
            ]);
        }

        $order = self::calculateAmount($order);
        
        return new OrderResource($order);
    }

    public static function update(array $data, Order $order)
    {
        $data = self::build($data);

        $order->update($data);

        return $order;
    }

    public static function deleteOrderProducts(array $codes, Order $order)
    {
        foreach ($codes as $code) {
            $product = ProductOrdered::where([['id', $code], ['order_id', $order->id]])->first();
            if ($product) {
                $product->delete();
            }
        }

        $order = self::calculateAmount($order);

        return new OrderResource($order);
    }

    public static function calculateAmount(Order $order)
    {
        $order = Order::where('id', $order->id)->first();
        $products = $order->relationsToArray()['products'];

        $amount = array_sum(array_map(fn($item) => $item['price'], $products));

        $data = [
            'amount' => $amount,
            'customer_id' => $order->customer_id,
            'payment_type_id' => $order->payment_type_id,
            'delivery_id' => $order->delivery_id
        ];

        $order->update($data);

        return $order;
    }

}