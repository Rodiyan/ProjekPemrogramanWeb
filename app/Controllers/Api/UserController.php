<?php

namespace App\Controllers\Api; // Namespace harus menunjuk ke sub-folder 'Api'

use CodeIgniter\API\ResponseTrait;
use App\Models\UserModel; 

// Class harus berakhiran Controller
class UserController extends \App\Controllers\BaseController
{
    use ResponseTrait;

    public function updateContact()
    {
        $rules = [
            'user_id' => 'required|integer',
            'contact' => 'required|max_length[15]'
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $userId = $this->request->getPost('user_id');
        $newContact = $this->request->getPost('contact');

        $userModel = new UserModel();
        
        // Pastikan user ada
        if (!$userModel->find($userId)) {
            return $this->failNotFound('User tidak ditemukan.');
        }

        // Update kontak
        $updated = $userModel->update($userId, ['contact' => $newContact]);

        if ($updated) {
            return $this->respond([
                'status' => 'success',
                'message' => 'Kontak berhasil diperbarui.'
            ]);
        } else {
            return $this->fail('Gagal memperbarui kontak.', 500);
        }
    }
}