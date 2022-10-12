<?php

namespace App\Services;

use App\Models\User;

class UserService
{

    private static function build(array $data)
    {
        $data = [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'document_type' => $data['document_type'],
            'document' => $data['document'],
            'password' => bcrypt($data['password']),
            'gender' => $data['gender']
        ];

        return $data;
    }

    public static function store(array $data)
    {
        $data = self::build($data);

        $user = User::create($data);

        return $user;
    }

    public static function update(array $data, User $user)
    {
        $data = self::build($data);

        $user->update($data);

        return $user;
    }

}