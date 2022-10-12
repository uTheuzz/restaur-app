<?php

namespace App\Models;

use App\Models\Concerns\CustomConnectionIdentifier;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerAddress extends Model
{
    use HasFactory, SoftDeletes, CustomConnectionIdentifier;

    protected $fillable = [
        'street',
        'number',
        'complement',
        'neighborhood',
        'city',
        'state',
        'zip_code',
        'country',
        'customer_id'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}