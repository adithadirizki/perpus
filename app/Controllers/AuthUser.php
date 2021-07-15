<?php

namespace App\Controllers;

use App\Models\MHome;
use App\Models\MUser;
use Config\Email;

class AuthUser extends BaseController
{
   protected $m_home;
   protected $m_user;

   public function __construct()
   {
      $this->m_home = new MHome();
      $this->m_user = new MUser();
   }

   public function register()
   {
      if ($this->request->getMethod() == "post") {
         $validation = \Config\Services::validation();
         $validation->setRules(
            [
               'username' => 'required|is_unique[user.username]|alpha_numeric',
               'nama' => 'required|alpha_space',
               'email' => 'required|is_unique[user.email]|valid_email',
               'password' => 'required|min_length[6]'
            ],
            [
               'username' => [
                  'required' => 'Username harus diisi.',
                  'is_unique' => 'Username sudah terdaftar.',
                  'alpha_numeric' => 'Username harus terdiri dari huruf dan angka.'
               ],
               'nama' => [
                  'required' => 'Nama harus diisi.',
                  'alpha_space' => 'Nama harus terdiri dari huruf dan spasi.'
               ],
               'email' => [
                  'required' => 'Email harus diisi.',
                  'is_unique' => 'Email sudah terdaftar.',
                  'valid_email' => 'Email tidak valid.'
               ],
               'password' => [
                  'required' => 'Password harus diisi.',
                  'min_length' => 'Passwrod minimal 6 karakter.'
               ]
            ]
         );
         if ($validation->withRequest($this->request)->run() == false) {
            switch (true) {
               case $validation->hasError('username'):
                  $errorMsg = $validation->getError('username');
                  break;
               case $validation->hasError('nama'):
                  $errorMsg = $validation->getError('nama');
                  break;
               case $validation->hasError('email'):
                  $errorMsg = $validation->getError('email');
                  break;
               case $validation->hasError('password'):
                  $errorMsg = $validation->getError('password');
                  break;
               default:
                  $errorMsg = "Terjadi kesalahan.";
                  break;
            }
            return json_encode([
               "error" => true,
               "msg" => $errorMsg
            ]);
         }
         helper('text');
         $token = random_string('md5');
         $data = [
            "username" => $this->request->getPost('username'),
            "nama" => $this->request->getPost('nama'),
            "email" => $this->request->getPost('email'),
            "password" => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            "role" => "user",
            "foto" => "avatar.png",
            "status" => 0,
            "token_aktivasi" => $token
         ];
         if ($this->m_user->add_user($data)) {
            $msg = "Verifikasi akun Anda! <a href='" . base_url('verifikasi_email/' . $token) . "' target='_blank'>Klik disini</a>";
            if ($this->send_email_verifikasi($msg, $this->request->getPost('email'), "Email Verifikasi Akun")) {
               return json_encode([
                  "error" => false,
                  "msg" => "Email verifikasi berhasil dikirim, silahkan cek kotak masuk diemail Anda."
               ]);
            } else {
               return json_encode([
                  "error" => true,
                  "msg" => "Email verifikasi gagal dikirim."
               ]);
            }
         } else {
            return json_encode([
               "error" => false,
               "msg" => "Gagal melakukan registrasi."
            ]);
         }
      }
      $data = [
         "title" => "Create Account"
      ];
      return view('register', $data);
   }

   public function login()
   {
      $session = session();
      if ($this->request->getMethod() == "post") {
         $validation = \Config\Services::validation();
         $validation->setRules(
            [
               'email' => 'required|valid_email',
               'password' => 'required|min_length[6]'
            ],
            [
               'email' => [
                  'required' => 'Email harus diisi.',
                  'valid_email' => 'Email tidak valid.'
               ],
               'password' => [
                  'required' => 'Password harus diisi.',
                  'min_length' => 'Passwrod minimal 6 karakter.'
               ]
            ]
         );
         if ($validation->withRequest($this->request)->run() == false) {
            switch (true) {
               case $validation->hasError('email'):
                  $errorMsg = $validation->getError('email');
                  break;
               case $validation->hasError('password'):
                  $errorMsg = $validation->getError('password');
                  break;
               default:
                  $errorMsg = "Terjadi kesalahan.";
                  break;
            }
            return json_encode([
               "error" => true,
               "msg" => $errorMsg
            ]);
         }
         $data = [
            "email" => $this->request->getPost('email'),
            "password" => $this->request->getPost('password')
         ];
         $data = $this->m_user->login($data);
         if (is_object($data)) {
            $sett = $this->m_home->setting();
            $session->set([
               "nama_app" => $sett->nama_app,
               "logo" => $sett->logo,
               "nama" => $data->nama,
               "username" => $data->username,
               "email" => $data->email,
               "foto" => $data->foto,
               "role" => $data->role,
               "no_hp" => $data->no_hp,
               "alamat" => $data->alamat,
               "hasLogin" => true
            ]);
            return json_encode([
               "error" => false,
               "msg" => "Berhasil login, Anda akan diarahkan ke dashboard.",
               "redirect" => $data->role == "admin" ? base_url('admin') : base_url()
            ]);
         }
         return json_encode([
            "error" => true,
            "msg" => $data['errorMsg']
         ]);
      }
      $data = [
         "title" => "Login an account"
      ];
      return view('login', $data);
   }

