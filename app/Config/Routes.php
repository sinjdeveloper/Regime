<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'HomeController::index');
$routes->get('/login', 'AdminController::loginPage');
$routes->post('admin/login', 'AuthController::loginAdmin');
$routes->get('/logout','AuthController::logout');
$routes->group('admin', ['filter' => 'role:admin'], function ($routes) {
    $routes->get(
        'dashboard',
        'AdminController::index'
    );
    $routes->group('regimes', function ($routes) {
        $routes->post('store', 'RegimeController::create');
        $routes->post('delete/(:num)','RegimeController::delete/$1');   
    });
});

// API Routes - Profile & Goals
$routes->get('api/client/profile', 'ClientController::getProfilePopup');
$routes->get('api/objectif/popup', 'ObjectifController::showPopup');
$routes->post('api/objectif/update', 'ObjectifController::updateGoal');

// API Routes - Suggestions & Meals
$routes->get('api/suggestion/client/(:num)', 'SuggestionController::getSuggestionsByClient/$1');

// API Routes - Gold Subscription
$routes->post('api/gold/subscribe', 'GoldController::subscribe');

// API Routes - Wallet & Codes
$routes->get('api/wallet/code-popup', 'WalletController::showCodePopup');
$routes->post('api/wallet/redeem-code', 'WalletController::redeemCode');
