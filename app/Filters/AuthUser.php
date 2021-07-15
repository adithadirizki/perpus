<?php

namespace App\Filters;

use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthUser implements FilterInterface
{
   public function before(RequestInterface $request, $arguments = null)
   {
      // Access Before/After Login
      switch ($request->uri->getSegment(1)) {
         case 'login':
         case 'register':
         case 'verifikasi_email':
         case 'resend_email_verifikasi':
         case 'verifikasi_lupa_password':
         case 'lupa_password':
            $result = true;
            break;
         default:
            $result = false;
            break;
      }
      if ($result == true && session()->has('hasLogin')) {
         if (session()->role == 'admin') {
            return redirect()->to(base_url('admin'));
         } elseif (session()->role == 'user') {
            return redirect()->to(base_url());
         }
      } elseif ($result == false && !session()->has('hasLogin')) {
         return redirect()->to(base_url('login'));
      }

      // Role Access
      if ($request->uri->getSegment(1) == 'admin' && session()->role == 'user') {
         // return redirect()->to(base_url('/'));
         throw new PageNotFoundException("Halaman tidak ditemukan");
      }
   }

   //--------------------------------------------------------------------

   public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
   {
      // Do something here
   }
}
