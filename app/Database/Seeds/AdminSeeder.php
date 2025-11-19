<?
namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'username' => 'superadmin',
            // Hash password "admin123" menggunakan BCRYPT default
            'password' => password_hash('admin123', PASSWORD_DEFAULT),
            'fullname' => 'IT Administrator',
            'created_at' => date('Y-m-d H:i:s'),
        ];

        // Insert data ke tabel admins
        $this->db->table('admins')->insert($data);
        
        // Insert contoh API Key
        $this->db->table('api_keys')->insert([
             'key_code' => 'KEY-ABC-123-XYZ', 
             'description' => 'Default Web Key'
        ]);
    }
}