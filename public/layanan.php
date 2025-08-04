<?php

require_once __DIR__ . '/../vendor/autoload.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Aktifkan CORS agar frontend bisa akses
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Gunakan controller dengan eager loading
use Controllers\LayananDigunakanController;

try {
    $controller = new LayananDigunakanController();
    
    // Cek method request
    $method = $_SERVER['REQUEST_METHOD'];
    
    switch ($method) {
        case 'GET':
            if (isset($_GET['id'])) {
                $controller->show($_GET['id']);
            } else {
                $controller->index();
            }
            break;
        case 'POST':
            $controller->store();
            break;
        case 'PUT':
        case 'PATCH':
            if (isset($_GET['id'])) {
                $controller->update($_GET['id']);
            } else {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'ID tidak ditemukan']);
            }
            break;
        case 'DELETE':
            if (isset($_GET['id'])) {
                $controller->destroy($_GET['id']);
            } else {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'ID tidak ditemukan']);
            }
            break;
        default:
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method tidak diizinkan']);
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Terjadi kesalahan',
        'error' => $e->getMessage()
    ]);
}
