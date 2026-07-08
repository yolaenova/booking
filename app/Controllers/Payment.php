<?php

namespace App\Controllers;

use App\Models\BookingModel;
use App\Models\UserModel;
use Config\Midtrans;
use Midtrans\Snap;
use Midtrans\Notification;

class Payment extends BaseController
{
    protected $bookingModel;
    protected $userModel;

    public function __construct()
    {
        Midtrans::init();

        $this->bookingModel = new BookingModel();
        $this->userModel    = new UserModel();
    }

    public function pay($id)
    {
        $booking = $this->bookingModel
            ->where('id', $id)
            ->first();

        if (!$booking) {
            return redirect()->back()->with('error', 'Booking tidak ditemukan.');
        }

        $user = $this->userModel->find($booking['user_id']);

        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan.');
        }

        $params = [
            'transaction_details' => [
                'order_id' => 'BOOKING-' . $booking['id'] . '-' . time(),
                'gross_amount' => (int)$booking['total_price']
            ],

            'customer_details' => [
                'first_name' => $user['name'],
                'email'      => $user['email']
            ],

            'item_details' => [
                [
                    'id'       => $booking['service_id'],
                    'price'    => (int)$booking['total_price'],
                    'quantity' => 1,
                    'name'     => 'Booking Makeup'
                ]
            ]
        ];

        $snapToken = Snap::getSnapToken($params);

        return view('payment_snap', [
            'snapToken' => $snapToken,
            'booking'   => $booking
        ]);
    }

    public function callback()
    {
        $notification = new Notification();

        $transaction = $notification->transaction_status;
        $order_id    = $notification->order_id;

        preg_match('/BOOKING-(\d+)-/', $order_id, $match);

        if (!isset($match[1])) {
            return;
        }

        $bookingId = $match[1];

        switch ($transaction) {

            case 'capture':
            case 'settlement':

                $this->bookingModel->update($bookingId, [
                    'payment_status' => 'paid',
                ]);

                break;

            case 'pending':

                $this->bookingModel->update($bookingId, [
                    'payment_status' => 'unpaid'
                ]);

                break;

            case 'expire':

                $this->bookingModel->update($bookingId, [
                    'payment_status' => 'expired'
                ]);

                break;

            case 'cancel':
            case 'deny':

                $this->bookingModel->update($bookingId, [
                    'payment_status' => 'failed'
                ]);

                break;
        }
    }
    public function success()
    {
        $id = $this->request->getPost('booking_id');

        if (!$id) {
            return $this->response->setJSON([
                'status' => false
            ]);
        }

        $this->bookingModel->update($id, [

            'payment_status' => 'paid',

        ]);

        return $this->response->setJSON([
            'status' => true
        ]);
    }
}