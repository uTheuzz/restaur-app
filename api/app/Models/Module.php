<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use App\Models\Concerns\CustomConnectionIdentifier;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Module extends Model
{
    use HasFactory, SoftDeletes, HasSlug, CustomConnectionIdentifier;

    protected $fillable = [
        'name', 'description', 'code'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function code(): Attribute 
    {
        return Attribute::make(
            get: fn ($value) => ucfirst($value),
            set: fn ($value) => $this->formatCode($value)
        );
    }

    public function formatCode($value)
    {
        $value = strtr(utf8_decode($value), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
        return strtoupper(str_replace(' ', '_', str_replace(array('\'', '"', ',' , ';', '<', '>', '.'),'', $value)));
    }

    # Para voltar ao normal, mude de 'slug' para 'id' ou comente o método getRouteKeyName();
    public function getRouteKeyName()
    {
        return 'id';
    }

    protected static function booted()
    {
        static::updating(fn(Module $model) => $model->preventsCodeUpdate($model));
    }

    public function preventsCodeUpdate($model)
    {
        $model['code'] = $model->original['code'];
    }
}