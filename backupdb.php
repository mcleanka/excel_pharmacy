<?php
   $db = new mysqli('localhost','root','','citipharm2');
   $table_name = "tbl_customer";
   $backup_file  = "C:\xampp\htdocs\citipharm2\Backups\customer.sql";
   $qry = "LOAD DATA INFILE '$backup_file' INTO TABLE $table_name";
   $res = $db->query($qry);
 ?>