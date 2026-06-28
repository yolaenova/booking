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
    
    // Fitur Booking & Scan WhatsApp
    $routes->get('bookings', 'Booking::index');
    $routes->get('bookings/delete/(:num)', 'Booking::delete/$1');
    $routes->get('bookings/confirm/(:num)', 'Booking::confirm/$1');
    $routes->get('bookings/cancel/(:num)', 'Booking::cancel/$1');
    $routes->get('bookings/create', 'Booking::create');
    $routes->post('bookings/save', 'Booking::save');
    
    // 🌟 RUTE SCAN & RESET WHATSAPP (Aman di dalam grup admin)
    $routes->get('scan-wa', 'Booking::show_qr');
    $routes->get('reset-wa', 'Booking::reset_wa'); // Selesai ditambahkan di sini!
    
    // CRUD Layanan
    $routes->get('services', 'Service::index');
    $routes->get('services/create', 'Service::create');
    $routes->post('services/save', 'Service::save');
    $routes->get('services/delete/(:num)', 'Service::delete/$1');

    // Komponen 5: Konsumsi API WAHA
    $routes->get('whatsapp', 'Admin::whatsapp');
});

// =====================================
// CUSTOMER ROUTES
// =====================================
$routes->group('', ['filter' => ['auth', 'role:customer']], function($routes) {
    // 1. Dashboard
    $routes->get('customer', 'Customer::index');
    $routes->get('dashboard', 'Customer::index');

    // 2. Alur Layanan & Form Booking
    $routes->get('services-list', 'Customer::services');    
    $routes->get('services', 'Customer::services');         
    $routes->get('booking/(:num)', 'Customer::booking/$1'); 
    $routes->post('booking/save', 'Customer::saveBooking'); 
    
    // 3. Riwayat Booking
    $routes->get('booking-history', 'Customer::bookingHistory'); 
    $routes->get('bookings', 'Customer::bookingHistory');        
});

// =====================================
// STAFF (Opsional)
// =====================================
$routes->group('staff', ['filter' => ['auth', 'role:staff']], function($routes) {
    $routes->get('/', 'Staff::index');        
    $routes->get('dashboard', 'Staff::index'); 
});

// =====================================
// EXPOSE API ENDPOINT (KOMPONEN 6)
// =====================================
$routes->get('api/summary', 'ApiController::getDashboardSummary');
$routes->post('api/whatsapp/callback', 'ApiController::whatsappCallback');