<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;
use Hash;
use Validator;

use App\Models\User;

class LoginController extends Controller
{
    public function formLogin()
    {
        $data['title']  =   'Form Login';

        return view('auth.login', $data);
    }

    public function postLogin(Request $request)
    {
        $officer_number =   $request->officer_number;
        $password       =   $request->password;

        $this->validate($request, [
            'officer_number'    =>  'required',
            'password'          =>  'required',
        ],
        [
            'officer_number.required'   =>  'Nomor Pegawai wajib diisi',
            'password.required'         =>  'Password wajib diisi',
        ]);

        if(Auth::attempt(['officer_number' => $officer_number, 'password' => $password])) {
            return redirect('home');
        } else {
            Auth::logout();
            return redirect()->route('login')->with(['error' => 'Nomor Pegawai atau Password salah']);
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
