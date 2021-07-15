<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use Config\Email as ConfigEmail;

class Email extends BaseController
{
   public function sendEmail($emailTo, $subject, $msg)
   {
      $email_conf = new ConfigEmail;
      $email = \Config\Services::email();
      $email->setFrom($email_conf->fromEmail, $email_conf->fromName);
      $email->setTo($emailTo);
      $email->setSubject($subject);
      $email->setMessage($msg);
      return $email->send();
      // echo $email->printDebugger(['headers']);
   }
}


?>