<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Parkir');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
//$routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.

// --------------------------------------------------------------------
// OAUTH : Akastra Access
// -------------------------------------------------------------------- 
$routes->group('oauth', function($routes){
    $routes->get('callback', 'Oauth::callback');
});

// --------------------------------------------------------------------
// Core Application Routes
// --------------------------------------------------------------------
$routes->get('/', 'Parkir::index', ['filter' => 'login']);
$routes->get('summary', 'Summary::index');
$routes->post('visit', 'Parkir::visit');

// --------------------------------------------------------------------
// Authentication Routes
// --------------------------------------------------------------------
$routes->get('login', 'Parkir::login');
$routes->get('logout', 'Parkir::logout');
$routes->post('authentication', 'Parkir::authentication');

// --------------------------------------------------------------------
// Parking Management Routes (Protected)
// --------------------------------------------------------------------
$routes->group('parkir', ['filter' => 'login'], static function ($routes) {
    
    // 1. Parking Areas Views (Supports optional Date & SeatID parameters)
    $areas = ['depan', 'stall_bp', 'stall_gr', 'akm'];
    
    foreach ($areas as $area) {
        $routes->get($area,                               "Parkir::{$area}");
        $routes->get("{$area}/(:segment)",                "Parkir::{$area}/$1");
        $routes->get("{$area}/(:segment)/(:segment)",     "Parkir::{$area}/$1/$2");
    }

    // 2. Data Retrieval Actions (AJAX)
    $routes->get('get_history', 'Parkir::get_history');
    $routes->post('get_detail', 'Parkir::get_detail');
    $routes->post('search_car', 'Parkir::search_car');

    // 3. Database Modification Actions
    $routes->post('tambah_parkir', 'Parkir::tambah_parkir');
    $routes->post('update_parkir', 'Parkir::update_parkir');
    $routes->post('update_posisi', 'Parkir::update_posisi');
    $routes->post('delete',        'Parkir::delete_parkir');
});


/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
