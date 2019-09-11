<?php
   date_default_timezone_set("Asia/Jakarta");

   function logToFile($msg)
   { 
   // open file
   $filename=date('m-d-Y').'.txt';
   $fd = fopen('logupload/'.$filename, "a+");
 
   // append date/time to message
   $str = "[" . date("Y-m-d H:i:s", time()) . "] " . $msg; 
  // fputs( $fd, $filename, strlen($msg) );
   fwrite($fd, $str . "\n");
   fclose( $fd );
   chmod($fd, 0777);

	}	

  function logError($msg)
   { 
   // open file
   $filename='error_'.date('m-d-Y').'.txt';
   $fd = fopen('logupload/'.$filename, "a+");
 
   // append date/time to message
   $str = "[" . date("Y-m-d H:i:s", time()) . "] " . $msg; 
  // fputs( $fd, $filename, strlen($msg) );
   fwrite($fd, $str . "\n");
   fclose( $fd );
   chmod($fd, 0777);

	}	
   ?>
   

