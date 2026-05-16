<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// --- ROUTING UNTUK AUTENTIKASI (LOGIN & LOGOUT) ---
$routes->get('/', 'TraseController::login');
$routes->get('login', 'TraseController::login');
$routes->post('login/proses', 'TraseController::prosesLogin');
$routes->get('logout', 'TraseController::logout');

// --- ROUTING UNTUK AKUN OPERATOR (INPUT DATA) ---
$routes->get('input', 'TraseController::inputHalaman');
$routes->post('batch/buka', 'TraseController::bukaBatch');
$routes->get('batch/tutup/(:num)', 'TraseController::tutupBatch/$1');
$routes->post('trase/simpan', 'TraseController::simpanTrase');

// --- ROUTING UNTUK AKUN MONITOR (MONITORING 24 JAM) ---
$routes->get('monitor', 'TraseController::monitorHalaman');
$routes->get('trase/api-data', 'TraseController::getLatestData');