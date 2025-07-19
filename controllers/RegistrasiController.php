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

            $uploadDir = __DIR__ . '/../uploads/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            $foto_ktp = $this->uploadFile('foto_ktp', $uploadDir) ?? $registrasi->foto_ktp;
            $foto_rmh = $this->uploadFile('foto_rmh', $uploadDir) ?? $registrasi->foto_rmh;
            $foto_kk  = $this->uploadFile('foto_kk', $uploadDir) ?? $registrasi->foto_kk;

            $tanggal = isset($data['tanggal']) && trim($data['tanggal']) !== ''
                ? $data['tanggal']
                : $registrasi->tanggal;

            $registrasi->update([
                'mgm_id'               => $data['mgm_id'] ?? $registrasi->mgm_id,
                'jenis_daf_id'         => $data['jenis_daf_id'] ?? $registrasi->jenis_daf_id,
                'nama_lengkap'         => $data['nama_lengkap'] ?? $registrasi->nama_lengkap,
                'whatsapp'             => $data['whatsapp'] ?? $registrasi->whatsapp,
                'alamat'               => $data['alamat'] ?? $registrasi->alamat,
                'rt'                   => $data['rt'] ?? $registrasi->rt,
                'rw'                   => $data['rw'] ?? $registrasi->rw,
                'provinsi_id'          => $data['provinsi_id'] ?? $registrasi->provinsi_id,
                'kabupaten_id'         => $data['kabupaten_id'] ?? $registrasi->kabupaten_id,
                'kecamatan_id'         => $data['kecamatan_id'] ?? $registrasi->kecamatan_id,
                'kelurahan_id'         => $data['kelurahan_id'] ?? $registrasi->kelurahan_id,
                'patokan'              => $data['patokan'] ?? $registrasi->patokan,
                'nik'                  => $data['nik'] ?? $registrasi->nik,
                'foto_ktp'             => $foto_ktp,
                'foto_rmh'             => $foto_rmh,
                'foto_kk'              => $foto_kk,
                'status_lokasi_id'     => $data['status_lokasi_id'] ?? $registrasi->status_lokasi_id,
                'produk_id'            => $data['produk_id'] ?? $registrasi->produk_id,
                'tahu_layanan_id'      => $data['tahu_layanan_id'] ?? $registrasi->tahu_layanan_id,
                'alasan_id'            => $data['alasan_id'] ?? $registrasi->alasan_id,
                'layanan_digunakan_id' => $data['layanan_digunakan_id'] ?? $registrasi->layanan_digunakan_id,
                'unit_id'              => $data['unit_id'] ?? $registrasi->unit_id,
                'tanggal'              => $tanggal,
                'pegawai_id'           => $data['pegawai_id'] ?? $registrasi->pegawai_id,
                'stts_create'          => $data['stts_create'] ?? $registrasi->stts_create,
                'stts_ver'             => $data['stts_ver'] ?? $registrasi->stts_ver,
                'longlat'              => $data['longlat'] ?? $registrasi->longlat,
                'userid_app'           => $data['userid_app'] ?? $registrasi->userid_app,
                'order_id'             => $data['order_id'] ?? $registrasi->order_id,
            ]);

            echo json_encode(['success' => true, 'data' => $registrasi]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Gagal update data', 'error' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        header('Content-Type: application/json');
        $data = RegistrasiModel::find($id);
        if (!$data) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Data tidak ditemukan']);
            return;
        }
        $data->delete();
        echo json_encode(['success' => true, 'message' => 'Data berhasil dihapus']);
    }
}
