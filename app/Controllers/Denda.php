<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MDenda;
use CodeIgniter\Exceptions\PageNotFoundException;

class Denda extends BaseController
{
   protected $m_denda;

   public function __construct()
   {
      $this->m_denda = new MDenda();
   }

   public function daftar_denda()
   {
      $data = [
         "title" => "Daftar Denda"
      ];
      return view('admin/daftar_denda', $data + $this->data_session());
   }

   public function get_daftar_denda()
   {
      $search = $_POST['search']['value'];
      $limit = $_POST['length'];
      $start = $_POST['start'];
      $index = $_POST['order'][0]['column'];
      $field = $_POST['columns'][$index]['data'];
      $orderby = $_POST['order'][0]['dir'];
      $total_denda = $this->m_denda->get_total_denda();
      $filter_denda = $this->m_denda->get_filter_denda($search, $limit, $start, $field, $orderby);
      $total_filter_denda = $this->m_denda->get_total_filter_denda($search);
      $data = [
         "draw" => $_POST['draw'],
         "recordsTotal" => $total_denda,
         "recordsFiltered" => $total_filter_denda,
         "data" => $filter_denda
      ];
      return json_encode($data);
   }

   public function detail_denda($denda_id)
   {
      if ($result = $this->m_denda->get_detail_denda($denda_id)) {
         $data = [
            "title" => "Detail Denda",
            "data" => $result
         ];
         return view('admin/detail_denda', $data + $this->data_session());
      } else {
         throw new PageNotFoundException();
      }
   }

   public function update_denda($denda_id)
   {
      $denda = htmlentities(preg_replace('/\D/', '', $this->request->getPost('denda')), ENT_QUOTES, 'UTF-8');
      if($denda != null && !preg_match('/^[0-9]/', $denda)) {
         return json_encode([
            "error" => true,
            "msg" => "Denda tidak valid."
         ]);
      }
      $no_pinjam = htmlentities($this->request->getPost('no_pinjam'), ENT_QUOTES, 'UTF-8');
      $ket_denda = htmlentities($this->request->getPost('ket_denda'), ENT_QUOTES, 'UTF-8');
      $data = [
         "denda" => $denda,
         "ket_denda" => $ket_denda,
         "no_pinjam" => $no_pinjam
      ];
      if ($this->m_denda->update_denda($data, $denda_id)) {
         return json_encode([
            "error" => false,
            "msg" => "Denda berhasil diupdate."
         ]);
      } else {
         return json_encode([
            "error" => true,
            "msg" => "Denda gagal diupdate."
         ]);
      }
   }

   public function delete_denda($denda_id)
   {
      if ($this->m_denda->delete_denda($denda_id)) {
         return json_encode([
            "error" => false,
            "msg" => "Denda berhasil dihapus."
         ]);
      } else {
         return json_encode([
            "error" => false,
            "msg" => "Denda berhasil dihapus."
         ]);
      }
   }

   public function laporan_denda()
   {
      $data = [
         "title" => "Laporan Denda",
         "total_denda" => $this->m_denda->get_total_pendapatan_denda(),
         "data" => $this->m_denda->laporan_denda()
      ];
      return view('admin/laporan/denda', $data + $this->data_session());
   }
}
