<?php

namespace Controllers;

require_once __DIR__ . '/../database/connection.php';

use Exception;
use PDO;

class SobatController
{
    private $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    public function index()
    {
        header('Content-Type: application/json');
        $stmt = $this->conn->query("SELECT * FROM tbl_sobat ORDER BY id DESC");
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $data]);
    }

    public function show($id)
    {
        header('Content-Type: application/json');
        $stmt = $this->conn->prepare("SELECT * FROM tbl_sobat WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

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

        try {
            $data = json_decode(file_get_contents("php://input"), true);

            $sql = "INSERT INTO tbl_sobat (
                        nama_sobat, no_wa_sobat, metode_bayar, no_rekening,
                        an_rek, alamat, tanggal, instagram, tiktok, facebook, youtube
                    ) VALUES (
                        :nama_sobat, :no_wa_sobat, :metode_bayar, :no_rekening,
                        :an_rek, :alamat, :tanggal, :instagram, :tiktok, :facebook, :youtube
                    )";

            $stmt = $this->conn->prepare($sql);

            $success = $stmt->execute([
                ':nama_sobat'   => $data['nama_sobat'] ?? '',
                ':no_wa_sobat'  => $data['no_wa_sobat'] ?? '',
                ':metode_bayar' => $data['metode_bayar'] ?? '',
                ':no_rekening'  => $data['no_rekening'] ?? '',
                ':an_rek'       => $data['an_rek'] ?? '',
                ':alamat'       => $data['alamat'] ?? '',
                ':tanggal'      => $data['tanggal'] ?? date('Y-m-d'),
                ':instagram'    => $data['instagram'] ?? '',
                ':tiktok'       => $data['tiktok'] ?? '',
                ':facebook'     => $data['facebook'] ?? '',
                ':youtube'      => $data['youtube'] ?? ''
            ]);

            echo json_encode(['success' => true, 'message' => 'Data berhasil disimpan']);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Gagal menyimpan data', 'error' => $e->getMessage()]);
        }
    }

    public function update($id)
    {
        header('Content-Type: application/json');

        try {
            $data = json_decode(file_get_contents("php://input"), true);

            $stmt = $this->conn->prepare("SELECT * FROM tbl_sobat WHERE id = ?");
            $stmt->execute([$id]);
            $existing = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$existing) {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Data tidak ditemukan']);
                return;
            }

            $sql = "UPDATE tbl_sobat SET
                        nama_sobat = :nama_sobat,
                        no_wa_sobat = :no_wa_sobat,
                        metode_bayar = :metode_bayar,
                        no_rekening = :no_rekening,
                        an_rek = :an_rek,
                        alamat = :alamat,
                        tanggal = :tanggal,
                        instagram = :instagram,
                        tiktok = :tiktok,
                        facebook = :facebook,
                        youtube = :youtube
                    WHERE id = :id";

            $stmt = $this->conn->prepare($sql);

            $success = $stmt->execute([
                ':nama_sobat'   => $data['nama_sobat'] ?? $existing['nama_sobat'],
                ':no_wa_sobat'  => $data['no_wa_sobat'] ?? $existing['no_wa_sobat'],
                ':metode_bayar' => $data['metode_bayar'] ?? $existing['metode_bayar'],
                ':no_rekening'  => $data['no_rekening'] ?? $existing['no_rekening'],
                ':an_rek'       => $data['an_rek'] ?? $existing['an_rek'],
                ':alamat'       => $data['alamat'] ?? $existing['alamat'],
                ':tanggal'      => $data['tanggal'] ?? $existing['tanggal'],
                ':instagram'    => $data['instagram'] ?? $existing['instagram'],
                ':tiktok'       => $data['tiktok'] ?? $existing['tiktok'],
                ':facebook'     => $data['facebook'] ?? $existing['facebook'],
                ':youtube'      => $data['youtube'] ?? $existing['youtube'],
                ':id'           => $id
            ]);

            echo json_encode(['success' => true, 'message' => 'Data berhasil diperbarui']);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Gagal update data', 'error' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        header('Content-Type: application/json');

        $stmt = $this->conn->prepare("SELECT * FROM tbl_sobat WHERE id = ?");
        $stmt->execute([$id]);
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$existing) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Data tidak ditemukan']);
            return;
        }

        $stmt = $this->conn->prepare("DELETE FROM tbl_sobat WHERE id = ?");
        $stmt->execute([$id]);

        echo json_encode(['success' => true, 'message' => 'Data berhasil dihapus']);
    }
}
