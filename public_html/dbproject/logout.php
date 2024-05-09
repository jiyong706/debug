<?php
  session_start();
  unset($_SESSION["userid"]);
  
  echo("
       <script>
          location.href = '1. main.php';
         </script>
       ");
?>