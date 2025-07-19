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

        if (!isset($input['nama'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Nama diperlukan']);
            return;
        }

        try {
            $data = Alasan::create(['nama' => $input['nama']]);
            echo json_encode(['success' => true, 'data' => $data]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
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
