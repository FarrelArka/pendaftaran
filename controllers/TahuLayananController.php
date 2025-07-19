<?php

namespace Controllers;

use Models\TahuLayanan;
use Exception;

class TahuLayananController
{
    public function index()
    {
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'data' => TahuLayanan::all()]);
    }

    public function show($id)
    {
        header('Content-Type: application/json');
        $data = TahuLayanan::find($id);
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

    // Cek apakah input berupa array banyak atau hanya satu objek
    $isMulti = array_keys($input) === range(0, count($input) - 1);

    try {
        if ($isMulti) {
            // Validasi tiap item harus punya nama
            foreach ($input as $item) {
                if (!isset($item['nama'])) {
                    throw new Exception("Semua item harus memiliki 'nama'");
                }
            }

            $data = [];
            foreach ($input as $item) {
                $data[] = TahuLayanan::create(['nama' => $item['nama']]);
            }

            echo json_encode(['success' => true, 'data' => $data]);
        } else {
            // Input tunggal
            if (!isset($input['nama'])) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Nama diperlukan']);
                return;
            }

            $data = TahuLayanan::create(['nama' => $input['nama']]);
            echo json_encode(['success' => true, 'data' => $data]);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
    

    public function update($id)
    {
        header('Content-Type: application/json');
        $input = json_decode(file_get_contents("php://input"), true);
        $data = TahuLayanan::find($id);

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
        $data = TahuLayanan::find($id);
        if (!$data) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Data tidak ditemukan']);
            return;
        }

        $data->delete();
        echo json_encode(['success' => true, 'message' => 'Data berhasil dihapus']);
    }
}
