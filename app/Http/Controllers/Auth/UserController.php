<?php

namespace App\Http\Controllers\Auth;

use DB;
use Validator;

use App\Models\Session;
use App\Http\Controllers\AppController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class UserController extends AppController
{

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;


    /**
     * Show the login form
     *
     * @return Response
     */
    public function showLogin()
    {
        $viewData = [
            'page' => [
                'title' => 'Iniciar sesión',
            ],
        ];

        return view('user.login', $viewData);
    }

    public function doLogin(Request $request) {

        $redirect = 'login';
        $username = $request->input('cto34_user');
        $password = $request->input('cto34_pass');

        $rules = [
            'cto34_user' => 'required',
            'cto34_pass' => 'required',
        ];

        $messages = [
            'cto34_user.required' => 'Usuario requerido',
            'cto34_pass.required' => 'Contraseña requerida',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

         if ($validator->fails()) {
            return redirect($redirect)
                        ->withErrors($validator)
                        ->withInput();
        }

        if (Auth::attempt(['username' => $username, 'password' => $password, 'DeletedAt' => null], false)) {

            $session = new Session();
            $session->setConnection('mysql-writer');
            $session->tbUsuarioID_Sesion = Auth::id();
            $session->save();

            return redirect('panel');
        } else {

            $errors = [
                'userNotFound' => 'Usuario o contraseña incorrecto',
            ];
            
            return redirect($redirect)
                        ->withErrors($errors)
                        ->withInput();
        }
    }

    public function doLogout(Request $request) {
        Auth::logout();
        return redirect('/login');
    }
}