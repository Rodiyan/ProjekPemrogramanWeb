<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\OrderModel;

class Shop extends BaseController
{
    // Halaman Depan (Katalog)
    public function index()
    {
        $productModel = new ProductModel();
        $data = [
            'products' => $productModel->findAll(),
            'title' => 'Toko Komputer - Katalog'
        ];
        return view('shop/catalog', $data);
    }

    // Halaman Konfirmasi Beli
    public function buy($id)
    {
        $model = new ProductModel();
        $product = $model->find($id);
        
        // 1. Cek produk ada atau tidak
        if (!$product) {
            return redirect()->to('/')->with('error', 'Produk tidak ditemukan atau sudah dihapus.');
        }

        // 2. Cek stok produk
        if ($product['stock'] <= 0) {
            return redirect()->to('/')->with('error', 'Stok produk ini sedang kosong.');
        }

        $data = [
            'product' => $product,
            'title' => 'Checkout: ' . $product['name'],
            'session' => session()
        ];
        
        // Catatan: Menggunakan 'shop/checkout' sesuai referensi Anda
        return view('shop/checkout', $data); 
    }

    // Proses Simpan Pesanan (LENGKAP dengan validasi dan update stok)
    public function processOrder()
    {
        $session = session();
        $orderModel = new OrderModel();
        $productModel = new ProductModel();
        
        $id_produk = $this->request->getPost('product_id');
        // Gunakan 'quantity' sebagai nama input QTY yang lebih umum (asumsi disesuaikan di view)
        $qty = (int)$this->request->getPost('quantity'); 
        
        $user_id = $session->get('user_id');
        
        // --- 1. Validasi Dasar & Keamanan ---
        if ($qty <= 0) {
            return redirect()->back()->withInput()->with('error', 'Jumlah pembelian harus minimal 1.');
        }
        
        // Ambil data produk & user dari DB/Session untuk keamanan & kelengkapan
        $product = $productModel->find($id_produk);
        $customer_name = $session->get('name');

        if (!$product || $user_id === null) {
            return redirect()->to('/')->with('error', 'Gagal memproses pesanan. Sesi atau produk tidak valid.');
        }

        // --- 2. Validasi Stok ---
        if ($qty > $product['stock']) {
            return redirect()->back()->withInput()->with('error', 'Stok tidak mencukupi. Stok tersedia: ' . $product['stock']);
        }

        // --- 3. Kalkulasi & Update Stok Produk ---
        $unit_price = $product['price']; // Harga diambil dari DB
        $total_price = $unit_price * $qty;
        $new_stock = $product['stock'] - $qty;

        // Kurangi Stok di database
        $productModel->update($id_produk, [
            'stock' => $new_stock
        ]);

        // --- 4. Simpan Data Order ---
        $orderModel->save([
            'user_id' => $user_id,
            'customer_name' => $customer_name, // Data pelanggan untuk Admin
            'product_id' => $id_produk,
            'product_name' => $product['name'], // Nama produk untuk riwayat
            'qty' => $qty,
            'unit_price' => $unit_price, 
            'total_price' => $total_price,
            'status' => 'Pending', // Status awal
        ]);

        // --- 5. Redirect Sukses ke Halaman Profil untuk cek riwayat ---
        return redirect()->to('/profile')->with('success', 'Pesanan **' . $product['name'] . '** berhasil dibuat! Silakan cek riwayat pesanan Anda.');
    }
}