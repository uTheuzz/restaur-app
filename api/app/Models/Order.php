<?php

namespace App\Models;

use App\Models\Concerns\CustomConnectionIdentifier;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes, CustomConnectionIdentifier;

    protected $fillable = ['amount', 'customer_id', 'payment_type_id', 'delivery_id'];

    protected $with = ['customer', 'paymentType', 'delivery', 'products'];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $hidden = ['customer_id', 'payment_type_id', 'delivery_id'];

    public function customer()
    {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }

    public function paymentType()
    {
        return $this->hasOne(PaymentType::class, 'id', 'payment_type_id');
    }

    public function delivery()
    {
        return $this->hasOne(Delivery::class, 'id', 'delivery_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_ordereds', 'order_id', 'product_id')
        ->as('code')
        ->withPivot('id')
        ->wherePivot('deleted_at', '=', null);
    }
}