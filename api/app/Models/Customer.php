<?php

namespace App\Models;

use App\Models\Concerns\CustomConnectionIdentifier;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes, CustomConnectionIdentifier;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'document_type',
        'document',
        'gender'
    ];

    protected $with = ['phones', 'address'];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function phones()
    {
        return $this->hasMany(CustomerPhone::class);
    }

    public function address()
    {
        return $this->hasMany(CustomerAddress::class);
    }

}