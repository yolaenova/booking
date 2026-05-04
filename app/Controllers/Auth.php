<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

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
        $email = trim($this->request->getPost('email'));
        $password = trim($this->request->getPost('password'));

        $user = $this->userModel->where('email', $email)->first();

        if ($user) {
            if (password_verify($password, $user['password'])) {
                
                // Pastikan key 'isLoggedIn' sama dengan yang dicek di RoleFilter
                session()->set([
                    'id'          => $user['id'],
                    'name'        => $user['name'],
                    'email'       => $user['email'],
                    'role'        => strtolower(trim($user['role'])),
                    'isLoggedIn'  => true 
                ]);

                // Redirect berdasarkan role
                $role = session()->get('role');
                if ($role == 'admin' || $role == 'administrator') {
                    return redirect()->to('/admin');
                } elseif ($role == 'staff') {
                    return redirect()->to('/staff');
                } else {
                    // Pastikan rute /dashboard mengarah ke Customer::index
                    return redirect()->to('/dashboard');
                }
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
        $email = $this->request->getPost('email');

        // Cek apakah email sudah ada
        $cek = $this->userModel->where('email', $email)->first();

        if ($cek) {
            return redirect()->back()->with('error', 'Email sudah terdaftar');
        }

        $this->userModel->save([
            'name'     => $this->request->getPost('name'),
            'email'    => $email,
            'phone'    => $this->request->getPost('phone'),
            'password' => password_hash(
                $this->request->getPost('password'),
                PASSWORD_DEFAULT
            ),
            'role'     => 'customer' // Otomatis jadi customer saat daftar
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