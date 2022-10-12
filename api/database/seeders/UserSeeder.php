<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'first_name' => 'Admin',
            'last_name' => 'System',
            'email' => 'admin@gmail.com',
            'document_type' => 'CPF',
            'document' => '07757148368',
            'password' => bcrypt('admin'),
        ])->assignRole('SUPER_ADMIN');
    
    }
}
