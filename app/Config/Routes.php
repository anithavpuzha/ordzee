<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');

service('auth')->routes($routes);

$routes->get('dashboard', 'Dashboard::index', [
    'filter' => 'session',
]);

// Business registration and dashboard routes
$routes->group('business', ['filter' => 'session'], static function ($routes) {
    $routes->get('register', 'Business::register');
    $routes->post('register', 'Business::store');

    $routes->get('dashboard', 'Business::dashboard');
});

// Daily Menu routes
$routes->group('daily-menu', ['filter' => 'session'], static function ($routes) {
    $routes->get('/', 'DailyMenu::index');
    $routes->get('view/(:num)', 'DailyMenu::view/$1');
    $routes->get('create', 'DailyMenu::create');
    $routes->post('create', 'DailyMenu::store');

    $routes->post('add-item/(:num)', 'DailyMenu::addItem/$1');
    $routes->get('edit-item/(:num)', 'DailyMenu::editItem/$1');
    $routes->post('update-item/(:num)', 'DailyMenu::updateItem/$1');
    $routes->post('toggle-item-availability/(:num)', 'DailyMenu::toggleItemAvailability/$1');
    $routes->post('delete-item/(:num)', 'DailyMenu::deleteItem/$1');
});

// Orders routes
$routes->group('orders', ['filter' => 'session'], static function ($routes) {
    $routes->get('/', 'Orders::index');
    $routes->get('view/(:num)', 'Orders::view/$1');
    $routes->post('accept/(:num)', 'Orders::accept/$1');
    $routes->post('reject/(:num)', 'Orders::reject/$1');
    $routes->post('deliver/(:num)', 'Orders::deliver/$1');

    $routes->post('record-payment/(:num)', 'Orders::recordPayment/$1');
    $routes->post('record-refund/(:num)', 'Orders::recordRefund/$1');
});

// Products routes
$routes->group('products', ['filter' => 'session'], static function ($routes) {
    $routes->get('/', 'Products::index');
    $routes->get('create', 'Products::create');
    $routes->post('create', 'Products::store');

    $routes->get('edit/(:num)', 'Products::edit/$1');
    $routes->post('update/(:num)', 'Products::update/$1');
    $routes->post('toggle-status/(:num)', 'Products::toggleStatus/$1');
});

// Public customer menu route
$routes->get('menu/(:segment)', 'PublicMenu::show/$1');
$routes->post('menu/(:segment)/place-order', 'PublicMenu::placeOrder/$1');
