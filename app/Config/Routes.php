<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Rute Halaman Utama
$routes->get('/', 'Shop::index');

// --- 1. Rute Auth (Login/Register Terpadu) ---
$routes->get('/register', 'Auth::register'); 
$routes->post('/register/process', 'Auth::processRegister'); 

$routes->get('/login', 'Auth::login'); 
$routes->post('/login/process', 'Auth::processLogin'); 

$routes->get('/logout', 'Auth::logout');

// --- 2. Rute Pelanggan Terproteksi (Wajib Login Pelanggan) ---
$routes->group('/', ['filter' => 'customerauth'], function($routes) {
    
    // Rute Pembelian
    $routes->get('buy/(:num)', 'Shop::buy/$1'); 
    $routes->post('process-order', 'Shop::processOrder');
    
    // Rute Profil Pelanggan
    $routes->get('profile', 'Customer::profile');
});


// --- 3. Rute Admin Group (Wajib Login Admin) ---
$routes->group('admin', ['filter' => 'auth'], function($routes) {
    
    // Dashboard Admin
    $routes->get('dashboard', 'Admin::dashboard');
    
    // CRUD Produk (Mengarah ke method di Admin.php)
    $routes->post('produk/store', 'Admin::saveProduct');
    $routes->get('produk/edit/(:num)', 'Admin::editProduct/$1');
    $routes->post('produk/update/(:num)', 'Admin::updateProduct/$1'); // Untuk proses POST
    $routes->get('produk/delete/(:num)', 'Admin::deleteProduct/$1');
    
    // BARU: Manajemen Pengguna (Mengarah ke Admin::indexUsers)
    $routes->get('users', 'Admin::indexUsers');

    // BARU: Manajemen Pesanan (Mengarah ke Admin::indexOrders)
    $routes->get('orders', 'Admin::indexOrders');
});

// --- 4. Rute API Group (Dilindungi oleh X-API-KEY) ---
$routes->group('api', ['filter' => 'api_auth'], function($routes) { 
    
    // A. GET /api/order?user_id=X
    $routes->get('order', 'Api\OrderController::getOrders'); 
    
    // B. POST /api/user/update-contact
   $routes->post('user/update-contact', 'Api\UserController::updateContact'); 
});