<?php 

namespace App\Controllers;

class Validation
{
   public function valid_role(string $str = null)
   {
      switch ($str) {
         case 'admin':
         case 'user':
            return true;
            break;
         default:
            return false;
            break;
      }
   }
}
