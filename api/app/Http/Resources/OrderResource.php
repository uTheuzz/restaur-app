<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $list = $this->resource->toArray();

        if (array_key_exists('data', $list)) {
            $orders = [];

            foreach ($list['data'] as $key => $order) {
                $data = [];
                foreach ($order['products'] as $product) {
                    $product['code'] = $product['code']['id'];
                    array_push($data, $product);
                }
                $list['data'][$key]['products'] = $data;
            }
        } else {
            $data = [];
            foreach ($list['products'] as $product) {
                $product['code'] = $product['code']['id'];
                array_push($data, $product);
            }
            $list['products'] = $data;
        }

        return $list;
    }
}
