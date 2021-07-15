<?php

namespace App\Controllers;

use App\Models\MBuku;
use App\Models\MDenda;
use App\Models\MPinjaman;
use App\Models\MUser;
use CodeIgniter\Exceptions\PageNotFoundException;

class Pinjaman extends BaseController
{
   protected $m_pinjaman;
   protected $m_denda;
   protected $m_user;
   protected $m_buku;

   public function __construct()
   {
      $this->m_pinjaman = new MPinjaman();
      $this->m_denda = new MDenda();
      $this->m_user = new MUser();
      $this->m_buku = new MBuku();
   }

   public function daftar_pinjaman()
   {
      if (session()->role == 'user') {
         $data = [
            "title" => "Pinjaman",
            "keranjang" => $this->m_buku->get_total_keranjang_buku(),
            "data" => $this->m_pinjaman->get_pinjaman()
         ];
         return view('pinjaman', $data + $this->data_session());
      }
      $data = [
         "title" => "Daftar Pinjaman"
      ];
      return view('admin/daftar_pinjaman', $data + $this->data_session());
   }

   public function get_daftar_pinjaman()
   {
      $search = $_POST['search']['value'];
      $limit = $_POST['length'];
      $start = $_POST['start'];
      $index = $_POST['order'][0]['column'];
      $field = $_POST['columns'][$index]['data'];
      $orderby = $_POST['order'][0]['dir'];
      $status = $_POST['status'];
      $total_pinjaman = $this->m_pinjaman->get_total_pinjaman();
      $filter_pinjaman = $this->m_pinjaman->get_filter_pinjaman($search, $limit, $start, $field, $orderby, $status);
      $total_filter_pinjaman = $this->m_pinjaman->get_total_filter_pinjaman($search, $status);
      $data = [
         "draw" => $_POST['draw'],
         "recordsTotal" => $total_pinjaman,
         "recordsFiltered" => $total_filter_pinjaman,
         "data" => $filter_pinjaman
      ];
      return json_encode($data);
   }

   public function detail_pinjaman($no_pinjam)
   {
      if (session()->role == 'user') {
         if ($result = $this->m_pinjaman->get_detail_pinjaman($no_pinjam)) {
            $data = [
               "title" => "Detail Pinjaman",
               "keranjang" => $this->m_buku->get_total_keranjang_buku(),
               "data" => $result
            ];
            return view('detail_pinjaman', $data + $this->data_session());
         } else {
            throw new PageNotFoundException();
         }
      }
      if ($result = $this->m_pinjaman->get_detail_pinjaman($no_pinjam)) {
         $data = [
            "title" => "Detail Pinjaman",
            "data" => $result
         ];
         return view('admin/detail_pinjaman', $data + $this->data_session());
      } else {
         throw new PageNotFoundException();
      }
   }

   public function add_pinjaman()
   {
      if ($this->request->getMethod() == "post") {
         $start = $_POST['start'];
         $end = $_POST['end'];
         $username = $_POST['username'];
         $buku_id = $_POST['id'];
         if (count($buku_id) == 0 or $buku_id == null) {
            return json_encode([
               "error" => true,
               "msg" => "Tidak ada buku yang dipinjam, pilih buku terlebih dahulu.",
            ]);
         }
         $no_pinjam = $this->m_pinjaman->get_no_pinjam();
         $data = [
            "no_pinjam" => $no_pinjam,
            "username" => $username,
            "start" => $start,
            "end" => $end
         ];
         $add_pinjaman = $this->m_pinjaman->add_pinjaman($data);
         $data = null;
         foreach ($buku_id as $row) {
            $data[] = [
               "buku_id" => $row,
               "no_pinjam" => $no_pinjam
            ];
         }
         $add_buku_pinjaman = $this->m_pinjaman->add_buku_pinjaman($data);
         if ($add_pinjaman && $add_buku_pinjaman) {
            return json_encode([
               "error" => false,
               "msg" => "Pinjaman berhasil ditambahkan."
            ]);
         } else {
            return json_encode([
               "error" => true,
               "msg" => "Pinjaman gagal ditambahkan."
            ]);
         }
      }
      $data = [
         "title" => "Tambah Pinjaman"
      ];
      return view('admin/tambah_pinjaman', $data + $this->data_session());
   }

