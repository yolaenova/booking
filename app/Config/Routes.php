<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// =====================================
// DEFAULT
// =====================================
$routes->get('/', 'Auth::login');


// =====================================
// AUTH
// =====================================
$routes->get('/login', 'Auth::login');
$routes->post('/login-process', 'Auth::loginProcess');

$routes->get('/register', 'Auth::register');
$routes->post('/register-process', 'Auth::registerProcess');

$routes->get('/logout', 'Auth::logout');


// =====================================
// ADMIN
// =====================================
$routes->get('/admin', 'Admin::index', [
    'filter' => ['auth', 'role:admin']
]);

$routes->get('/bookings', 'Booking::index', [
    'filter' => ['auth', 'role:admin']
]);

$routes->get('/services', 'Service::index', [
    'filter' => ['auth', 'role:admin']
]);

$routes->get('/services/create', 'Service::create', [
    'filter' => ['auth', 'role:admin']
]);

$routes->post('/services/store', 'Service::store', [
    'filter' => ['auth', 'role:admin']
]);

$routes->get('/services/delete/(:num)', 'Service::delete/$1', [
    'filter' => ['auth', 'role:admin']
]);


// =====================================
// CUSTOMER
// =====================================

// dashboard customer
$routes->get('/customer', 'Customer::index', [
    'filter' => ['auth', 'role:customer']
]);

$routes->get('/dashboard', 'Customer::index', [
    'filter' => ['auth', 'role:customer']
]);

// list layanan
$routes->get('/services-list', 'Customer::services', [
    'filter' => ['auth', 'role:customer']
]);

// form booking
$routes->get('/booking/(:num)', 'Customer::booking/$1', [
    'filter' => ['auth', 'role:customer']
]);

// simpan booking
$routes->post('/booking/save', 'Customer::saveBooking', [
    'filter' => ['auth', 'role:customer']
]);


// =====================================
// STAFF
// =====================================
$routes->get('/staff', 'Staff::index', [
    'filter' => ['auth', 'role:staff']
]);