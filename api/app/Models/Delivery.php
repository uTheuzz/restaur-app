<?php

namespace App\Models;

use App\Models\Concerns\CustomConnectionIdentifier;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Delivery extends Model
{
    use HasFactory, SoftDeletes, CustomConnectionIdentifier;

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

}