<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    // =========================
    // FORM LOGIN
    // =========================
    public function login()
    {
        return view('auth/login');
    }

    // =========================
    // PROSES LOGIN
    // =========================
    public function loginProcess()
    {
        $model = new UserModel();

        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $model->where('email', $email)->first();

        // cek user ditemukan & password benar
        if ($user && password_verify($password, $user['password'])) {

            session()->set([
                'id'      => $user['id'],
                'name'    => $user['name'],
                'email'   => $user['email'],
                'role'    => $user['role'],
                'isLogin' => true
            ]);

            // redirect berdasarkan role
            if ($user['role'] == 'admin') {
                return redirect()->to('/admin');

            } elseif ($user['role'] == 'staff') {
                return redirect()->to('/staff');

            } else {
                // CUSTOMER
                return redirect()->to('/dashboard');
            }
        }

        return redirect()->back()->with('error', 'Email atau password salah');
    }

    // =========================
    // FORM REGISTER
    // =========================
    public function register()
    {
        return view('auth/register');
    }

    // =========================
    // PROSES REGISTER
    // =========================
    public function registerProcess()
    {
        $model = new UserModel();

        $email = $this->request->getPost('email');

        // cek email sudah ada
        $cek = $model->where('email', $email)->first();

        if ($cek) {
            return redirect()->back()->with('error', 'Email sudah terdaftar');
        }

        $model->save([
            'name'     => $this->request->getPost('name'),
            'email'    => $email,
            'phone'    => $this->request->getPost('phone'),
            'password' => password_hash(
                $this->request->getPost('password'),
                PASSWORD_DEFAULT
            ),
            'role'     => 'customer'
        ]);

        return redirect()->to('/login')->with('success', 'Register berhasil, silakan login');
    }

    // =========================
    // LOGOUT
    // =========================
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}