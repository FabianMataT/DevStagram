<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{

    public function index() 
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        //Modificar el Request
        $request->request->add(['username'=>Str::slug($request->username)]);

        //Validacion
        $validatedData = $request->validate([
            'name' => 'required|max:30',
            'username' => 'required|unique:users|min:3|max:20',
            'email' => 'required|unique:users|email|max:60',
            'password' => 'required|confirmed|min:6',
        ]);

        User::create([
            'name'=> $request->name,
            'username'=>$request->username,
            'email'=>$request->email,
            'password'=>Hash::make($request->password)
        ]);

        //Autonticar un usuario
        //auth()->attempt([
        //    'email'=>$request->email,
        //    'passowrd'=>$request->password
        //]);
        
        //Otra forma de autenticar el usuario
        auth()->attempt($request->only('email', 'password'));
 
        //Redireccionar al usuario
        return redirect()->route('post.index');
    }
}
