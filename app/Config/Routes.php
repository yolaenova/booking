<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// =====================================
// DEFAULT & AUTH
// =====================================
$routes->get('/', 'Auth::login');
$routes->get('login', 'Auth::login');
$routes->post('login-process', 'Auth::loginProcess');
$routes->get('register', 'Auth::register');
$routes->post('register-process', 'Auth::registerProcess');
$routes->get('logout', 'Auth::logout');

// =====================================
// ADMIN
// =====================================
$routes->group('admin', ['filter' => ['auth', 'role:admin']], function ($routes) {

    // Dashboard
    $routes->get('/', 'Admin::index');
    $routes->get('dashboard', 'Admin::index'); // alias dari '/'

    // Booking management
    $routes->get('bookings', 'Booking::index');
    $routes->get('bookings/create', 'Booking::create');
    $routes->post('bookings/save', 'Booking::save');
    $routes->get('bookings/confirm/(:num)', 'Booking::confirm/$1');
    $routes->get('bookings/cancel/(:num)', 'Booking::cancel/$1');
    $routes->get('bookings/delete/(:num)', 'Booking::delete/$1');

    // Service management
    $routes->get('services', 'Service::index');
    $routes->get('services/create', 'Service::create');
    $routes->post('services/save', 'Service::save');
    $routes->get('services/delete/(:num)', 'Service::delete/$1');

    // Integrasi WhatsApp (WAHA API) — hanya admin yang login
    $routes->get('whatsapp', 'Admin::whatsapp');

    // Dokumentasi API
    $routes->get('api-documentation', '\App\Controllers\BookingApiController::documentation');
});

// =====================================
// CUSTOMER
// =====================================
$routes->group('', ['filter' => ['auth', 'role:customer']], function ($routes) {

    // Dashboard
    $routes->get('customer', 'Customer::index');
    $routes->get('dashboard', 'Customer::index'); // alias dari 'customer'

    // Alur layanan & booking
    $routes->get('services-list', 'Customer::services');
    $routes->get('services', 'Customer::services'); // alias, untuk akses manual
    $routes->get('booking/(:num)', 'Customer::booking/$1');       // form input detail booking
    $routes->post('booking/save', 'Customer::saveBooking');       // simpan booking

    // Riwayat & detail booking
    $routes->get('booking-history', 'Customer::bookingHistory');
    $routes->get('bookings', 'Customer::bookingHistory');         // alias
    $routes->get('booking/detail/(:num)', 'Customer::detail/$1');
    $routes->get('customer/booking/detail/(:num)', 'Customer::detail/$1'); // alias untuk sidebar baru

    // Pembayaran
    $routes->get('booking/pay/(:num)', 'Customer::pay/$1');
    $routes->get('payment/(:num)', 'Payment::pay/$1');
    $routes->post('payment/callback', 'Payment::callback');
    $routes->post('payment/success', 'Payment::success');
});

// =====================================
// STAFF
// =====================================
$routes->group('staff', ['filter' => ['auth', 'role:staff']], function ($routes) {
    $routes->get('/', 'Staff::index');
    $routes->get('dashboard', 'Staff::index'); // alias dari '/'
});

// =====================================
// PUBLIC / EXTERNAL API
// =====================================
// Ditaruh di luar grup admin agar sistem luar (mis. Postman) bisa memanggil via token
$routes->get('api/summary', 'ApiController::getDashboardSummary');
$routes->post('api/whatsapp/callback', 'ApiController::whatsappCallback');

$routes->get('api/bookings', '\App\Controllers\BookingApiController::getAllBookings');
$routes->get('api/services', '\App\Controllers\ServiceApiController::getServices');
$routes->get('api/booking-status/(:num)', '\App\Controllers\BookingApiController::bookingStatus/$1');