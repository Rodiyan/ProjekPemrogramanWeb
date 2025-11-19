<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller; // Tidak perlu jika sudah extend BaseController

class Auth extends BaseController
{
    // --- REGISTER CUSTOMER ---
    public function register()
    {
        return view('auth/register'); 
    }

    public function processRegister()
    {
        $model = new UserModel();
        
        // Aturan validasi yang lebih kuat
        $rules = [
            'username' => 'required|min_length[4]|max_length[20]|is_unique[users.username]',
            'password' => 'required|min_length[6]',
            'name'     => 'required|min_length[3]|max_length[100]',
            'contact'  => 'required|max_length[15]'
        ];

        // 1. Cek Validasi Form
        if (!$this->validate($rules)) {
            // Jika validasi gagal (kosong, terlalu pendek, username sudah ada)
            // Menggunakan withInput() untuk mempertahankan data yang sudah diisi pengguna.
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        // 2. Siapkan Data
        $data = [
            'username' => $this->request->getVar('username'),
            'password' => $this->request->getVar('password'), 
            'name'     => $this->request->getVar('name'),
            'contact'  => $this->request->getVar('contact'),
            'role'     => 'customer'
        ];

        // 3. Insert ke Database
        if ($model->insert($data)) {
            return redirect()->to('/login')->with('success', 'Registrasi berhasil! Silakan login.');
        } else {
            // Ini akan menangkap kegagalan insert lainnya (misal error DB)
            return redirect()->back()->with('error', 'Registrasi gagal. Terjadi kesalahan sistem.');
        }
    }
    
    // --- LOGIN ---
    public function login()
    {
        return view('auth/login');
    }

    public function processLogin()
    {
        $session = session();
        $model = new UserModel(); 
        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');
        
        // 1. Cari User
        $data = $model->where('username', $username)->first();

        // 2. Verifikasi
        if($data){
            if(password_verify($password, $data['password'])){
                
                // Set Session
                $ses_data = [
                    'user_id' => $data['id'],
                    'username' => $data['username'],
                    'name' => $data['name'],
                    'role' => $data['role'],
                    'isLoggedIn' => TRUE
                ];
                $session->set($ses_data);

                // Redirect
                if ($data['role'] == 'admin') {
                    return redirect()->to('/admin/dashboard');
                } else {
                    return redirect()->to('/'); // Pelanggan
                }
            } else {
                // Password Salah
                return redirect()->back()->with('error', 'Login Gagal: Password salah.');
            }
        }
        
        // 3. User Tidak Ditemukan
        return redirect()->back()->with('error', 'Login Gagal: Username tidak ditemukan.');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/');
    }
}