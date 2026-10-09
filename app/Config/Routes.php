<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->setDefaultNamespace('App\\Controllers');
$routes->setDefaultController('AuthController');
$routes->setDefaultMethod('login');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

$routes->get('/', 'AuthController::login');
$routes->match(['get', 'post'], 'login', 'AuthController::login');
$routes->post('logout', 'AuthController::logout');

$routes->group('', ['filter' => 'auth'], static function ($routes): void {
    $routes->get('dashboard', 'DashboardController::index');

    $routes->get('products', 'ProductsController::index');
    $routes->get('products/new', 'ProductsController::new');
    $routes->post('products', 'ProductsController::create');
    $routes->get('products/(:num)/edit', 'ProductsController::edit/$1');
    $routes->post('products/(:num)', 'ProductsController::update/$1');
    $routes->post('products/(:num)/archive', 'ProductsController::archive/$1');
    $routes->post('products/(:num)/restore', 'ProductsController::restore/$1');

    $routes->get('customers', 'CustomersController::index');
    $routes->get('customers/new', 'CustomersController::new');
    $routes->post('customers', 'CustomersController::create');
    $routes->get('customers/(:num)/edit', 'CustomersController::edit/$1');
    $routes->post('customers/(:num)', 'CustomersController::update/$1');
    $routes->post('customers/(:num)/delete', 'CustomersController::delete/$1');

    $routes->get('staff', 'StaffController::index');
    $routes->get('staff/new', 'StaffController::new');
    $routes->post('staff', 'StaffController::create');
    $routes->get('staff/(:num)/edit', 'StaffController::edit/$1');
    $routes->post('staff/(:num)', 'StaffController::update/$1');
    $routes->post('staff/(:num)/delete', 'StaffController::delete/$1');

    $routes->get('sales/new', 'SalesController::new');
    $routes->post('sales', 'SalesController::create');
    $routes->get('sales', 'SalesController::index');
});
