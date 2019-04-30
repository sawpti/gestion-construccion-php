<?php
require_once('recaptchalib.php');
 $privatekey = "6Lf88jgUAAAAAGRqGvmBHzOdu330WJeBDy0gT34J";
 $resp = recaptcha_check_answer ($privatekey,$_SERVER["REMOTE_ADDR"],$_POST["recaptcha_challenge_field"],$_POST["recaptcha_response_field"]);
 
 if (!$resp->is_valid) {
      //ERROR EN EL CAPTCHA
      echo 0;
 }else{
      //CAPTCHA CORRECTO
      echo 1;
 }
 
 ?>