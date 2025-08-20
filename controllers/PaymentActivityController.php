<?php

namespace Controllers;

class PaymentActivityController {
    
    public function getExtendReport() {
        try {
            // Get parameters from request
            $bulan = $_GET['bulan'] ?? date('n');
            $tahun = $_GET['tahun'] ?? date('Y');
            
            // Validate parameters
            if (!is_numeric($bulan) || $bulan < 1 || $bulan > 12) {
                http_response_code(400);
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Parameter bulan tidak valid (1-12)'
                ]);
                return;
            }
            
            if (!is_numeric($tahun) || $tahun < 2020 || $tahun > 2030) {
                http_response_code(400);
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Parameter tahun tidak valid (2020-2030)'
                ]);
                return;
            }
            
            // Build API URL
            $apiUrl = "https://wo.naraya.co.id/beta/api/v1/extend_report/?bulan={$bulan}&tahun={$tahun}";
            
            // Initialize cURL
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $apiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Kapten Naratel Payment Activity/1.0');
            
            // Execute request
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);
            
            // Check for cURL errors
            if ($error) {
                http_response_code(500);
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Gagal mengakses API eksternal: ' . $error
                ]);
                return;
            }
            
            // Check HTTP status
            if ($httpCode !== 200) {
                http_response_code($httpCode);
                echo json_encode([
                    'status' => 'error',
                    'message' => 'API eksternal mengembalikan error: HTTP ' . $httpCode,
                    'http_code' => $httpCode
                ]);
                return;
            }
            
            // Validate JSON response
            $data = json_decode($response, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                http_response_code(500);
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Response API tidak valid (bukan JSON yang benar)'
                ]);
                return;
            }
            
            // Add timestamp for caching info
            $data['fetched_at'] = date('Y-m-d H:i:s');
            $data['proxy_status'] = 'success';
            
            // Return the proxied data
            http_response_code(200);
            echo json_encode($data);
            
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'Terjadi kesalahan server: ' . $e->getMessage()
            ]);
        }
    }
}
