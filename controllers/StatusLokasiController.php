<?php

namespace Controllers;

use Models\StatusLokasi;
use Exception;

class StatusLokasiController
{
    public function index()
    {
        header('Content-Type: application/json');
        echo json_encode(StatusLokasi::all());
    }

    public function show($id)
    {
        header('Content-Type: application/json');
        $data = StatusLokasi::find($id);
        if (!$data) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Data tidak ditemukan']);
            return;
        }
        echo json_encode(['success' => true, 'data' => $data]);
    }

    public function store()
{
    header('Content-Type: application/json');
    $input = json_decode(file_get_contents("php://input"), true);

    // Jika input berupa 1 object saja, bungkus ke array
    $items = isset($input[0]) ? $input : [$input];

    $created = [];

    foreach ($items as $data) {
        if (!isset($data['nama'])) continue;

        try {
            $createdItem = StatusLokasi::create(['nama' => $data['nama']]);
            $created[] = $createdItem;
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            return;
        }
    }

    echo json_encode(['success' => true, 'data' => $created]);
}

    public function update($id)
    {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents("php://input"), true);

        $item = StatusLokasi::find($id);
        if (!$item) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Data tidak ditemukan']);
            return;
        }

        $item->update(['nama' => $data['nama'] ?? $item->nama]);
        echo json_encode(['success' => true, 'data' => $item]);
    }

    public function destroy($id)
    {
        header('Content-Type: application/json');
        $item = StatusLokasi::find($id);
        if (!$item) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Data tidak ditemukan']);
            return;
        }

        $item->delete();
        echo json_encode(['success' => true, 'message' => 'Data berhasil dihapus']);
    }
}
