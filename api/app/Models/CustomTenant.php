<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Multitenancy\Models\Tenant;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Artisan;

class CustomTenant extends Tenant
{
    use HasFactory, SoftDeletes, HasSlug;

    protected $table = 'tenants';

    protected $with = ['modules'];

    protected $fillable = [
        'name',
        'database',
        'database_user',
        'database_password',
        'database_type',
        'code',
        'contact_document_type',
        'contact_document',
        'contact_name',
        'contact_phone',
        'contact_email'
    ]; 

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $hidden = [
        'database_type',
        'database_password'
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
            set: fn ($value) => strtoupper(str_replace(' ', '_', str_replace(array('\'', '"', ',' , ';', '<', '>' ),'', $value)))
        );
    }

    # Para voltar ao normal, mude de 'slug' para 'id' ou comente o método getRouteKeyName();
    public function getRouteKeyName()
    {
        return 'id';
    }

    protected static function booted()
    {
        static::creating(fn(CustomTenant $model) => $model->configureDatabase($model));
        static::created(fn(CustomTenant $model) => $model->createDatabase($model));
        static::updating(fn(CustomTenant $model) => $model->updatingTenant($model));
    }

    public function createDatabase($model)
    {
        if ($model['database_type'] == 'cloud') {
            $this->makeCurrent();
            Artisan::call('CreateDatabase:createdb', ['data' => $model, 'driver' => 'mysql']);
            Artisan::call('tenants:artisan', ['artisanCommand' => 'migrate --database=tenant --path=database/migrations/tenants', '--tenant' => $model->id]);
            $this->forgetCurrent();
        }
    }

    public function configureDatabase($model)
    {
        if ($model['database']) {
            $model['database'] = $model['database'];
            $model['database_type'] = 'local';
        } else {
            $model['database'] = strtolower($model->code);
            $model['database_type'] = 'cloud';
        }

        $model['database_user'] = $model['database_user'] ? $model['database_user'] : 'teste';
        $model['database_password'] = $model['database_password'] ? $model['database_password'] : bcrypt('teste');

        $exists = $this->where('database', $model['database'])->first();

        if ($exists) {
            throw new \Exception("Company Database already exists", 1);
        }
    }

    public function updatingTenant($model)
    {
        $model['database_password'] =  $model['database_password'];
    }

    public function modules()
    {
        return $this->belongsToMany(Module::class, 'module_tenants', 'tenant_id', 'module_id')
        ->wherePivot('deleted_at', '=', null);
    } 
}