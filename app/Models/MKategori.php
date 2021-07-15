<?php

namespace App\Models;

use CodeIgniter\Model;

class MKategori extends Model
{
   protected $table = 'kategori';
   protected $allowedFields = [
      'nama_kategori'
   ];
   protected $dateFormat = 'date';
   protected $useTimestamps = true;
   protected $createdField = 'tanggal';
   protected $updatedField = false;
   protected $returnType = 'object';
   
   public function get_kategori()
   {
      return $this->get()->getResult();
   }

   public function get_total_kategori()
   {
      return $this->countAllResults();
   }

   public function get_filter_kategori($search, $limit, $start, $field, $orderby)
   {
      $this->like('nama_kategori', $search);
      $this->orderBy($field, $orderby);
      $this->limit($limit, $start);
      return $this->get()->getResult();
   }

   public function get_total_filter_kategori($search)
   {
      $this->like('nama_kategori', $search);
      return $this->countAllResults();
   }

   public function get_detail_kategori($kategori_id)
   {
      $this->where('id', $kategori_id);
      return $this->get()->getFirstRow();
   }

   public function add_kategori($data)
   {
      return $this->insert($data);
   }

   public function update_kategori($data, $kategori_id)
   {
      $this->set($data);
      $this->where('id', $kategori_id);
      return $this->update();
   }

   public function delete_kategori($kategori_id)
   {
      $this->where('id', $kategori_id);
      return $this->delete();
   }

}