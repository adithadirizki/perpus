<?php

namespace App\Models;

use CodeIgniter\Model;

class MBuku extends Model
{
   protected $table = 'buku';
   protected $allowedFields = [
      'nama_buku', 'kode_buku', 'kategori_id', 'jumlah', 'foto', 'deskripsi', 'penerbit', 'pengarang', 'tahun_terbit', 'isbn'
   ];
   protected $dateFormat = 'date';
   protected $useTimestamps = true;
   protected $createdField = 'tanggal';
   protected $updatedField = false;
   protected $returnType = 'object';

   protected function join_table()
   {
      $this->join('kategori', 'kategori.id = buku.kategori_id', 'inner');
   }

   public function get_total_buku()
   {
      return $this->countAllResults();
   }

   public function get_filter_buku($search, $limit, $start, $field, $orderby, $kategori = null, $query = "*")
   {
      $this->select($query);
      $this->join_table();
      if ($kategori != null) {
         $this->where('kategori_id', $kategori);
      }
      $this->groupStart();
      $this->like('nama_buku', $search);
      $this->orLike('kode_buku', $search);
      $this->orLike('pengarang', $search);
      $this->orLike('penerbit', $search);
      $this->orLike('tahun_terbit', $search);
      $this->orLike('isbn', $search);
      $this->orLike('nama_kategori', $search);
      $this->groupEnd();
      $this->orderBy($field, $orderby);
      $this->limit($limit, $start);
      return $this->get()->getResult();
   }

   public function get_total_filter_buku($search, $kategori = null)
   {
      $this->join_table();
      if ($kategori != null) {
         $this->where('kategori_id', $kategori);
      }
      $this->groupStart();
      $this->like('nama_buku', $search);
      $this->orLike('kode_buku', $search);
      $this->orLike('pengarang', $search);
      $this->orLike('penerbit', $search);
      $this->orLike('tahun_terbit', $search);
      $this->orLike('isbn', $search);
      $this->orLike('nama_kategori', $search);
      $this->groupEnd();
      return $this->countAllResults();
   }

   public function get_detail_buku($buku_id)
   {
      $this->where('id', $buku_id);
      return $this->get()->getFirstRow();
   }

   public function add_buku($data)
   {
      return $this->save($data);
   }

   public function update_buku($data, $buku_id)
   {
      $this->set($data);
      $this->where('id', $buku_id);
      return $this->update();
   }

   public function delete_buku($buku_id)
   {
      $this->where('id', $buku_id);
      return $this->delete();
   }

   public function pinjam_buku($data)
   {
      $builder = $this->db->table('keranjang_buku');
      $builder->where($data);
      if ($builder->get()->getFirstRow()) {
         return ["errorMsg" => "Buku sudah ada dikeranjang."];
      }
      $builder = $this->db->table('keranjang_buku');
      $builder->set($data);
      return $builder->insert();
   }

   public function get_keranjang()
   {
      $this->join('keranjang_buku', 'buku_id = buku.id');
      $this->where('username', session()->username);
      return $this->get()->getResult();
   }

   public function get_total_keranjang_buku()
   {
      $builder = $this->db->table('keranjang_buku');
      $builder->where('username', session()->username);
      return $builder->countAllResults();
   }

   public function delete_keranjang($data)
   {
      $builder = $this->db->table('keranjang_buku');
      $builder->where($data);
      return $builder->delete();
   }
}
