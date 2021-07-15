<?php

namespace App\Controllers;

use App\Models\MKategori;
use CodeIgniter\Exceptions\PageNotFoundException;

class Kategori extends BaseController
{
   protected $m_kategori;

   public function __construct()
   {
      $this->m_kategori = new MKategori();
   }

   public function daftar_kategori()
   {
      $data = [
         "title" => "Kategori"
      ];
      return view('admin/daftar_kategori', $data + $this->data_session());
   }

   public function get_daftar_kategori()
   {
      $search = $_POST['search']['value'];
      $limit = $_POST['length'];
      $start = $_POST['start'];
      $index = $_POST['order'][0]['column'];
      $field = $_POST['columns'][$index]['data'];
      $orderby = $_POST['order'][0]['dir'];
      $total_kategori = $this->m_kategori->get_total_kategori();
      $filter_kategori = $this->m_kategori->get_filter_kategori($search, $limit, $start, $field, $orderby);
      $total_filter_kategori = $this->m_kategori->get_total_filter_kategori($search);
      $data = [
         "draw" => $_POST['draw'],
         "recordsTotal" => $total_kategori,
         "recordsFiltered" => $total_filter_kategori,
         "data" => $filter_kategori
      ];
      return json_encode($data);
   }

   public function detail_kategori($kategori_id)
   {
      if ($result = $this->m_kategori->get_detail_kategori($kategori_id)) {
         $data = [
            "title" => "Detail Kategori",
            "data" => $result
         ];
         return view('admin/detail_kategori', $data + $this->data_session());
      } else {
         throw new PageNotFoundException();
      }
   }

   public function add_kategori()
   {
      if ($this->request->getMethod() == "post") {
         $validation = \Config\Services::validation();
         $validation->setRules(
            [
               "nama_kategori" => "required|alpha_numeric_space"
            ],
            [
               "nama_kategori" => [
                  "required" => "Nama kategori harus diisi.",
                  "alpha_numeric_space" => "Nama kategori harus terdiri dari huruf, angka dan spasi."
               ]
            ]
         );
         if ($validation->withRequest($this->request)->run() == false) {
            return json_encode([
               "error" => true,
               "msg" => $validation->getError('nama_kategori')
            ]);
         }
         $nama_kategori = $this->request->getPost('nama_kategori');
         $data = [
            "nama_kategori" => $nama_kategori
         ];
         if ($this->m_kategori->add_kategori($data)) {
            return json_encode([
               "error" => false,
               "msg" => "Kategori berhasil ditambahkan."
            ]);
         } else {
            return json_encode([
               "error" => true,
               "msg" => "Kategori gagal ditambahkan,"
            ]);
         }
      }
      $data = [
         "title" => "Tambah Kategori"
      ];
      return view('admin/tambah_kategori', $data + $this->data_session());
   }

   public function update_kategori($kategori_id)
   {
      if ($this->request->getMethod() == "post") {
         $validation = \Config\Services::validation();
         $validation->setRules(
            [
               "nama_kategori" => "required|alpha_numeric_space"
            ],
            [
               "nama_kategori" => [
                  "required" => "Nama kategori harus diisi.",
                  "alpha_numeric_space" => "Nama kategori harus terdiri dari huruf, angka dan spasi."
               ]
            ]
         );
         if ($validation->withRequest($this->request)->run() == false) {
            return json_encode([
               "error" => true,
               "msg" => $validation->getError('nama_kategori')
            ]);
         }
         $nama_kategori = $this->request->getPost('nama_kategori');
         $data = [
            "nama_kategori" => $nama_kategori
         ];
         if ($this->m_kategori->update_kategori($data, $kategori_id)) {
            return json_encode([
               "error" => false,
               "msg" => "Kategori berhasil diupdate."
            ]);
         } else {
            return json_encode([
               "error" => true,
               "msg" => "Kategori gagal diupdate,"
            ]);
         }
      }
      $data = [
         "title" => "Tambah Kategori"
      ];
      return view('admin/tambah_kategori', $data + $this->data_session());
   }

   public function delete_kategori($kategori_id)
   {
      if ($this->m_kategori->delete_kategori($kategori_id)) {
         return json_encode([
            "error" => false,
            "msg" => "Kategori berhasil dihapus."
         ]);
      } else {
         return json_encode([
            "error" => true,
            "msg" => "Kategori gagal dihapus,"
         ]);
      }
   }
}
