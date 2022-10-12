<?php

namespace App\Models;

use App\Models\Concerns\CustomConnectionIdentifier;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Contracts\Permission as PermissionContract;
use Spatie\Permission\Exceptions\PermissionAlreadyExists;
use Spatie\Permission\Guard;

class CustomPermission extends Permission implements PermissionContract
{
    use HasFactory, CustomConnectionIdentifier;

    protected $dates = [
        'created_at',
        'updated_at'
    ];

}