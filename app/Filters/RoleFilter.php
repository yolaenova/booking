<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->get('isLogin')) {
            return redirect()->to('/login');
        }

        $role = strtolower(trim(session()->get('role')));

        // mapping role
        if ($role == 'administrator') {
            $role = 'admin';
        }

        $allowed = [];

        if ($arguments) {
            foreach ($arguments as $arg) {
                $allowed[] = strtolower(trim($arg));
            }
        }

        if (!in_array($role, $allowed)) {
            return redirect()->to('/login');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}