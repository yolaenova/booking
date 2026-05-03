<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

abstract class BaseController extends Controller
{
    /**
     * Helpers yang dimuat secara otomatis agar bisa digunakan di semua Controller dan View.
     */
    protected $helpers = ['form', 'url', 'session']; // PINDAHKAN KE SINI

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Jangan taruh $this->helpers di sini lagi.
        
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.
        $this->session = service('session');
    }
}