<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class CustomerAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Cek jika user tidak login, atau login tapi bukan customer, redirect ke login
        if (!session()->get('isLoggedIn') || session()->get('role') != 'customer') {
            return redirect()->to('/login')->with('error', 'Anda harus login untuk melanjutkan pembelian.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}