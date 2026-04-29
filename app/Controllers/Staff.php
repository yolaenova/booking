<?php

namespace App\Controllers;

class Staff extends BaseController
{
    public function index()
    {
        return view('staff/dashboard');
    }
}