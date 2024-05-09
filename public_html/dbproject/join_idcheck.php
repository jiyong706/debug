<?php
   $id = $_GET["id"];

   if(!$id) 
   {
      echo("아이디를 입력해 주세요!");
   }
   else
   {
      $conn = oci_connect("dbuser211927", "754124", "azza.gwangju.ac.kr/orcl","AL32UTF8");
 
      $sql = "select * from users where id='$id'";
      $result = oci_parse($conn, $sql);
      oci_execute($result);
      $num_record = oci_fetch_array($result);
     
      if ($num_record)
      {
         echo $id." 아이디는 중복됩니다. 다른 아이디를 사용해 주세요.";
      }
      else
      {
         echo $id." 아이디는 사용 가능합니다.";
      }
    
      oci_close($conn);
   }
?>