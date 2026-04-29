<?php

namespace App\Controllers;

use App\Models\BookingModel;
use App\Models\UserModel;

class Admin extends BaseController
{
    public function index()
    {
        // SESSION SEMENTARA
        session()->set([
            'username' => 'Admin',
            'role'     => 'Administrator'
        ]);

        $bookingModel = new BookingModel();
        $userModel = new UserModel();

        $data['totalBooking'] = $bookingModel->countAll();
        $data['totalCustomer'] = $userModel->where('role','customer')->countAllResults();

        $data['bookings'] = $bookingModel
            ->select('bookings.*, users.name, services.name as service_name')
            ->join('users','users.id = bookings.user_id')
            ->join('services','services.id = bookings.service_id')
            ->findAll();

        $data['revenue'] = array_sum(array_column($data['bookings'],'total_price'));

        return view('admin/dashboard', $data);
    }
}