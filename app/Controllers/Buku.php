<?php

namespace App\Controllers;

use App\Models\MBuku;
use App\Models\MKategori;
use CodeIgniter\Exceptions\PageNotFoundException;

class Buku extends BaseController
{
   protected $m_buku;
   protected $m_kategori;

   public function __construct()
   {
      $this->m_buku = new MBuku();
      $this->m_kategori = new MKategori();
   }

   public function daftar_buku()
   {
      $data = [
         "title" => "Daftar Buku",
         "kategori" => $this->m_kategori->get_kategori()
      ];
      return view('admin/daftar_buku', $data + $this->data_session());
   }

   public function get_daftar_buku()
   {
      $search = $_POST['search']['value'];
      $limit = $_POST['length'];
      $start = $_POST['start'];
      $index = $_POST['order'][0]['column'];
      $field = $_POST['columns'][$index]['data'];
      $orderby = $_POST['order'][0]['dir'];
      $kategori = $_POST['kategori'];
      $query = "buku.id, nama_buku, kode_buku, pengarang, penerbit, tahun_terbit, isbn, nama_kategori";
      $total_buku = $this->m_buku->get_total_buku();
      $filter_buku = $this->m_buku->get_filter_buku($search, $limit, $start, $field, $orderby, $kategori, $query);
      $total_filter_buku = $this->m_buku->get_total_filter_buku($search, $kategori);
      $data = [
         "draw" => $_POST['draw'],
         "recordsTotal" => $total_buku,
         "recordsFiltered" => $total_filter_buku,
         "data" => $filter_buku
      ];
      return json_encode($data);
   }

   public function detail_buku($buku_id)
   {
      if (session()->role == 'user') {
         if ($result = $this->m_buku->get_detail_buku($buku_id)) {
            $data = [
               "title" => "Buku " . $result->nama_buku,
               "keranjang" => $this->m_buku->get_total_keranjang_buku(),
               "data" => $result
            ];
            return view('detail_buku', $data + $this->data_session());
         } else {
            throw new PageNotFoundException();
         }
      }
      if ($result = $this->m_buku->get_detail_buku($buku_id)) {
         $data = [
            "title" => "Detail Buku",
            "kategori" => $this->m_kategori->get_kategori(),
            "data" => $result
         ];
         return view('admin/detail_buku', $data + $this->data_session());
      } else {
         throw new PageNotFoundException();
      }
   }

