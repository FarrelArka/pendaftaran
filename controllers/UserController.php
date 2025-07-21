<?php

namespace Controllers;

use Models\User;
use Exception;

class UserController
{
    public function register()
    {
        header('Content-Type: application/json');
        $data = $_POST;

        try {
            // Validasi sederhana
            if (empty($data['username']) || empty($data['password'])) {
                throw new Exception('Username dan Password wajib diisi');
            }

            // Cek username sudah ada
            if (User::where('username', $data['username'])->exists()) {
                throw new Exception('Username sudah digunakan');
            }

            // Simpan user baru
            $user = User::create([
                'username' => $data['username'],
                'password' => password_hash($data['password'], PASSWORD_BCRYPT),
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => null
            ]);

            echo json_encode(['success' => true, 'message' => 'Registrasi berhasil', 'user' => $user]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function login()
{
    header('Content-Type: application/json');
    session_start(); // 🔥 Mulai sesi

    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['username'], $data['password'])) {
        http_response_code(400);
        echo json_encode(['message' => 'Username dan password wajib diisi']);
        return;
    }

    $user = User::where('username', $data['username'])->first();

    if ($user && password_verify($data['password'], $user->password)) {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['username'] = $user->username;

        echo json_encode(['message' => 'Login berhasil', 'user' => $user]);
    } else {
        http_response_code(401);
        echo json_encode(['message' => 'Login gagal']);
    }
}


    public function logout()
    {
        session_destroy();
        echo json_encode(['success' => true, 'message' => 'Logout berhasil']);
    }
}
