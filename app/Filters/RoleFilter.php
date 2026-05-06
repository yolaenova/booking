<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // 1. Cek login
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        // 2. Ambil role user
        $role = strtolower(trim(session()->get('role')));

        // Normalisasi (kalau ada 'administrator')
        if ($role === 'administrator') {
            $role = 'admin';
        }

        // 3. Kalau tidak ada parameter role di route → biarkan lewat
        if (!$arguments) {
            return;
        }

        // 4. Ambil role yang diizinkan dari route
        $allowed = array_map('strtolower', $arguments);

        // 5. Kalau role tidak sesuai → redirect sesuai role
        if (!in_array($role, $allowed)) {

            // ❗ Hindari infinite redirect
            $currentPath = $request->getUri()->getPath();

            if ($role === 'admin' && strpos($currentPath, 'admin') === false) {
                return redirect()->to('/admin');
            }

            if ($role === 'customer' && strpos($currentPath, 'dashboard') === false) {
                return redirect()->to('/dashboard');
            }

            // fallback
            return redirect()->to('/login');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // kosong
    }
}