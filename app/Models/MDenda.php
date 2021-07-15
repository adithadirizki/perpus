<?php 

namespace App\Models;

use CodeIgniter\Model;

class MDenda extends Model
{
   protected $table = 'denda';
   protected $allowedFields = [
      'denda', 'ket_denda'
   ];
   protected $dateFormat = 'date';
   protected $useTimestamps = true;
   protected $createdField = 'tanggal';
   protected $updatedField = false;
   protected $returnType = 'object';

   public function add_denda($data)
   {
      return $this->insert($data);
   }

   public function get_total_denda()
   {
      return $this->countAllResults();
   }

   public function get_filter_denda($search, $limit, $start, $field, $orderby)
   {
      $this->like('no_pinjam', $search);
      $this->orLike('denda', $search);
      $this->orLike('tanggal', $search);
      $this->orderBy($field, $orderby);
      $this->limit($limit, $start);
      return $this->get()->getResult();
   }

   public function get_total_filter_denda($search)
   {
      $this->like('no_pinjam', $search);
      $this->orLike('denda', $search);
      $this->orLike('tanggal', $search);
      return $this->countAllResults();
   }

   public function get_detail_denda($denda_id)
   {
      $this->where('id', $denda_id);
      return $this->get()->getFirstRow();
   }

   public function update_denda($data, $denda_id)
   {
      $this->set($data);
      $this->where('id', $denda_id);
      return $this->update();
   }

   public function delete_denda($denda_id)
   {
      $this->where('id', $denda_id);
      return $this->delete();
   }

   public function week()
   {
      $this->select('DATE_FORMAT(tanggal, "%d/%m") as tanggal, denda');
      $this->where('tanggal >= (DATE(NOW()) - INTERVAL 7 DAY)');
      for ($i = 0; $i >= -6; $i--) {
         $tgl[date('d/m', strtotime($i . ' day'))] = 0;
      }
      foreach ($this->get()->getResult() as $row) {
         $tgl[$row->tanggal] = $row->denda + $tgl[$row->tanggal];
      }
      $this_week = array_sum($tgl);
      $today = date('d/m');
      $yesterday = date('d/m', strtotime('yesterday'));
      $data = "";
      foreach (array_reverse($tgl) as $row) {
         $data .= $row;
         $data .= ",";
      }
      $label = "";
      foreach (array_reverse(array_keys($tgl)) as $row) {
         $label .= "'$row'";
         $label .= ",";
      }
      return [
         "label" => $label,
         "on_this_week" => $data,
         "today" => $tgl[$today],
         "yesterday" => $tgl[$yesterday],
         "this_week" => $this_week
      ];
   }

   public function year()
   {
      $this->select('EXTRACT(MONTH FROM tanggal) as bulan, denda');
      $this->where('YEAR(tanggal) = YEAR(NOW())');
      $bln = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
      foreach ($this->get()->getResult() as $row) {
         // mengubah bulan - 1. ex: bulan 12 menjadi bulan 11
         $bln[$row->bulan - 1] = $row->denda + $bln[$row->bulan - 1];
      }
      $data = "";
      for ($i=0; $i < 12; $i++) {
            $data .= $bln[$i];
            $data .= ",";
      }
      return ["on_this_year" => substr($data, 0, strlen($data) - 1)];
   }

   public function laporan_denda()
   {
      return $this->week() + $this->year();
   }

   public function get_total_pendapatan_denda()
   {
      $denda = 0;
      foreach ($this->get()->getResult() as $row) {
         $denda = $denda + $row->denda;
      }
      return $denda;
   }
}


?>