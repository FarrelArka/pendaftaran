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

        if (!isset($data['nama'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Field "nama" wajib diisi']);
            return;
        }

        try {
            $new = LayananDigunakan::create([
                'nama' => $data['nama']
            ]);
            echo json_encode(['success' => true, 'data' => $new]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Gagal menyimpan', 'error' => $e->getMessage()]);
        }
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
