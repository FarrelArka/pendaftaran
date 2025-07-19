<?php

namespace Controllers;

use Models\LayananDigunakan;
use Exception;

class LayananDigunakanController
{
    public function index()
    {
        header('Content-Type: application/json');
        $data = LayananDigunakan::all();
        echo json_encode(['success' => true, 'data' => $data]);
    }

    public function show($id)
    {
        header('Content-Type: application/json');
        $data = LayananDigunakan::find($id);
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
    $data = json_decode(file_get_contents("php://input"), true);

    if (!$data) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Input tidak valid']);
        return;
    }

    // Cek apakah data array atau single object
    $items = isset($data[0]) ? $data : [$data];

    $created = [];
    $errors = [];

    foreach ($items as $i => $item) {
        if (!isset($item['nama']) || empty($item['nama'])) {
            $errors[] = "Item ke-$i: Field 'nama' wajib diisi";
            continue;
        }

        try {
            $new = LayananDigunakan::create(['nama' => $item['nama']]);
            $created[] = $new;
        } catch (Exception $e) {
            $errors[] = "Item ke-$i: " . $e->getMessage();
        }
    }

    if (!empty($errors)) {
        http_response_code(207); // Multi-Status
        echo json_encode([
            'success' => false,
            'message' => 'Sebagian data gagal disimpan',
            'data' => $created,
            'errors' => $errors
        ]);
        return;
    }

    echo json_encode(['success' => true, 'data' => $created]);
}


    public function update($id)
    {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents("php://input"), true);

        $item = LayananDigunakan::find($id);
        if (!$item) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Data tidak ditemukan']);
            return;
        }

        try {
            $item->update([
                'nama' => $data['nama'] ?? $item->nama
            ]);
            echo json_encode(['success' => true, 'data' => $item]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Gagal update', 'error' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        header('Content-Type: application/json');
        $item = LayananDigunakan::find($id);
        if (!$item) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Data tidak ditemukan']);
            return;
        }

        $item->delete();
        echo json_encode(['success' => true, 'message' => 'Data berhasil dihapus']);
    }
}
