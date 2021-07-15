<?php

namespace App\Controllers;

use App\Models\MUser;
use CodeIgniter\Exceptions\PageNotFoundException;

class User extends BaseController
{
   protected $m_user;

   public function __construct()
   {
      $this->m_user = new MUser();
   }

   public function profile()
   {
      $m_buku = new \App\Models\MBuku();
      if (session()->role == 'user') {
         $data = [
            "title" => "Profile",
            "keranjang" => $m_buku->get_total_keranjang_buku(),
         ];
         return view('profile', $data + $this->data_session());
      }
      $data = [
         "title" => "Profile"
      ];
      return view('admin/profile', $data + $this->data_session());
   }

   public function update_profile()
   {
      $validation = \Config\Services::validation();
      $validation->setRules(
         [
            "nama" => "required|alpha_space"
         ],
         [
            [
               "nama" => [
                  "required" => "Nama harus diisi.",
                  "alpha_space" => "Nama harus terdiri dari huruf, angka dan spasi"
               ]
            ]
         ]
      );
      if ($validation->withRequest($this->request)->run() == false) {
         return json_encode([
            "error" => true,
            "msg" => $validation->getError('nama')
         ]);
      }
      $no_hp = htmlentities($this->request->getPost('no_hp'), ENT_QUOTES, 'UTF-8');
      if (!preg_match('/^[0-9]/', $no_hp)) {
         return json_encode([
            "error" => true,
            "msg" => "No HP tidak valid"
         ]);
      }
      $nama = $this->request->getPost('nama');
      $alamat = htmlentities($this->request->getPost('alamat'), ENT_QUOTES, 'UTF-8');
      $username = session()->username;
      $file = $this->request->getFile('foto');
      $foto = [];
      if ($file->getName() != '') {
         $mime = ['image/png', 'image/jpg', 'image/jpeg'];
         if (!$file->isValid() || !in_array($file->getMimeType(), $mime)) {
            return json_encode([
               "error" => true,
               "msg" => "File tidak valid."
            ]);
         }
         if ($file->getSize() / 1024 / 1024 > 2) {
            return json_encode([
               "error" => true,
               "msg" => "File tidak boleh lebih besar dari 2MB."
            ]);
         }
         $filename = $file->getRandomName();
         $file->move('assets/img', $filename);
         $foto = ["foto" => $filename];
      } else {
         $filename = 'avatar.png';
      }
      $data = [
         "nama" => $nama,
         "no_hp" => $no_hp,
         "alamat" => $alamat
      ] + $foto;
      if ($this->m_user->update_user($data, $username)) {
         session()->set([
            "nama" => $nama,
            "no_hp" => $no_hp,
            "alamat" => $alamat
         ] + $foto);
         return json_encode([
            "error" => false,
            "msg" => "Profile berhasil diupdate."
         ]);
      } else {
         return json_encode([
            "error" => true,
            "msg" => "Profile gagal diupdate,"
         ]);
      }
   }

   public function daftar_user()
   {
      $data = [
         "title" => "Daftar User"
      ];
      return view('admin/daftar_user', $data + $this->data_session());
   }

   public function get_daftar_user()
   {
      $search = $_POST['search']['value'];
      $limit = $_POST['length'];
      $start = $_POST['start'];
      $index = $_POST['order'][0]['column'];
      $field = $_POST['columns'][$index]['data'];
      $orderby = $_POST['order'][0]['dir'];
      $total_user = $this->m_user->get_total_user();
      $filter_user = $this->m_user->get_filter_user($search, $limit, $start, $field, $orderby);
      $total_filter_user = $this->m_user->get_total_filter_user($search);
      $data = [
         "draw" => $_POST['draw'],
         "recordsTotal" => $total_user,
         "recordsFiltered" => $total_filter_user,
         "data" => $filter_user
      ];
      return json_encode($data);
   }

   public function detail_user($username)
   {
      if ($result = $this->m_user->get_detail_user($username)) {
         $data = [
            "title" => "Detail User",
            "data" => $result
         ];
         return view('admin/detail_user', $data + $this->data_session());
      } else {
         throw new PageNotFoundException();
      }
   }

