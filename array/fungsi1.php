<?php
   function logToFile($filename, $msg)
   { 
   // open file
   $fd = fopen($filename, "a");
   // append date/time to message
   $today = mktime(0,0,0,date("m"),date("d"),date("Y"));
   $str = "[" . date("Y/m/d h:i:s", mktime()) . "] " . $msg; 
   // write string
   fwrite($fd, $str . "\n");
   // close file
   fclose($fd);
   }
   
     function LogToSql($filename, $msg, $kueri)
   { 
   // open file
   $fd = fopen($filename, "a");
   // append date/time to message
   $today = mktime(0,0,0,date("m"),date("d"),date("Y"));
   $str = "[" . date("Y/m/d h:i:s", mktime()) . "] " . $msg. $kueri; 
   // write string
   fwrite($fd, $str . "\n");
   // close file
   fclose($fd);
   }
   ?>
