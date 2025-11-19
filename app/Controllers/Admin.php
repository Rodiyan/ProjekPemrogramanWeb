<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\OrderModel;
use App\Models\UserModel;

class Admin extends BaseController
{
    // HAPUS SEMUA FUNGSI LOGIN/AUTH/LOGOUT karena sudah di Controller Auth.php
    
    // --- 1. DASHBOARD (READ ALL) ---
    public function dashboard()
    {
        $productModel = new ProductModel();
        $orderModel = new OrderModel();
        
        $data = [
            'products' => $productModel->findAll(), // Semua produk
            'orders'   => $orderModel->findAll()  // Semua pesanan
        ];
        
        return view('admin/dashboard', $data);
    }

    // --- 2. CREATE PRODUK (POST: /admin/produk/store) ---
    public function saveProduct()
    {
        $model = new ProductModel();
        
        // --- 1. Ambil File dari Form ---
        $imageFile = $this->request->getFile('image');
        
        // --- 2. Validasi Input (termasuk file) ---
        if (!$this->validate([
            'name'  => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            // Aturan untuk validasi file gambar
            'image' => [
                'uploaded[image]',
                'mime_in[image,image/jpg,image/jpeg,image/png]',
                'max_size[image,2048]', // Maks 2MB
            ]
        ])) {
            // Jika validasi gagal, kembali ke form dengan error
            return redirect()->back()->withInput()->with('error', 'Validasi gagal: Cek kembali input dan file gambar (PNG/JPG, maks 2MB).');
        }

        // --- 3. Proses Upload File ---
        if ($imageFile->isValid() && !$imageFile->hasMoved()) {
            // Generate nama file acak untuk keamanan
            $newName = $imageFile->getRandomName();
            
            // Pindahkan file ke folder publik (Pastikan folder public/images ada!)
            // ROOTPATH . 'public/images' mengacu ke folder public/images di root project
            $imageFile->move(ROOTPATH . 'public/images', $newName);
            
            // Simpan nama file baru ke database
            $imageNameForDB = $newName;
        } else {
            // Jika ada masalah upload selain error validasi
            return redirect()->back()->withInput()->with('error', 'Gagal mengunggah gambar. Coba lagi.');
        }

        // --- 4. Simpan Data Produk ke DB ---
        $model->save([
            'name'        => $this->request->getVar('name'),
            'description' => $this->request->getVar('description'),
            'price'       => $this->request->getVar('price'),
            'stock'       => $this->request->getVar('stock'),
            'image'       => $imageNameForDB // Menggunakan nama file yang diunggah
        ]);
        
        // Redirect kembali ke dashboard setelah menyimpan
        return redirect()->to('/admin/dashboard')->with('success', 'Produk dan gambar berhasil ditambahkan!');
    }

    // --- 3. DELETE PRODUK (GET: /admin/produk/delete/(:num)) ---
    public function deleteProduct($id)
    {
        $model = new ProductModel();
        $product = $model->find($id);

        if ($product) {
            // Hapus file gambar terkait dari folder public/images (Opsional, tapi disarankan)
            if ($product['image'] != 'default.jpg' && file_exists(ROOTPATH . 'public/images/' . $product['image'])) {
                unlink(ROOTPATH . 'public/images/' . $product['image']);
            }
        }

        $model->delete($id);
        
        return redirect()->to('/admin/dashboard')->with('success', 'Produk berhasil dihapus.');
    }
    
    // --- 4. MANAJEMEN PENGGUNA (USERS) ---
    public function indexUsers()
    {
        $userModel = new UserModel();
        
        $data = [
            // Hanya tampilkan pengguna yang bukan Admin (role: customer)
            'users' => $userModel->where('role !=', 'admin')->findAll(),
            'title' => 'Manajemen Pengguna Pelanggan'
        ];
        
        // PERBAIKAN: Memanggil View sesuai path baru: app/Views/admin/ManajemenPengguna.php
        return view('admin/ManajemenPengguna', $data);
    }

    // --- 5. MANAJEMEN PESANAN (ORDERS) ---
    public function indexOrders()
    {
        $orderModel = new OrderModel();
        
        $data = [
            'orders' => $orderModel->orderBy('created_at', 'DESC')->findAll(),
            'title' => 'Daftar Semua Pesanan'
        ];
        
        // PERBAIKAN: Memanggil View sesuai path baru: app/Views/admin/ManajemenPesanan.php
        return view('admin/ManajemenPesanan', $data);
    }

    // --- 6. EDIT PRODUK (GET: /admin/produk/edit/(:num)) ---
public function editProduct($id)
{
    $model = new ProductModel();
    $product = $model->find($id);

    if (!$product) {
        return redirect()->to('/admin/dashboard')->with('error', 'Produk tidak ditemukan.');
    }

    $data = [
        'product' => $product,
        'title' => 'Edit Produk: ' . $product['name']
    ];

    // Memanggil View baru: app/Views/admin/EditProduk.php
    return view('admin/EditProduk', $data);
}
    
    // Fitur Helper untuk Generate Password Hash (Bisa dihapus jika tidak lagi diperlukan)
    public function generatePass($pass) {
        echo password_hash($pass, PASSWORD_BCRYPT);
    }

    // --- 7. UPDATE PRODUK (POST: /admin/produk/update/(:num)) ---
public function updateProduct($id)
{
    $model = new ProductModel();
    
    // Ambil data lama produk untuk membandingkan atau menghapus gambar lama
    $oldProduct = $model->find($id);
    
    // --- 1. Validasi Input ---
    if (!$this->validate([
        'name'  => 'required',
        'price' => 'required|numeric',
        'stock' => 'required|integer',
        // Aturan 'image' bersifat opsional, karena pengguna mungkin tidak ingin mengganti gambar
        'image' => [
            'mime_in[image,image/jpg,image/jpeg,image/png]',
            'max_size[image,2048]', 
        ]
    ])) {
        return redirect()->back()->withInput()->with('error', 'Validasi gagal: Cek kembali input dan file gambar (PNG/JPG, maks 2MB).');
    }

    // --- 2. Proses Upload Gambar Baru (Jika ada) ---
    $imageFile = $this->request->getFile('image');
    $imageNameForDB = $oldProduct['image']; // Default: pakai nama gambar lama

    if ($imageFile->isValid() && !$imageFile->hasMoved()) {
        // Hapus gambar lama (jika bukan 'default.jpg')
        if ($oldProduct['image'] != 'default.jpg' && file_exists(ROOTPATH . 'public/images/' . $oldProduct['image'])) {
            unlink(ROOTPATH . 'public/images/' . $oldProduct['image']);
        }
        
        // Simpan gambar baru
        $newName = $imageFile->getRandomName();
        $imageFile->move(ROOTPATH . 'public/images', $newName);
        $imageNameForDB = $newName;
    }

    // --- 3. Update Data Produk ke DB ---
    $model->update($id, [
        'name'        => $this->request->getPost('name'),
        'description' => $this->request->getPost('description'),
        'price'       => $this->request->getPost('price'),
        'stock'       => $this->request->getPost('stock'),
        'image'       => $imageNameForDB // Bisa gambar lama atau gambar baru
    ]);
    
    return redirect()->to('/admin/dashboard')->with('success', 'Produk **' . $this->request->getPost('name') . '** berhasil diperbarui!');
}

}