<?php

namespace Controllers;

use Models\Alasan;
use Exception;

class AlasanController
{
    public function index()
    {
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'data' => Alasan::all()]);
    }

    public function show($id)
    {
        header('Content-Type: application/json');
        $data = Alasan::find($id);
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

    if (!$input) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Input tidak valid']);
        return;
    }

    // Ubah input menjadi array jika hanya kirim satu object
    $items = isset($input[0]) ? $input : [$input];

    $created = [];
    $errors = [];

    foreach ($items as $i => $item) {
        if (!isset($item['nama']) || empty($item['nama'])) {
            $errors[] = "Item ke-$i: Nama diperlukan";
            continue;
        }

        try {
            $data = Alasan::create(['nama' => $item['nama']]);
            $created[] = $data;
        } catch (Exception $e) {
            $errors[] = "Item ke-$i: " . $e->getMessage();
        }
    }

    if (!empty($errors)) {
        http_response_code(207); // 207 Multi-Status (partial success)
        echo json_encode([
            'success' => false,
            'message' => 'Sebagian data gagal disimpan',
            'errors' => $errors,
            'data' => $created
        ]);
        return;
    }

    echo json_encode(['success' => true, 'data' => $created]);
}


    public function update($id)
    {
        header('Content-Type: application/json');
        $input = json_decode(file_get_contents("php://input"), true);
        $data = Alasan::find($id);

        if (!$data) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Data tidak ditemukan']);
            return;
        }

        $data->nama = $input['nama'] ?? $data->nama;
        $data->save();

        echo json_encode(['success' => true, 'data' => $data]);
    }

    public function destroy($id)
    {
        header('Content-Type: application/json');
        $data = Alasan::find($id);
        if (!$data) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Data tidak ditemukan']);
            return;
        }

        $data->delete();
        echo json_encode(['success' => true, 'message' => 'Data berhasil dihapus']);
    }
}
