<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModuleTenant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'module_id', 'tenant_id', 'start_date', 'expiration_date'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'start_date',
        'expiration_date'
    ];

}