<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// =====================================
// DEFAULT & AUTH
// =====================================
$routes->get('/', 'Auth::login');
$routes->get('/login', 'Auth::login');
$routes->post('/login-process', 'Auth::loginProcess');
$routes->get('/register', 'Auth::register');
$routes->post('/register-process', 'Auth::registerProcess');
$routes->get('/logout', 'Auth::logout');

// =====================================
// ADMIN
// =====================================
$routes->group('admin', ['filter' => ['auth', 'role:admin']], function($routes) {
    $routes->get('/', 'Admin::index');
    $routes->get('dashboard', 'Admin::index');
    $routes->get('bookings', 'Booking::index');
    $routes->get('bookings/delete/(:num)', 'Booking::delete/$1');
    $routes->get('bookings/confirm/(:num)', 'Booking::confirm/$1');
$routes->get('bookings/cancel/(:num)', 'Booking::cancel/$1');
    
    // CRUD Layanan (Disatukan di sini agar rapi)
    $routes->get('services', 'Service::index');
    $routes->get('services/create', 'Service::create');
    $routes->post('services/save', 'Service::save');
    $routes->get('services/delete/(:num)', 'Service::delete/$1');
});

// =====================================
// CUSTOMER ROUTES (Sesuai Layouts/Sidebar Baru)
// =====================================
$routes->group('', ['filter' => ['auth', 'role:customer']], function($routes) {
    
    // 1. Dashboard
    $routes->get('customer', 'Customer::index');
    $routes->get('dashboard', 'Customer::index'); // Dibuka saat klik menu Dashboard

    // 2. Alur Layanan & Form Booking
    $routes->get('services-list', 'Customer::services');    // Dibuka saat klik menu Layanan
    $routes->get('services', 'Customer::services');         // Antisipasi jika diakses manual
    $routes->get('booking/(:num)', 'Customer::booking/$1'); // Form input detail booking
    $routes->post('booking/save', 'Customer::saveBooking'); // Proses simpan data ke database
    
    // 3. Riwayat Booking
    $routes->get('booking-history', 'Customer::bookingHistory'); // Dibuka saat klik menu Booking
    $routes->get('bookings', 'Customer::bookingHistory');        // Cadangan/pintu darurat
});

// =====================================
// STAFF (Opsional)
// =====================================
$routes->group('staff', ['filter' => ['auth', 'role:staff']], function($routes) {
    $routes->get('/', 'Staff::index');        // akses: /staff
    $routes->get('dashboard', 'Staff::index'); // akses: /staff/dashboard (opsional)
});