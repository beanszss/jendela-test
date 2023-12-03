<?php

namespace App\Repository;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserRepository {
    public static function listUsers() {
        return User::get();
    }

    public static function addUser() {
        try {
            $validation  = Validator::make(request()->all(), [
                "name" => "required|string",
                "email" => "required|string|unique:users,email",
                "password" => "required",
                "roles" => "required|exists:roles,name"
            ]);

            if($validation->fails()) {
                return responseCustom(collect($validation->errors()->all())->implode(' , '), false);
            }

            $user = User::create([
                "name" => request()->get("name"),
                "email" => request()->get("email"),
                "password" => Hash::make(request()->get("password"))
            ]);

            $user->assignRole(request()->get("roles"));

            return responseCustom("Success add users", true);
        } catch (\Throwable $th) {
            return responseCustom($th->getMessage(), false);
        }
    }
}
