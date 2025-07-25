<?php
// 🔧 Middleware CORS Global
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
$allowed_origins = [
    'http://127.0.0.1:5500',
    'http://localhost:5500'
];

if (in_array($origin, $allowed_origins)) {
    header("Access-Control-Allow-Origin: $origin");
}
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}



use Bramus\Router\Router;
use Controllers\UserController;
use Controllers\RegistrasiController;
use Controllers\LayananDigunakanController;
use Controllers\StatusLokasiController;
use Controllers\TahuLayananController;
use Controllers\AlasanController;
use Controllers\SobatController;


$router = new Router();
$userController = new UserController();
$controller = new RegistrasiController();
$layananController = new LayananDigunakanController();
$statusLokasi = new StatusLokasiController();
$tahu = new TahuLayananController();
$alasan = new AlasanController();
$sobatController = new SobatController();

$router->post('/api/register', function() use ($userController) {
    $userController->register();
});

$router->post('/api/login', function() use ($userController) {
    $userController->login();
});

$router->get('/api/logout', function() use ($userController) {
    $userController->logout();
});

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
$router->post('/registrasi/update/(\d+)', function ($id) use ($controller) {
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

$router->put('/layanan-digunakan/(\d+)', function ($id) use ($layananController) {
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

$router->put('/status-lokasi/(\d+)', function ($id) use ($statusLokasi) {
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

$router->put('/tahu-layanan/(\d+)', function ($id) use ($tahu) {
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
$router->put('/alasan/(\d+)', function ($id) use ($alasan) {
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

$router->post('/sobat/(\d+)/update', function ($id) use ($sobatController) {
  $sobatController->update($id);
});

$router->delete('/sobat/(\d+)', function ($id) use ($sobatController) {
  $sobatController->destroy($id);
});

// ✅ Run all routes
$router->run();
