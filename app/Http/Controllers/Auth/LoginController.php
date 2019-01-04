<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);


        if (Auth::attempt(['user_name' => $request->user_name, 'password' => $request->password, 'condition'=>1])){
            return redirect()->route('main');
        }

        return back()
        ->withErrors(['user_name' => trans('auth.failed')])
        ->withInput(request(['user_name']));
    }

    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
           'user_name'      => 'required|string',     
           'password'       => 'required|string'     
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        return redirect('/');
    }
}
