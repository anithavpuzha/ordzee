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