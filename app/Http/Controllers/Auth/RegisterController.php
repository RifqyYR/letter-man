<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function register()
    {
        return view('auth.register');
    }

    public function registerProcess(Request $request)
    {
        $messages = [
            'required' => ':attribute tidak boleh kosong',
            'unique' => ':attribute yang diinput sudah terdaftar',
            'min' => ':attribute minimal 8 karakter',
        ];

        $validator = $this->validate(
            $request,
            [
                'name' => 'required|string|max:255',
                'email' => 'required|string|max:255|unique:users',
                'password' => 'required|string',
                'role' => 'required'
            ],
            $messages
        );

        if ($validator) {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'email_verified_at' => Carbon::now(),
                'remember_token' => Str::random(10),
            ]);
            return redirect()->route('home')->with('success', 'Berhasil menambahkan user.');
        } else {
            return redirect()->back()->with('error', 'Gagal menambahkan user.');
        }
    }
}
