<?php

namespace App\Models;

use App\Models\Concerns\CustomConnectionIdentifier;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Product extends Model
{
    use HasFactory, SoftDeletes, HasSlug, CustomConnectionIdentifier;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'image',
        'category_id',
    ];

    protected $with = ['category'];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $hidden = [
        'category_id'
    ];

    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    # Para voltar ao normal, mude de 'slug' para 'id' ou comente o método getRouteKeyName();
    public function getRouteKeyName()
    {
        return 'id';
    }

    public function category()
    {
        return $this->hasOne(ProductCategory::class, 'id', 'category_id');
    }
}