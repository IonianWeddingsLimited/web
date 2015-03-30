<?php

function Generate_Password($length=10){

   list($usec, $sec) = explode(' ', microtime());
   srand((float) $sec + ((float) $usec * 100000));

   $chars = "0123456789abcdfghjkmnpqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_!";

   $password  = "";
   $counter   = 0;

   while ($counter < $length) {
     $actChar = substr($chars, rand(0, strlen($chars)-1), 1);

     // All character must be different
     if (!strstr($password, $actChar)) {
        $password .= $actChar;
        $counter++;
     }
   }

   return $password;

}

