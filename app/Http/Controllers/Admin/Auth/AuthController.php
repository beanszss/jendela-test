<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class AuthController extends Controller
{
    public function index() {
        return view("admin.pages.login");
    }

    public function forgotPassword() {
        return view("admin.pages.reset-password");
    }

    public function forgotPasswordProcess(Request $request) {
        $validation = Validator::make($request->all(), [
            "email" => "required|exists:users,email"
        ]);

        if($validation->fails()) {
            alertNotify(false, "User not found!");
            return redirect(url("/"));
        }

        $token = Str::random(64);

        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        return redirect(url("reset-password?token=" . $token));

    }

    public function resetPasswordView() {
        $check = DB::table('password_reset_tokens')->where("token", request()->get("token"))->first();
        if(!$check) {
            alertNotify(false, "Token not valid!");
            return redirect(url("/"));
        }

        return view("admin.pages.reset", compact("check"));
    }

    public function resetPasswordProcess(Request $request) {
        $validation = Validator::make($request->all(), [
            'email' => 'required|email|exists:users',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required'
        ]);

        if($validation->fails()) {
            alertNotify(false, "User not found!");
            return redirect(url("/"));
        }

        $updatePassword = DB::table('password_reset_tokens')
            ->where([
                'email' => $request->email,
                'token' => $request->token
            ])
            ->first();

          if(!$updatePassword){
            alertNotify(false, "Invalid token");
            return redirect(url("/"));
          }

          $user = User::where('email', $request->email)
            ->update(['password' => Hash::make($request->password)]);

          DB::table('password_reset_tokens')->where(['email'=> $request->email])->delete();

        alertNotify(true, "Success reset password!");
        return redirect(url("/"));
    }

    public function login(Request $request) {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            if(Auth::user()->getRoleNames()->isEmpty()) {
                Auth::logout();
                alertNotify(false, "You doesnt have role to access this site!");
                return back();
            }

            return redirect()->intended('/dashboard');
        }

        alertNotify(false, "Authentication Failed");
        return back();
    }

    public function logout() {
        Auth::guard('web')->logout();

        request()->session()->invalidate();

        request()->session()->regenerateToken();

        return redirect('/');
    }
}
