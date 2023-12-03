<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Str;

class UserRepository
{
    public function register(string $email): User
    {
        $newUser = new User([
            'email' => $email,
            'ulid_token' => Str::ulid()
        ]);
        $newUser->save();

        return $newUser->refresh();
    }
}