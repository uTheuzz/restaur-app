<?php

namespace App\Models;

use App\Models\Concerns\CustomConnectionIdentifier;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerPhone extends Model
{
    use HasFactory, SoftDeletes, CustomConnectionIdentifier;

    protected $fillable = ['type', 'country_code', 'number', 'customer_id'];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}