   public function add_buku()
   {
      if ($this->request->getMethod() == "post") {
         $validation = \Config\Services::validation();
         $validation->setRules(
            [
               'nama_buku' => 'required',
               'kategori' => 'required|numeric',
               'jumlah_buku' => 'required|numeric',
               'penerbit' => 'required',
               'pengarang' => 'required',
               'tahun_terbit' => 'required|numeric'
            ],
            [
               'nama_buku' => [
                  'required' => 'Nama buku harus diisi.'
               ],
               'kategori' => [
                  'required' => 'Kategori harus diisi.',
                  'numeric' => 'Kategori tidak valid.'
               ],
               'jumlah_buku' => [
                  'required' => 'Jumlah buku harus diisi.',
                  'numeric' => 'Jumlah buku tidak valid.'
               ],
               'penerbit' => [
                  'required' => 'Penerbit harus diisi.'
               ],
               'pengarang' => [
                  'required' => 'Pengarang harus diisi.'
               ],
               'tahun_terbit' => [
                  'required' => 'Tahun terbit harus diisi.',
                  'numeric' => 'Tahun terbit tidak valid.'
               ]
            ]
         );
         if ($validation->withRequest($this->request)->run() == false) {
            switch (true) {
               case $validation->hasError('nama_buku'):
                  $errorMsg = $validation->getError('nama_buku');
                  break;
               case $validation->hasError('kategori'):
                  $errorMsg = $validation->getError('kategori');
                  break;
               case $validation->hasError('jumlah_buku'):
                  $errorMsg = $validation->getError('jumlah_buku');
                  break;
               case $validation->hasError('penerbit'):
                  $errorMsg = $validation->getError('penerbit');
                  break;
               case $validation->hasError('pengarang'):
                  $errorMsg = $validation->getError('pengarang');
                  break;
               case $validation->hasError('tahun_terbit'):
                  $errorMsg = $validation->getError('tahun_terbit');
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
         $nama_buku = htmlentities($this->request->getPost('nama_buku'), ENT_QUOTES, 'UTF-8');
         $kode_buku = htmlentities($this->request->getPost('kode_buku'), ENT_QUOTES, 'UTF-8');
         $deskripsi = htmlentities($this->request->getPost('deskripsi'), ENT_QUOTES, 'UTF-8');
         $isbn = htmlentities($this->request->getPost('isbn'), ENT_QUOTES, 'UTF-8');
         $kategori_id = $this->request->getPost('kategori');
         $jumlah_buku = $this->request->getPost('jumlah_buku');
         $pengarang = $this->request->getPost('pengarang');
         $penerbit = $this->request->getPost('penerbit');
         $tahun_terbit = $this->request->getPost('tahun_terbit');
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
            $filename = 'default.png';
         }
         $data = [
            "nama_buku" => $nama_buku,
            "kode_buku" => $kode_buku,
            "deskripsi" => $deskripsi,
            "kategori_id" => $kategori_id,
            "jumlah_buku" => $jumlah_buku,
            "pengarang" => $pengarang,
            "penerbit" => $penerbit,
            "tahun_terbit" => $tahun_terbit,
            "isbn" => $isbn,
            "foto" => $filename
         ];
         if ($this->m_buku->add_buku($data)) {
            return json_encode([
               "error" => false,
               "msg" => "Buku berhasil ditambahkan."
            ]);
         } else {
            return json_encode([
               "error" => true,
               "msg" => "Buku gagal ditambahkan,"
            ]);
         }
      }
      $data = [
         "title" => "Tambah Buku",
         "kategori" => $this->m_kategori->get_kategori()
      ];
      return view('admin/tambah_buku', $data + $this->data_session());
   }

   public function update_buku($buku_id)
   {
      if ($this->request->getMethod() == "post") {
         $validation = \Config\Services::validation();
         $validation->setRules(
            [
               'nama_buku' => 'required',
               'kategori' => 'required|numeric',
               'jumlah_buku' => 'required|numeric',
               'penerbit' => 'required',
               'pengarang' => 'required',
               'tahun_terbit' => 'required|numeric'
            ],
            [
               'nama_buku' => [
                  'required' => 'Nama buku harus diisi.'
               ],
               'kategori' => [
                  'required' => 'Kategori harus diisi.',
                  'numeric' => 'Kategori tidak valid.'
               ],
               'jumlah_buku' => [
                  'required' => 'Jumlah buku harus diisi.',
                  'numeric' => 'Jumlah buku tidak valid.'
               ],
               'penerbit' => [
                  'required' => 'Penerbit harus diisi.'
               ],
               'pengarang' => [
                  'required' => 'Pengarang harus diisi.'
               ],
               'tahun_terbit' => [
                  'required' => 'Tahun terbit harus diisi.',
                  'numeric' => 'Tahun terbit tidak valid.'
               ]
            ]
         );
         if ($validation->withRequest($this->request)->run() == false) {
            switch (true) {
               case $validation->hasError('nama_buku'):
                  $errorMsg = $validation->getError('nama_buku');
                  break;
               case $validation->hasError('kategori'):
                  $errorMsg = $validation->getError('kategori');
                  break;
               case $validation->hasError('jumlah_buku'):
                  $errorMsg = $validation->getError('jumlah_buku');
                  break;
               case $validation->hasError('penerbit'):
                  $errorMsg = $validation->getError('penerbit');
                  break;
               case $validation->hasError('pengarang'):
                  $errorMsg = $validation->getError('pengarang');
                  break;
               case $validation->hasError('tahun_terbit'):
                  $errorMsg = $validation->getError('tahun_terbit');
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
         $nama_buku = htmlentities($this->request->getPost('nama_buku'), ENT_QUOTES, 'UTF-8');
         $kode_buku = htmlentities($this->request->getPost('kode_buku'), ENT_QUOTES, 'UTF-8');
         $deskripsi = htmlentities($this->request->getPost('deskripsi'), ENT_QUOTES, 'UTF-8');
         $isbn = htmlentities($this->request->getPost('isbn'), ENT_QUOTES, 'UTF-8');
         $kategori_id = $this->request->getPost('kategori');
         $jumlah_buku = $this->request->getPost('jumlah_buku');
         $pengarang = $this->request->getPost('pengarang');
         $penerbit = $this->request->getPost('penerbit');
         $tahun_terbit = $this->request->getPost('tahun_terbit');
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
            $filename = 'default.png';
         }
         $data = [
            "nama_buku" => $nama_buku,
            "kode_buku" => $kode_buku,
            "deskripsi" => $deskripsi,
            "kategori_id" => $kategori_id,
            "jumlah_buku" => $jumlah_buku,
            "pengarang" => $pengarang,
            "penerbit" => $penerbit,
            "tahun_terbit" => $tahun_terbit,
            "isbn" => $isbn
         ] + $foto;
         if ($this->m_buku->update_buku($data, $buku_id)) {
            return json_encode([
               "error" => false,
               "msg" => "Buku berhasil diupdate."
            ]);
         } else {
            return json_encode([
               "error" => true,
               "msg" => "Buku gagal diupdate,"
            ]);
         }
      }
   }

   public function delete_buku($buku_id)
   {
      if ($this->m_buku->delete_buku($buku_id)) {
         return json_encode([
            "error" => false,
            "msg" => "Buku berhasil dihapus."
         ]);
      } else {
         return json_encode([
            "error" => true,
            "msg" => "Buku gagal dihapus,"
         ]);
      }
   }

   public function pinjam_buku()
   {
      $buku_id = $this->request->getPost('id');
      $data = [
         "buku_id" => $buku_id,
         "username" => session()->username
      ];
      $pinjam_buku = $this->m_buku->pinjam_buku($data);
      if (is_array($pinjam_buku) && isset($pinjam_buku['errorMsg'])) {
         return json_encode([
            "error" => true,
            "msg" => $pinjam_buku['errorMsg']
         ]);
      } elseif ($pinjam_buku) {
         return json_encode([
            "error" => false,
            "msg" => "Buku berhasil masuk keranjang."
         ]);
      } else {
         return json_encode([
            "error" => true,
            "msg" => "Buku gagal masuk keranjang,"
         ]);
      }
   }

   public function keranjang()
   {
      $data = [
         "title" => "Keranjang",
         "data" => $this->m_buku->get_keranjang(),
         "keranjang" => $this->m_buku->get_total_keranjang_buku()
      ];
      return view('keranjang', $data + $this->data_session());
   }

   public function delete_keranjang()
   {
      $buku_id = $this->request->getPost('id');
      $data = [
         "buku_id" => $buku_id,
         "username" => session()->username
      ];
      if ($this->m_buku->delete_keranjang($data)) {
         return json_encode([
            "error" => false,
            "msg" => "Buku berhasil dibuang dari keranjang."
         ]);
      } else {
         return json_encode([
            "error" => true,
            "msg" => "Buku gagal dibuang dari keranjang,"
         ]);
      }
   }
}