   public function confirm_pinjaman($no_pinjam)
   {
      $validation = \Config\Services::validation();
      $end = $this->request->getPost('end');
      $validation->setRules(
         [
            "end" => "required|valid_date[Y-m-d]"
         ],
         [
            "end" => [
               "required" => "Tgl tempo harus diisi.",
               "valid_date" => "Tgl tempo tidak valid."
            ]
         ]
      );
      if ($validation->withRequest($this->request)->run() == false) {
         switch (true) {
            case $validation->hasError('end'):
               $errorMsg = $validation->getError('end');
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
         "status" => 0,
         "end" => $end
      ];
      if ($this->m_pinjaman->update_pinjaman($data, $no_pinjam)) {
         return json_encode([
            "error" => false,
            "msg" => "Pinjaman berhasil dikonfirmasi."
         ]);
      } else {
         return json_encode([
            "error" => true,
            "msg" => "Pinjaman gagal dikonfirmasi."
         ]);
      }
   }

   public function update_pinjaman($no_pinjam)
   {
      $denda = htmlentities(preg_replace('/\D/', '', $this->request->getPost('denda')), ENT_QUOTES, 'UTF-8');
      if($denda != null && !preg_match('/^[0-9]/', $denda)) {
         return json_encode([
            "error" => true,
            "msg" => "Denda tidak valid."
         ]);
      }
      $ket_denda = htmlentities($this->request->getPost('ket_denda'), ENT_QUOTES, 'UTF-8');
      $data = [
         "denda" => $denda,
         "ket_denda" => $ket_denda,
         "no_pinjam" => $no_pinjam
      ];
      // Jika ada denda == 0 maka jangan tambah denda
      $add_denda = $denda != null ? $this->m_denda->add_denda($data) : true;
      $data = [
         "status" => 1,
         "tgl_return" => date('Y-m-d')
      ];
      $update_pinjaman = $this->m_pinjaman->update_pinjaman($data, $no_pinjam);
      if ($update_pinjaman && $add_denda) {
         return json_encode([
            "error" => false,
            "msg" => "Pinjaman berhasil diupdate."
         ]);
      } else {
         return json_encode([
            "error" => true,
            "msg" => "Pinjaman gagal diupdate."
         ]);
      }
   }

   public function delete_pinjaman($no_pinjam)
   {
      if ($this->m_pinjaman->delete_pinjaman($no_pinjam) && $this->m_pinjaman->delete_buku_pinjaman($no_pinjam)) {
         return json_encode([
            "error" => false,
            "msg" => "Pinjaman berhasil dihapus."
         ]);
      } else {
         return json_encode([
            "error" => false,
            "msg" => "Pinjaman berhasil dihapus."
         ]);
      }
   }

   public function get_user_pinjaman()
   {
      $search = $_POST['search']['value'];
      $limit = $_POST['length'];
      $start = $_POST['start'];
      $index = $_POST['order'][0]['column'];
      $field = $_POST['columns'][$index]['data'];
      $orderby = $_POST['order'][0]['dir'];
      $total_user = $this->m_user->get_total_user();
      $this->m_user->select("nama, username, email");
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

   public function get_buku_pinjaman()
   {
      $search = $_POST['search']['value'];
      $limit = $_POST['length'];
      $start = $_POST['start'];
      $index = $_POST['order'][0]['column'];
      $field = $_POST['columns'][$index]['data'];
      $orderby = $_POST['order'][0]['dir'];
      $query = "buku.id, nama_buku, kode_buku, pengarang, penerbit, isbn";
      $total_buku = $this->m_buku->get_total_buku();
      $filter_buku = $this->m_buku->get_filter_buku($search, $limit, $start, $field, $orderby, null, $query);
      $total_filter_buku = $this->m_buku->get_total_filter_buku($search);
      $data = [
         "draw" => $_POST['draw'],
         "recordsTotal" => $total_buku,
         "recordsFiltered" => $total_filter_buku,
         "data" => $filter_buku
      ];
      return json_encode($data);
   }

   public function laporan_pinjaman()
   {
      $data = [
         "title" => "Laporan Pinjaman",
         "total_pinjaman" => $this->m_pinjaman->get_total_pinjaman(),
         "data" => $this->m_pinjaman->laporan_pinjaman(),
         "pinjaman_today" => $this->m_pinjaman->pinjaman_today()
      ];
      return view('admin/laporan/pinjaman', $data + $this->data_session());
   }

   public function ajukan_pinjaman()
   {
      $m_buku = new MBuku();
      $keranjang = $m_buku->get_keranjang();
      if ($keranjang == null) {
         return json_encode([
            "error" => false,
            "msg" => "Tidak ada buku dikeranjang."
         ]);
      }
      $no_pinjam = $this->m_pinjaman->get_no_pinjam();
      $data = [
         "no_pinjam" => $no_pinjam,
         "username" => session()->username,
         "status" => 2
      ];
      $add_pinjaman = $this->m_pinjaman->add_pinjaman($data);
      $data = null;
      foreach ($keranjang as $row) {
         $data[] = [
            "buku_id" => $row->buku_id,
            "no_pinjam" => $no_pinjam
         ];
      }
      $add_buku_pinjaman = $this->m_pinjaman->add_buku_pinjaman($data);
      $data = [
         "username" => session()->username
      ];
      $delete_keranjang = $this->m_buku->delete_keranjang($data);
      if ($add_pinjaman && $add_buku_pinjaman && $delete_keranjang) {
         return json_encode([
            "error" => false,
            "msg" => "Pinjaman berhasil diajukan."
         ]);
      } else {
         return json_encode([
            "error" => true,
            "msg" => "Pinjaman gagal diajukan."
         ]);
      }
   }
}
