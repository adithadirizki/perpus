<?php

namespace App\Models;

use CodeIgniter\Model;

class MUser extends Model
{
   protected $table = 'user';
   protected $allowedFields = [
      'nama', 'username', 'email', 'password', 'role', 'foto', 'token_aktivasi', 'status', 'alamat', 'no_hp', 'tanggal'
   ];
   protected $dateFormat = 'date';
   protected $useTimestamps = true;
   protected $createdField = 'tanggal';
   protected $updatedField = false;
   protected $returnType = 'object';

   public function verifikasi_email($token)
   {
      $this->select('status');
      $this->where('token_aktivasi', $token);
      if($data = $this->get()->getFirstRow('array')) {
         if($data['status'] == 0) {
            $this->set('status', '1');
            $this->where('token_aktivasi', $token);
            if($this->update()) {
               return ["msg" => "Verrifikasi email berhasil. Silahkan login!"];
            } else {
               return ["msg" => "Verifikasi email gagal."];
            }
         } else {
            return ["msg" => "Email sudah terverifikasi."];
         }
      } else {
         return ["msg" => "Token aktivasi tidak valid."];
      }
   }

   public function get_user($data)
   {
      $this->select('username, email, token_aktivasi, status');
      $this->where($data);
      return $this->get()->getFirstRow();
   }

   public function get_total_user()
   {
      return $this->countAllResults();
   }

   public function get_filter_user($search, $limit, $start, $field, $orderby)
   {
      $this->like('nama', $search);
      $this->orLike('username', $search);
      $this->orLike('email', $search);
      $this->orderBy($field, $orderby);
      $this->limit($limit, $start);
      return $this->get()->getResult();
   }

   public function get_total_filter_user($search)
   {
      $this->like('nama', $search);
      $this->orLike('username', $search);
      $this->orLike('email', $search);
      return $this->countAllResults();
   }

   public function get_detail_user($username)
   {
      $this->where('username', $username);
      return $this->get()->getFirstRow();
   }

   public function login($data)
   {
      $this->where('email', $data['email']);
      $result = $this->get()->getFirstRow();
      if(!$result) {
         return ["errorMsg" => "Email atau Password yang digunakan salah"];
      }
      if(!password_verify($data['password'], $result->password)) {
         return ["errorMsg" => "Email atau Password yang digunakan salah"];
      }
      if($result->status == 0) {
         return ["errorMsg" => "Akun tidak aktif, harap verifikasi email terlebih dahulu."];
      }
      return $result;
   }

   public function add_user($data)
   {
      return $this->insert($data);
   }

   public function update_user($data, $username)
   {
      $this->set($data);
      $this->where('username', $username);
      return $this->update();
   }

   public function delete_user($username)
   {
      $this->where('username', $username);
      return $this->delete();
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
      for ($i=0; $i < 12; $i++) {
            $data .= $bln[$i];
            $data .= ",";
      }
      return ["on_this_year" => $data];
   }

   public function laporan_user()
   {
      return $this->week() + $this->year();
   }

   public function user_today()
   {
      $this->select('username, nama, foto');
      $this->where('DATE(tanggal) = CURDATE()');
      return $this->get()->getResult();
   }
}
