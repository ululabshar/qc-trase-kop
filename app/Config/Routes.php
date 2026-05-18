<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'TraseController::login');
$routes->get('login', 'TraseController::login');
$routes->post('login/proses', 'TraseController::prosesLogin');
$routes->get('logout', 'TraseController::logout');

// Route khusus Operator
$routes->get('input', 'TraseController::inputHalaman');
$routes->post('trase/simpan', 'TraseController::simpanTrase');
$routes->get('trase/hapus/(:num)', 'TraseController::hapusTrase/$1');
$routes->get('trase/cetak-ulang/(:num)', 'TraseController::cetakUlang/$1');


// Route khusus Kontrol Kendali Batch
$routes->post('batch/buka', 'TraseController::bukaBatch');
$routes->get('batch/tutup/(:num)', 'TraseController::tutupBatch/$1');

// Route khusus Admin Dashboard Global
$routes->get('admin', 'AdminController::index');

// Proses simpan & hitung otomatis 3 komponen PROP gudang
$routes->post('lot/simpan', 'AdminController::simpanLot');

// Proses hapus baris rekap LOT gudang kantor
$routes->get('lot/hapus/(:num)', 'AdminController::hapusLot/$1');

// Route Layar Monitor TV & API Realtime
$routes->get('monitor', 'TraseController::monitorHalaman');
$routes->get('trase/api-data', 'TraseController::getLatestData');
$routes->get('admin/export-excel', 'AdminController::exportExcel');