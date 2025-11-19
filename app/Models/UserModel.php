<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    
    // Pastikan 'contact' termasuk dalam allowedFields
    protected $allowedFields = ['username', 'password', 'name', 'contact', 'role']; 
    
    // Gunakan fungsi BCRYPT otomatis dari CI4
    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_BCRYPT);
        }
        return $data;
    }

    // =======================================================
    // FUNGSI BARU UNTUK UPDATE KONTAK MELALUI API
    // =======================================================
    /**
     * Memperbarui kolom 'contact' untuk user tertentu.
     *
     * @param int $userId ID Pengguna
     * @param string $newContact Nomor kontak baru
     * @return bool
     */
    public function updateContact($userId, $newContact): bool
    {
        // Fungsi $this->update() bawaan CI4 akan memperbarui 
        // baris berdasarkan $primaryKey (yaitu 'id').
        // Kita hanya perlu menentukan ID dan data yang diubah.
        return $this->update($userId, ['contact' => $newContact]);
    }

    // =======================================================
    // FUNGSI TAMBAHAN (Jika diperlukan untuk autentikasi)
    // =======================================================
    
    /**
     * Fungsi untuk mencari user berdasarkan username (yang berfungsi sebagai email)
     * @param string $username
     * @return array|null
     */
    public function getUserByUsername(string $username)
    {
        return $this->where('username', $username)->first();
    }
}