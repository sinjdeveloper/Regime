<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/login', 'AdminController::loginPage');
$routes->post('admin/login', 'AuthController::loginAdmin');
$routes->get('/logout','AuthController::logout');
$routes->group('admin', ['filter' => 'role:admin'], function ($routes) {
    $routes->get(
        'dashboard',
        'AdminController::index'
    );
});