<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login()
    {
        if (Auth::check()) { // true sekalian session field di users nanti bisa dipanggil via Auth
            //Login Success
            return redirect()->route('dashboard.main.index');
        }
        return view('auth.login');
    }

    public function doLogin(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|max:100'
            ], [
                'email.required' => 'Email harus diisi!',
                'password.required' => 'Kata sandi harus diisi!'
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }

            $email = strtolower($request->email);
            $password = $request->password;

            if (Auth::attempt(['email' => $email, 'password' => $password])) {
                return redirect()->route('dashboard.main.index');
            } else {
                return redirect()->back()->withInput()->with('error', 'Email atau password salah!');
            }
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', 'Ada sesuatu yang salah di server!');
        }
    }

    public function forgotPassword()
    {
        return view('auth.forgot');
    }

    public function sendMail(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email|exists:users'
            ]);

            $token = Str::random(64);

            DB::table('password_resets')->insert([
                'email' => $request->email,
                'token' => $token,
                'created_at' => Carbon::now()
            ]);

            Mail::send('email.forget_password', ['token' => $token], function ($message) use ($request) {
                $message->to($request->email);
                $message->subject('Reset password');
            });

            return back()->with('message', 'Reset password telah dikirim ke email anda.');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', 'Ada sesuatu yang salah di server!');
        }
    }

    public function showResetPasswordForm($token)
    {
        return view('auth.forgot_password_link', ['token' => $token]);
    }

    public function submitResetPasswordForm(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email|exists:users',
                'password' => 'required|string|min:6|confirmed',
                'password_confirmation' => 'required'
            ]);
            $updatePassword = DB::table('password_resets')->where([
                'email' => $request->email,
                'token' => $request->token
            ])->first();

            if (!$updatePassword) {
                return back()->withInput()->with('error', 'Invalid token!');
            }

            $user = User::where('email', $request->email)
                ->update(['password' => Hash::make($request->password)]);

            DB::table('password_resets')->where(['email' => $request->email])->delete();
            return redirect('/login')->with('message', 'Password anda telah berubah.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Ada sesuatu yang salah di server!');
        }
    }

    public function doLogout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
