<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'HomeController::index');
$routes->get('/login', 'UserController::loginPage');
$routes->post('/login', 'UserController::login');
$routes->get('/admin/login', 'AdminController::loginPage');
$routes->post('/admin/login', 'AuthController::loginAdmin');
$routes->get('/user/login', 'UserController::loginPage');
$routes->post('/user/login', 'UserController::login');
$routes->get('/logout', 'AuthController::logout');
$routes->get('/logout','AuthController::logout');

// Client Dashboard
$routes->get('/dashboard', 'ClientController::dashboard');

// Pages fonctionnalités (utilisateur connecté)
$routes->group('', ['filter' => 'auth'], function ($routes) {
    $routes->get('/imc', 'ClientController::imc');
    $routes->get('/suivi', 'ClientController::suivi');
    $routes->get('/gold', 'ClientController::gold');
    $routes->get('/wallet', 'ClientController::wallet');

    $routes->get('/profile', 'ClientController::profile');

    $routes->get('/programs/regime/(:num)', 'ProgramController::regime/$1');
    $routes->get('/programs/sport/(:num)', 'ProgramController::sport/$1');
});

$routes->group('admin', ['filter' => 'role:admin'], function ($routes) {
    $routes->get('infos','DashboardController::index');
    $routes->get(
        'dashboard',
        'AdminController::index'
    );
    $routes->group('regimes', function ($routes) {
        $routes->post('store', 'RegimeController::create');
        $routes->post('update/(:num)', 'RegimeController::update/$1');
        $routes->post('delete/(:num)', 'RegimeController::delete/$1');
    });
    $routes->group('sports', function ($routes) {
        $routes->get('index', 'AdminController::sportIndex');
        $routes->post('store', 'AdminController::createSport');
        $routes->post('update/(:num)', 'AdminController::updateSport/$1');
        $routes->post('delete/(:num)', 'AdminController::deleteSport/$1');
    });
    $routes->group('api', function ($routes) {
        $routes->get('repartition','DashboardController::getRepartition');
        $routes->get('gold','DashboardController::getRepartitionGold');
        $routes->get('regimes','DashboardController::getPopularRegimes');
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
$routes->get('/wallet','');
$routes->post('/wallet/redeem-code', 'WalletController::redeemCode');

$routes->get('/signup', 'AuthController::showSignup');
$routes->post('/signup/user', 'AuthController::storeUserInfo');

$routes->get('/signup/health', 'AuthController::showHealthForm');
$routes->post('/signup/health', 'AuthController::storeHealthInfo');

$routes->get('/signup/goals', 'ObjectifController::showSelection');
// $routes->post('/signup/goals', 'ObjectifController::storeGoals');

$routes->post('/signup/complete', 'AuthController::completeSignup');
