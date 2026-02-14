<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.users.index', [
            'users' => User::latest()->get()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'email'=>'required|email|unique:users',
            'role'=>'required'
        ]);

        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'role'=>$request->role,
            'password'=>Hash::make('password')
        ]);

        logAktivitas(
            'User',
            'Menambahkan user #'.$user->id.' '.$user->name.' ('.$user->email.') dengan role '.$user->role
        );

        return back()->with('success','User berhasil dibuat');
    }
}
