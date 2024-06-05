<?php
	//ORACLE CODE
session_start();
if (isset($_SESSION['unique_id'])) {
    include_once "config.php";
    $logout_id = $_GET['logout_id'];
    if (isset($logout_id)) {
        $status = "Offline now";
        $sql = oci_parse($conn, "UPDATE users SET status = :status WHERE unique_id = :logout_id");
        oci_bind_by_name($sql, ":status", $status);
        oci_bind_by_name($sql, ":logout_id", $logout_id);
        oci_execute($sql);
        if (oci_num_rows($sql) > 0) {
            session_unset();
            session_destroy();
            header("location: ../login.php");
            exit(); // Add this line to stop further execution
        }
    } else {
        // Remove the redirection below to prevent unnecessary redirects
         header("location: ../users.php");
    }
} else {
    header("location: ../login.php");
    exit(); // Add this line to stop further execution
}

	/* MYSQL CODE
    session_start();
    if(isset($_SESSION['unique_id'])){
        include_once "config.php";
        $logout_id = mysqli_real_escape_string($conn, $_GET['logout_id']);
        if(isset($logout_id)){
            $status = "Offline now";
            $sql = mysqli_query($conn, "UPDATE users SET status = '{$status}' WHERE unique_id={$_GET['logout_id']}");
            if($sql){
                session_unset();
                session_destroy();
                header("location: ../login.php");
            }
        }else{
            header("location: ../users.php");
        }
    }else{  
        header("location: ../login.php");
    }*/
?>