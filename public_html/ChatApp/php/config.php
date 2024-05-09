<?php

  //ORACLE SQL
  $hostname = 'azza.gwangju.ac.kr';
  $username = 'dbuser211694';
  $password = 'ce1234';
  $dbname = 'orcl';


  $conn = oci_connect($username, $password, $hostname.'/'.$dbname, 'AL32UTF8');
  if (!$conn) {
    $e = oci_error();
    echo "Database connection error: (error probably from config.php) " . $e['message'];
  }




  /* MYSQL CODE 
  $hostname = "localhost";
  $username = "root";
  $password = "";
  $dbname = "ChatApp";

  $conn = mysqli_connect($hostname, $username, $password, $dbname);
  if(!$conn){
    echo "Database connection error".mysqli_connect_error();
  }
  */
?>
