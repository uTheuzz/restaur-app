<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLandlordTenantsTable extends Migration
{
    public function up()
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('database')->unique();
            $table->string('database_user');
            $table->string('database_password');
            $table->string('database_type');
            $table->string('slug')->unique();
            $table->string('code')->unique();
            $table->string('contact_document_type');
            $table->string('contact_document')->unique();
            $table->string('contact_name');
            $table->string('contact_phone');
            $table->string('contact_email');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
