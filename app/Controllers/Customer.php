<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\OrderModel;

class Customer extends BaseController
{
    /**
     * Halaman Profil Pelanggan, termasuk Riwayat Pembelian.
     * Rute: /profile (Dilindungi oleh filter 'customerauth')
     */
    public function profile()
    {
        $session = session();
        // Pastikan user_id tersedia dari sesi login
        $userId = $session->get('user_id');
        
        $userModel = new UserModel();
        $orderModel = new OrderModel();
        
        // 1. Ambil detail data user dari database
        $user = $userModel->find($userId);

        // 2. Ambil semua riwayat pesanan user ini (MERGE LOGIC)
        $orders = $orderModel->where('user_id', $userId)
                             ->orderBy('created_at', 'DESC') // Pesanan terbaru di atas
                             ->findAll();
        
        $data = [
            'user' => $user, // Data user lengkap
            'orders' => $orders, // Riwayat pesanan
            'title' => 'Profil Pelanggan',
            'apiKey' => getenv('APP_API_KEY')
        ];
        
        
        return view('customer/profile', $data);
    }

}