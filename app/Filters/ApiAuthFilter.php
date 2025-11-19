<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class ApiAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // 1. Ambil API Key dari header permintaan (X-API-KEY)
        $apiKeyHeader = $request->getHeaderLine('X-API-KEY');
        
        // 2. Ambil API Key yang diharapkan dari .env
        $expectedApiKey = getenv('APP_API_KEY');

        // Pengecekan Kunci:
        // Cek jika kunci kosong ATAU kunci yang dikirim TIDAK SAMA dengan kunci yang diharapkan
        if (empty($apiKeyHeader) || $apiKeyHeader !== $expectedApiKey) {
            
            // Buat response 401 Unauthorized
            $response = service('response');
            $response->setStatusCode(401); 
            $response->setJSON([
                'status' => 'error',
                'message' => 'Akses ditolak. X-API-KEY tidak valid atau tidak tersedia.'
            ]);
            
            // Hentikan eksekusi dan kirim response 401
            return $response; 
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak ada yang dilakukan setelah permintaan API
    }
}