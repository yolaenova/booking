<?php

namespace App\Controllers;

class Customer extends BaseController
{
    public function index()
    {
        return view('customer/dashboard');
    }
}