   public function send_email_verifikasi($msg, $emailTo, $subject)
   {
      $email_conf = new Email;
      $email = \Config\Services::email();
      $email->setFrom($email_conf->fromEmail, $email_conf->fromName);
      $email->setTo($emailTo);
      $email->setSubject($subject);
      $email->setMessage($msg);
      return $email->send();
      // echo $email->printDebugger(['headers']);
   }

   public function resend_email_verifikasi()
   {
      if ($this->request->getMethod() == "post") {
         $validation = \Config\Services::validation();
         $validation->setRules(
            [
               'email' => 'required|valid_email'
            ],
            [
               'email' => [
                  'required' => 'Email harus diisi.',
                  'valid_email' => 'Email tidak valid.'
               ]
            ]
         );
         if ($validation->withRequest($this->request)->run() == false) {
            return json_encode([
               "error" => true,
               "msg" => $validation->getError('email')
            ]);
         }
         $data = ["email" => $this->request->getPost('email')];
         $result = $this->m_user->get_user($data);
         if (!$result) {
            return json_encode([
               "error" => true,
               "msg" => "Email tidak terdaftar!"
            ]);
         }
         if ($result->status == 1) {
            return json_encode([
               "error" => false,
               "msg" => "Email sudah terverifikasi. Silahkan login!"
            ]);
         }
         $token = $result->token_aktivasi;
         $msg = "Verifikasi akun Anda! <a href='" . base_url('verifikasi_email/' . $token) . "' target='_blank'>Klik disini</a>";
         if ($this->send_email_verifikasi($msg, $this->request->getPost('email'), "Email Verifikasi Akun")) {
            return json_encode([
               "error" => false,
               "msg" => "Email verifikasi berhasil dikirim, silahkan cek kotak masuk diemail Anda."
            ]);
         } else {
            return json_encode([
               "error" => true,
               "msg" => "Email verifikasi gagal dikirim."
            ]);
         }
      }
      $data = [
         "title" => "Resend Email Verifikasi"
      ];
      return view('resend_email_verifikasi', $data + $this->data_session());
   }

   public function verifikasi_email($token)
   {
      $session = session();
      $verifikasi = $this->m_user->verifikasi_email($token);
      $session->setFlashdata('msg', $verifikasi['msg']);
      return redirect()->to(base_url('login'));
   }

   public function forgot_password()
   {
      if ($this->request->getMethod() == "post") {
         $validation = \Config\Services::validation();
         $validation->setRules(
            [
               "email" => "required|valid_email"
            ],
            [
               "email" => [
                  "required" => "Email harus diisi.",
                  "valid_email" => "Email tidak valid."
               ]
            ]
         );
         if ($validation->withRequest($this->request)->run() == false) {
            return json_encode([
               "error" => true,
               "msg" => $validation->getError('email')
            ]);
         }
         $email = $this->request->getPost('email');
         $data = [
            "email" => $email
         ];
         helper('text');
         $token = random_string('md5');
         $result = $this->m_user->get_user($data);
         if (!$result) {
            return json_encode([
               "error" => true,
               "msg" => "Email tidak terdaftar."
            ]);
         }
         $data = [
            "token_aktivasi" => $token
         ];
         if ($this->m_user->update_user($data, $result->username)) {
            $msg = "Konfirmasi bahwa yang melakukan Lupa Password ini adalah Anda!<br/> <a href='" . base_url('verifikasi_lupa_password/' . $token) . "' target='_blank'>Klik disini</a> jika Anda yang melakukan ini.<br/><a href='" . base_url('verifikasi_lupa_password/' . $token) . "' target='_blank'>" . base_url('verifikasi_lupa_password/' . $token) . "</a><br/>Jika bukan Anda yang melakukan ini abaikan saja pesan ini.";
            if ($this->send_email_verifikasi($msg, $email, "Lupa Password")) {
               return json_encode([
                  "error" => false,
                  "msg" => "Konfirmasi lupa password berhasil dikirim, silahkan cek kotak masuk diemail Anda."
               ]);
            } else {
               return json_encode([
                  "error" => true,
                  "msg" => "Konfirmasi lupa password gagal dikirim."
               ]);
            }
         } else {
            return json_encode([
               "error" => true,
               "msg" => "Terjadi kesalahan."
            ]);
         }
      }
      $data = [
         "title" => "Lupa Password"
      ];
      return view('forgot_password', $data + $this->data_session());
   }

   public function verifikasi_forgot_password($token)
   {
      $data = [
         "token_aktivasi" => $token
      ];
      $result = $this->m_user->get_user($data);
      if (!$result) {
         return json_encode([
            "error" => true,
            "msg" => "Token tidak valid."
         ]);
      }
      helper('text');
      $password = random_string('alnum', 8);
      $data = [
         "password" => password_hash($password, PASSWORD_DEFAULT),
         "token_aktivasi" => null
      ];
      $session = session();
      if ($this->m_user->update_user($data, $result->username)) {
         $msg = "Password baru Anda adalah : " . $password . "<br/>Gunakan password ini untuk <a href='" . base_url('login') . "' target='_blank'>Login disini</a>.";
         if ($this->send_email_verifikasi($msg, $result->email, "Password Baru")) {
            $session->setFlashdata('msg', 'Password baru Anda berhasil dikirim, silahkan cek kotak masuk diemail Anda.');
         } else {
            $session->setFlashdata('msg', 'Password baru Anda gagal dikirim.');
         }
      } else {
         $session->setFlashdata('msg', 'Terjadi kesalahan.');
      }
      return redirect()->to(base_url('login'));
   }

   public function logout()
   {
      session()->destroy();
      return redirect()->to(base_url('login'));
   }

   //--------------------------------------------------------------------

}
