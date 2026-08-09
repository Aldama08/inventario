<?php

namespace App\Controllers;

use App\Models\UsuarioModel;

class Auth extends BaseController
{
    public function index()
    {
        // Si ya está logueado, lo mandamos al inventario
        if (session()->get('isLoggedIn')) {
            return redirect()->to('inventario');
        }
        return view('auth/login');
    }

    public function procesarLogin()
    {
        $usuarioModel = new UsuarioModel();

        $usuario = $this->request->getPost('usuario');
        $password = $this->request->getPost('password');

        $user = $usuarioModel->where('usuario', $usuario)->first();

        // Verificamos si el usuario existe y si el hash coincide con la contraseña escrita
        if ($user && password_verify($password, $user['password'])) {
            
            // Guardamos los datos en la sesión
            $sesionData = [
                'id_usuario' => $user['id'],
                'usuario'    => $user['usuario'],
                'rol'        => $user['rol'],
                'isLoggedIn' => true
            ];
            session()->set($sesionData);

            return redirect()->to('inventario');
        } else {
            return redirect()->back()->with('error', 'Usuario o contraseña incorrectos.');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('login');
    }

}