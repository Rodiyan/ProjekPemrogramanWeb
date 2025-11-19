<?php

namespace App\Controllers\Api; // Namespace harus menunjuk ke sub-folder 'Api'

use CodeIgniter\API\ResponseTrait;
use App\Models\OrderModel; 

// Class harus berakhiran Controller
class OrderController extends \App\Controllers\BaseController 
{
    use ResponseTrait;

    public function getOrders()
    {
        $userId = $this->request->getGet('user_id');

        if (empty($userId)) {
            return $this->fail('Parameter user_id diperlukan.', 400);
        }

        $orderModel = new OrderModel();
        
        // Asumsi logic ini benar
        $orders = $orderModel
                        ->where('user_id', $userId)
                        ->orderBy('created_at', 'DESC')
                        ->findAll();

        return $this->respond([
            'status' => 'success',
            'orders' => $orders
        ]);
    }
}