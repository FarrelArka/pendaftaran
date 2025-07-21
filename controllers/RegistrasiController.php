<?php

namespace Controllers;

use Models\Registrasi as RegistrasiModel;
use Exception;

require_once __DIR__ . '/../database/connection.php';

class RegistrasiController
{
    public function store()
    {
        header('Content-Type: application/json');

        try {
            $data = $_POST;

            $uploadDir = __DIR__ . '/../uploads/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            $foto_ktp = $this->uploadFile('foto_ktp', $uploadDir);
            $foto_rmh = $this->uploadFile('foto_rmh', $uploadDir);
            $foto_kk  = $this->uploadFile('foto_kk', $uploadDir);

            $tanggal = isset($data['tanggal']) && trim($data['tanggal']) !== ''
                ? $data['tanggal']
                : date('Y-m-d H:i:s');

            $registrasi = RegistrasiModel::create([
                'mgm_id'               => $data['mgm_id'] ?? null,
                'jenis_daf_id'         => $data['jenis_daf_id'] ?? null,
                'nama_lengkap'         => $data['nama_lengkap'] ?? '',
                'whatsapp'             => $data['whatsapp'] ?? '{}',
                'alamat'               => $data['alamat'] ?? '',
                'rt'                   => $data['rt'] ?? 0,
                'rw'                   => $data['rw'] ?? 0,
                'provinsi_id'          => $data['provinsi_id'] ?? 0,
                'kabupaten_id'         => $data['kabupaten_id'] ?? 0,
                'kecamatan_id'         => $data['kecamatan_id'] ?? 0,
                'kelurahan_id'         => $data['kelurahan_id'] ?? 0,
                'patokan'              => $data['patokan'] ?? '',
                'nik'                  => $data['nik'] ?? '',
                'foto_ktp'             => $foto_ktp,
                'foto_rmh'             => $foto_rmh,
                'foto_kk'              => $foto_kk,
                'status_lokasi_id'     => $data['status_lokasi_id'] ?? 0,
                'produk_id'            => $data['produk_id'] ?? 0,
                'tahu_layanan_id'      => $data['tahu_layanan_id'] ?? 0,
                'alasan_id'            => $data['alasan_id'] ?? 0,
                'layanan_digunakan_id' => $data['layanan_digunakan_id'] ?? 0,
                'unit_id'              => strval($data['unit_id'] ?? ''),
                'tanggal'              => $tanggal,
                'pegawai_id'           => strval($data['pegawai_id'] ?? ''),
                'stts_create'          => $data['stts_create'] ?? 'n',
                'stts_ver'             => $data['stts_ver'] ?? 'n',
                'longlat'              => $data['longlat'] ?? '',
                'userid_app'           => $data['userid_app'] ?? null,
                'order_id'             => $data['order_id'] ?? null,
            ]);

            echo json_encode(['success' => true, 'data' => $registrasi]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Gagal menyimpan data.',
                'error'   => $e->getMessage()
            ]);
        }
    }

    private function uploadFile($fieldName, $uploadDir)
    {
        if (isset($_FILES[$fieldName]) && $_FILES[$fieldName]['error'] === UPLOAD_ERR_OK) {
            $tmpName = $_FILES[$fieldName]['tmp_name'];
            $name = time() . '_' . basename($_FILES[$fieldName]['name']);
            move_uploaded_file($tmpName, $uploadDir . $name);
            return 'uploads/' . $name;
        }
        return null;
    }

    public function index()
    {
        header('Content-Type: application/json');
        $data = RegistrasiModel::all();
        echo json_encode($data);
    }

    public function show($id)
    {
        header('Content-Type: application/json');
        $data = RegistrasiModel::find($id);
        if (!$data) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Data tidak ditemukan']);
            return;
        }
        echo json_encode(['success' => true, 'data' => $data]);
    }

    public function update($id)
{
    header('Content-Type: application/json');
    try {
        $data = $_POST;
        $registrasi = RegistrasiModel::find($id);

        if (!$registrasi) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Data tidak ditemukan']);
            return;
        }

        // Update hanya 5 kolom ini
        $registrasi->update([
            'nama_lengkap' => $data['nama_lengkap'] ?? $registrasi->nama_lengkap,
            'whatsapp'     => $data['whatsapp'] ?? $registrasi->whatsapp,
            'alamat'       => $data['alamat'] ?? $registrasi->alamat,
            'stts_create'  => $data['stts_create'] ?? $registrasi->stts_create,
            'unit_id'      => $data['unit_id'] ?? $registrasi->unit_id,
        ]);

        echo json_encode(['success' => true, 'data' => $registrasi]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Gagal update data',
            'error'   => $e->getMessage()
        ]);
    }
}


    public function destroy($id)
    {
        header('Content-Type: application/json');
        $data = RegistrasiModel::find($id);
        if (!$data) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Data    tidak ditemukan']);
            return;
        }
        $data->delete();
        echo json_encode(['success' => true, 'message' => 'Data berhasil dihapus']);
    }
}
