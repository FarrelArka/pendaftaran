<?php

use Bramus\Router\Router;
use Controllers\RegistrasiController;
use Controllers\LayananDigunakanController;
use Controllers\StatusLokasiController;
use Controllers\TahuLayananController;
use Controllers\AlasanController;
use Controllers\SobatController;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

// Handle preflight OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}


$router = new Router();
$controller = new RegistrasiController();
$layananController = new LayananDigunakanController();
$statusLokasi = new StatusLokasiController();
$tahu = new TahuLayananController();
$alasan = new AlasanController();
$sobatController = new SobatController();


// 🔸 Create
$router->post('/registrasi', function () use ($controller) {
  $controller->store();
});

// 🔸 Read (all)
$router->get('/registrasi', function () use ($controller) {
  $controller->index();
});

// 🔸 Read (by ID)
$router->get('/registrasi/(\d+)', function ($id) use ($controller) {
  $controller->show($id);
});

// 🔸 Update
$router->put('/registrasi/update/(\d+)', function ($id) use ($controller) {
  $controller->update($id);
});

// 🔸 Delete
$router->delete('/registrasi/(\d+)', function ($id) use ($controller) {
  $controller->destroy($id);
});


$router->get('/layanan-digunakan', function () use ($layananController) {
    $layananController->index();
});

$router->get('/layanan-digunakan/(\d+)', function ($id) use ($layananController) {
    $layananController->show($id);
});

$router->post('/layanan-digunakan', function () use ($layananController) {
    $layananController->store();
});

$router->put('/layanan-digunakan/update/(\d+)', function ($id) use ($layananController) {
    $layananController->update($id);
});

$router->delete('/layanan-digunakan/(\d+)', function ($id) use ($layananController) {
    $layananController->destroy($id);
});
$router->get('/status-lokasi', function () use ($statusLokasi) {
  $statusLokasi->index();
});

$router->get('/status-lokasi/(\d+)', function ($id) use ($statusLokasi) {
  $statusLokasi->show($id);
});

$router->post('/status-lokasi', function () use ($statusLokasi) {
  $statusLokasi->store();
});

$router->put('/status-lokasi/update/(\d+)', function ($id) use ($statusLokasi) {
  $statusLokasi->update($id);
});

$router->delete('/status-lokasi/(\d+)', function ($id) use ($statusLokasi) {
  $statusLokasi->destroy($id);
});
$router->get('/tahu-layanan', function () use ($tahu) {
  $tahu->index();
});

$router->get('/tahu-layanan/(\d+)', function ($id) use ($tahu) {
  $tahu->show($id);
});

$router->post('/tahu-layanan', function () use ($tahu) {
  $tahu->store();
});

$router->put('/tahu-layanan/update/(\d+)', function ($id) use ($tahu) {
  $tahu->update($id);
});

$router->delete('/tahu-layanan/(\d+)', function ($id) use ($tahu) {
  $tahu->destroy($id);
});
$router->get('/alasan', function () use ($alasan) {
  $alasan->index();
});
$router->get('/alasan/(\d+)', function ($id) use ($alasan) {
  $alasan->show($id);
});
$router->post('/alasan', function () use ($alasan) {
  $alasan->store();
});
$router->put('/alasan/update/(\d+)', function ($id) use ($alasan) {
  $alasan->update($id);
});
$router->delete('/alasan/(\d+)', function ($id) use ($alasan) {
  $alasan->destroy($id);
});
// 🔸 SOBAT CRUD
$router->get('/sobat', function () use ($sobatController) {
  $sobatController->index();
});

$router->get('/sobat/(\d+)', function ($id) use ($sobatController) {
  $sobatController->show($id);
});

$router->post('/sobat', function () use ($sobatController) {
  $sobatController->store();
});

$router->put('/sobat/update/(\d+)', function ($id) use ($sobatController) {
  $sobatController->update($id);
});

$router->delete('/sobat/(\d+)', function ($id) use ($sobatController) {
  $sobatController->destroy($id);
});

// Tambahkan route root agar tidak 404
$router->get('/', function () {
  $content = __DIR__ . '/../../../frontend/home.php';
  include __DIR__ . '/../../../frontend/layouts/template.php';
});


// ✅ Run all routes
$router->run();
