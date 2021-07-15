<?php

namespace App\Models;

use CodeIgniter\Model;

class MPinjaman extends Model
{
   protected $table = 'pinjaman';
   protected $primaryKey = 'id';
   protected $allowedFields = [
      'no_pinjam', 'username', 'buku_id', 'start', 'end', 'status', 'tgl_return'
   ];
   protected $dateFormat = 'date';
   protected $useTimestamps = true;
   protected $createdField = 'tanggal';
   protected $updatedField = false;
   protected $returnType = 'object';

   protected function join_table()
   {
      $this->join('buku_pinjaman as b', 'b.no_pinjam = pinjaman.no_pinjam', 'inner');
      $this->join('buku as c', 'b.buku_id = c.id', 'inner');
   }

   public function get_no_pinjam()
   {
      $this->select("MAX(RIGHT(no_pinjam,4)) as no_pinjam_last");
      $this->where("DATE(tanggal) = CURDATE()");
      if ($this->countAllResults(false) > 0) {
         foreach ($this->get()->getFirstRow() as $row) {
            $no = (int)$row + 1;
            $no = sprintf("%04s", $no);
         }
      } else {
         $no = "0001";
      }
      return "PJM" . date('Ymd') . $no;
   }

   public function get_pinjaman()
   {
      return $this->get()->getResult();
   }

   public function get_total_pinjaman()
   {
      return $this->countAllResults();
   }

   public function get_filter_pinjaman($search, $limit, $start, $field, $orderby, $status = null)
   {
      $this->select("pinjaman.no_pinjam, pinjaman.username, pinjaman.start, pinjaman.end, pinjaman.status, GROUP_CONCAT(c.nama_buku SEPARATOR ', ') as nama_buku");
      $this->join_table();
      if ($status != null) {
         $this->where('status', $status);
      }
      $this->groupStart();
      $this->like('pinjaman.no_pinjam', $search);
      $this->orLike('username', $search);
      $this->orLike('nama_buku', $search);
      $this->orLike('start', $search);
      $this->orLike('end', $search);
      $this->groupEnd();
      $this->groupBy('pinjaman.no_pinjam');
      $this->orderBy($field, $orderby);
      $this->limit($limit, $start);
      return $this->get()->getResult();
   }

   public function get_total_filter_pinjaman($search, $status)
   {
      $this->select("DISTINCT COUNT(pinjaman.no_pinjam) OVER() AS count");
      $this->join_table();
      if ($status != null) {
         $this->where('status', $status);
      }
      $this->groupStart();
      $this->like('pinjaman.no_pinjam', $search);
      $this->orLike('username', $search);
      $this->orLike('nama_buku', $search);
      $this->orLike('start', $search);
      $this->orLike('end', $search);
      $this->groupEnd();
      $this->groupBy('pinjaman.no_pinjam');
      return (int) (($count = $this->get()->getFirstRow()) ? $count->count : 0);
   }

   public function get_detail_pinjaman($no_pinjam)
   {
      $this->select('pinjaman.*, b.buku_id, c.nama_buku, c.kode_buku, c.penerbit, c.isbn, denda.denda, denda.ket_denda');
      $this->join_table();
      $this->join('denda', 'denda.no_pinjam = pinjaman.no_pinjam', 'left');
      $this->where('pinjaman.no_pinjam', $no_pinjam);
      return $this->get()->getResult();
   }

   public function add_pinjaman($data)
   {
      return $this->save($data);
   }

   public function add_buku_pinjaman($data)
   {
      $builder = $this->db->table('buku_pinjaman');
      return $builder->insertBatch($data);
   }

   public function update_pinjaman($data, $no_pinjam)
   {
      $this->set($data);
      $this->where('no_pinjam', $no_pinjam);
      return $this->update();
   }

   public function delete_pinjaman($no_pinjam)
   {
      $this->where('no_pinjam', $no_pinjam);
      return $this->delete();
   }

   public function delete_buku_pinjaman($no_pinjam)
   {
      $builder = $this->db->table('buku_pinjaman');
      $builder->where('no_pinjam', $no_pinjam);
      return $builder->delete();
   }

   public function week()
   {
      $this->select('DATE_FORMAT(tanggal, "%d/%m") as tanggal');
      $this->where('tanggal >= (DATE(NOW()) - INTERVAL 7 DAY)');
      for ($i = 0; $i >= -6; $i--) {
         $tgl[date('d/m', strtotime($i . ' day'))] = 0;
      }
      foreach ($this->get()->getResult() as $row) {
         $tgl[$row->tanggal] = 1 + $tgl[$row->tanggal];
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
      $this->select('EXTRACT(MONTH FROM tanggal) as bulan');
      $this->where('YEAR(tanggal) = YEAR(NOW())');
      $bln = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
      foreach ($this->get()->getResult() as $row) {
         // mengubah bulan - 1. ex: bulan 12 menjadi bulan 11
         $bln[$row->bulan - 1] = 1 + $bln[$row->bulan - 1];
      }
      $data = "";
      for ($i = 0; $i < 12; $i++) {
         $data .= $bln[$i];
         $data .= ",";
      }
      return ["on_this_year" => $data];
   }

   public function pinjaman_jatuh_tempo()
   {
      $this->where('status = 0');
      $this->where('CURDATE() >= DATE(end)');
      return $this->countAllResults();
   }

   public function laporan_pinjaman()
   {
      return $this->week() + $this->year() + ["jatuh_tempo" => $this->pinjaman_jatuh_tempo()];
   }

   public function pinjaman_today()
   {
      $this->select('no_pinjam, username');
      $this->where('DATE(tanggal) = CURDATE()');
      return $this->get()->getResult();
   }
}
