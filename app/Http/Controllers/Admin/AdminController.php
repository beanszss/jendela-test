<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Book;
use App\Models\User;
use App\Repository\UserRepository;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;


class AdminController extends Controller
{
    public function dashboard() {
        $books = Book::count();
        $authors = Author::count();
        $users = User::count();
        return view("admin.pages.dashboard", compact("books", "authors", "users"));
    }

    public function listUsers() {
        $users = UserRepository::listUsers();
        return view("admin.pages.user.index", compact("users"));
    }

    public function createUser() {
        $roles = Role::where("name", "!=", "super-admin")
            ->get();
        return view("admin.pages.user.form", compact("roles"));
    }

    public function addUser() {
        $response = UserRepository::addUser();
        if(!$response["status"]) {
            alertNotify(false, $response["data"]);
            return back()->withInput();
        }

        alertNotify(true, $response["data"]);
        return redirect(url("dashboard/user"));
    }
}
