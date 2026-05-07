<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

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
