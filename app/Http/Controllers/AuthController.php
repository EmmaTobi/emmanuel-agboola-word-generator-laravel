<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserWordService;
use App\Services\RandomWordService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Display Login Form
     */
    public function loginForm()
    {
       if(auth()->user()){
            return redirect('/words');
       };

       return view('welcome');
    }

    /**
     * Handle User Login request
     * @param Request $request
     */
    public function login(Request $request)
    {
        $user = auth()->user();
        if($user){
            Auth::login($user);
            return redirect('/words');
        };

        $data = $request->validate([
            'firstname' => 'required|string',
            'lastname' => 'required|string',
        ]);

        $user = User::firstOrCreate([
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
        ]);

        Auth::login($user);

        return redirect('/words');
    }

    /**
     * Logout a user
     * Invalidate a user session
     */
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
