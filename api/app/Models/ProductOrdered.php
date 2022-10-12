<?php

namespace App\Models;

use App\Models\Concerns\CustomConnectionIdentifier;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductOrdered extends Model
{
    use HasFactory, SoftDeletes, CustomConnectionIdentifier;

    protected $fillable = ['order_id', 'product_id'];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}