   public function add_user()
   {
      if ($this->request->getMethod() == "post") {
         $validation = \Config\Services::validation();
         $validation->setRules(
            [
               "username" => "required|is_unique[user.username]|alpha_numeric",
               "nama" => "required|alpha_space",
               "email" => "required|valid_email|is_unique[user.email]",
               "password" => "required|min_length[6]",
               "role" => "required|valid_role",
               "status" => "required|numeric"
            ],
            [
               "username" => [
                  "required" => "Username harus diisi.",
                  "is_unique" => "Username sudah terdaftar.",
                  "alpha_numeric" => "Username harus terdiri dari huruf dan angka."
               ],
               [
                  "nama" => [
                     "required" => "Nama harus diisi.",
                     "alpha_space" => "Nama harus terdiri dari huruf, angka dan spasi"
                  ]
               ],
               "email" => [
                  "required" => "Email harus diisi.",
                  "is_unique" => "Email sudah terdaftar.",
                  "valid_email" => "Email tidak valid."
               ],
               "password" => [
                  "required" => "Password harus diisi.",
                  "min_length" => "Password minimal 6 karakter."
               ],
               "role" => [
                  "required" => "Role harus diisi.",
                  "valid_role" => "Role tidak valid."
               ],
               "status" => [
                  "required" => "Status harus diisi.",
                  "numeric" => "Status tidak valid."
               ]
            ]
         );
         if ($validation->withRequest($this->request)->run() == false) {
            switch (true) {
               case $validation->hasError('username'):
                  $errorMsg = $validation->getError('username');
                  break;
               case $validation->hasError('email'):
                  $errorMsg = $validation->getError('email');
                  break;
               case $validation->hasError('nama'):
                  $errorMsg = $validation->getError('nama');
                  break;
               case $validation->hasError('password'):
                  $errorMsg = $validation->getError('password');
                  break;
               case $validation->hasError('role'):
                  $errorMsg = $validation->getError('role');
                  break;
               case $validation->hasError('status'):
                  $errorMsg = $validation->getError('status');
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
         $username = $this->request->getPost('username');
         $nama = $this->request->getPost('nama');
         $email = $this->request->getPost('email');
         $password = $this->request->getPost('password');
         $role = $this->request->getPost('role');
         $status = $this->request->getPost('status');
         $nohp = htmlentities($this->request->getPost('nohp'), ENT_QUOTES, 'UTF-8');
         $alamat = htmlentities($this->request->getPost('alamat'), ENT_QUOTES, 'UTF-8');
         $file = $this->request->getFile('foto');
         if ($file->getName() != '') {
            $mime = ['image/png', 'image/jpg', 'image/jpeg'];
            if (!$file->isValid() || !in_array($file->getMimeType(), $mime)) {
               return json_encode([
                  "error" => true,
                  "msg" => "File tidak valid."
               ]);
            }
            if ($file->getSize() / 1024 / 1024 > 2) {
               return json_encode([
                  "error" => true,
                  "msg" => "File tidak boleh lebih besar dari 2MB."
               ]);
            }
            $filename = $file->getRandomName();
            $file->move('assets/img', $filename);
         } else {
            $filename = 'avatar.png';
         }
         $data = [
            "username" => $username,
            "nama" => $nama,
            "email" => $email,
            "password" => $password,
            "role" => $role,
            "status" => $status,
            "no_hp" => $nohp,
            "alamat" => $alamat,
            "foto" => $filename
         ];
         if ($this->m_user->add_user($data)) {
            return json_encode([
               "error" => false,
               "msg" => "User berhasil ditambahkan."
            ]);
         } else {
            return json_encode([
               "error" => true,
               "msg" => "User gagal ditambahkan."
            ]);
         }
      }
      $data = [
         "title" => "Tambah User"
      ];
      return view('admin/tambah_user', $data + $this->data_session());
   }

   public function update_user($username)
   {
      if ($this->request->getMethod() == "post") {
         $validation = \Config\Services::validation();
         $validation->setRules(
            [
               "nama" => "required|alpha_space",
               "email" => "required|valid_email|is_unique[user.email,username,$username]",
               "role" => "required|valid_role",
               "status" => "required|numeric"
            ],
            [
               "nama" => [
                  "required" => "Nama harus diisi.",
                  "alpha_space" => "Nama harus terdiri dari huruf, angka dan spasi"
               ],
               "email" => [
                  "required" => "Email harus diisi.",
                  "is_unique" => "Email sudah terdaftar.",
                  "valid_email" => "Email tidak valid."
               ],
               "role" => [
                  "required" => "Role harus diisi.",
                  "valid_role" => "Role tidak valid."
               ],
               "status" => [
                  "required" => "Status harus diisi.",
                  "numeric" => "Status tidak valid."
               ]
            ]
         );
         if ($validation->withRequest($this->request)->run() == false) {
            switch (true) {
               case $validation->hasError('nama'):
                  $errorMsg = $validation->getError('nama');
                  break;
               case $validation->hasError('email'):
                  $errorMsg = $validation->getError('email');
                  break;
               case $validation->hasError('password'):
                  $errorMsg = $validation->getError('password');
                  break;
               case $validation->hasError('role'):
                  $errorMsg = $validation->getError('role');
                  break;
               case $validation->hasError('status'):
                  $errorMsg = $validation->getError('status');
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
         $password = $this->request->getPost('password');
         if ($password != null) {
            $password = ["password" => $password];
            $validation->reset();
            $validation->setRules(
               [
                  "password" => "min_length[6]"
               ],
               [
                  "password" => [
                     "min_length" => "Password minimal 6 karakter."
                  ]
               ]
            );
         } else {
            $password = [];
         }
         if ($validation->withRequest($this->request)->run() == false) {
            return json_encode([
               "error" => true,
               "msg" => $validation->getError('password')
            ]);
         }
         $nama = $this->request->getPost('nama');
         $email = $this->request->getPost('email');
         $role = $this->request->getPost('role');
         $status = $this->request->getPost('status');
         $nohp = htmlentities($this->request->getPost('nohp'), ENT_QUOTES, 'UTF-8');
         $alamat = htmlentities($this->request->getPost('alamat'), ENT_QUOTES, 'UTF-8');
         $file = $this->request->getFile('foto');
         $foto = [];
         if ($file->getName() != '') {
            $mime = ['image/png', 'image/jpg', 'image/jpeg'];
            if (!$file->isValid() || !in_array($file->getMimeType(), $mime)) {
               return json_encode([
                  "error" => true,
                  "msg" => "File tidak valid."
               ]);
            }
            if ($file->getSize() / 1024 / 1024 > 2) {
               return json_encode([
                  "error" => true,
                  "msg" => "File tidak boleh lebih besar dari 2MB."
               ]);
            }
            $filename = $file->getRandomName();
            $file->move('assets/img', $filename);
            $foto = ["foto" => $filename];
         } else {
            $filename = 'avatar.png';
         }
         $data = [
            "nama" => $nama,
            "email" => $email,
            "role" => $role,
            "status" => $status,
            "no_hp" => $nohp,
            "alamat" => $alamat
         ] + $password + $foto;
         if ($this->m_user->update_user($data, $username)) {
            return json_encode([
               "error" => false,
               "msg" => "User berhasil diupdate."
            ]);
         } else {
            return json_encode([
               "error" => true,
               "msg" => "User gagal diupdate,"
            ]);
         }
      }
   }

   public function delete_user($username)
   {
      if ($this->m_user->delete_user($username)) {
         return json_encode([
            "error" => false,
            "msg" => "User berhasil dihapus."
         ]);
      } else {
         return json_encode([
            "error" => true,
            "msg" => "User gagal dihapus,"
         ]);
      }
   }

   public function change_password()
   {
      $pass_lama = $this->request->getPost('pass_lama');
      $pass_baru = $this->request->getPost('pass_baru');
      $validation = \Config\Services::validation();
      $validation->setRules(
         [
            "pass_lama" => "required|min_length[6]",
            "pass_baru" => "required|min_length[6]"
         ],
         [
            "pass_lama" => [
               "required" => "Password harus diisi.",
               "min_length" => "Password minimal 6 karakter."
            ],
            "pass_baru" => [
               "required" => "Password harus diisi.",
               "min_length" => "Password minimal 6 karakter."
            ]
         ]
      );
      if ($validation->withRequest($this->request)->run() == false) {
         switch (true) {
            case $validation->hasError('pass_lama'):
               $errorMsg = $validation->getError('pass_lama');
               break;
            case $validation->hasError('pass_baru'):
               $errorMsg = $validation->getError('pass_baru');
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
      if ($result = $this->m_user->get_detail_user(session()->username)) {
         if (password_verify($pass_lama, $result->password)) {
            $data = [
               "password" => password_hash($pass_baru, PASSWORD_DEFAULT)
            ];
            if ($this->m_user->update_user($data, session()->username)) {
               return json_encode([
                  "error" => false,
                  "msg" => "Password berhasil diubah."
               ]);
            } else {
               return json_encode([
                  "error" => true,
                  "msg" => "Password gagal diubah."
               ]);
            }
         } else {
            return json_encode([
               "error" => true,
               "msg" => "Password lama tidak valid."
            ]);
         }
      } else {
         return json_encode([
            "error" => true,
            "msg" => "Terjadi Kesalahan."
         ]);
      }
   }

   public function laporan_user()
   {
      $data = [
         "title" => "Laporan User",
         "total_user" => $this->m_user->get_total_user(),
         "data" => $this->m_user->laporan_user(),
         "user_today" => $this->m_user->user_today()
      ];
      return view('admin/laporan/user', $data + $this->data_session());
   }
}
