<?php

namespace App\Models;

use CodeIgniter\Model;

class MHome extends Model
{
   protected $table = 'pengaturan';
   protected $allowedFields = [
      'nama_app', 'logo'
   ];
   protected $createdField = false;
   protected $returnType = 'object';

   public function setting()
   {
      return $this->get()->getFirstRow();
   }

   public function update_setting($data)
   {
      $this->set($data);
      return $this->update();
   }
}
