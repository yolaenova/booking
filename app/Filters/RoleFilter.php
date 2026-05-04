<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // 1. Perbaikan Key Session: Sesuaikan dengan yang ada di Auth Controller
        // Jika di Auth pakai 'isLoggedIn', maka di sini harus 'isLoggedIn'
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // 2. Ambil role dan bersihkan (lowercase)
        $role = strtolower(trim(session()->get('role')));

        // 3. Mapping Role (Seringkali di DB tulisannya 'administrator' tapi di route cuma 'admin')
        if ($role === 'administrator') {
            $role = 'admin';
        }

        // 4. Cek apakah role user ada di dalam daftar yang diizinkan oleh routes
        if ($arguments) {
            $allowed = array_map('strtolower', $arguments);

            if (!in_array($role, $allowed)) {
                // Jika user login tapi rolenya gak cocok, lempar ke dashboard masing-masing
                if ($role === 'admin') {
                    return redirect()->to('/admin');
                } else if ($role === 'customer') {
                    return redirect()->to('/dashboard');
                }
                
                return redirect()->to('/login')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Biasanya kosong
    }
}