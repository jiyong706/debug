<?php 

   session_start();
    if(isset($_SESSION['unique_id'])){
        include_once "config.php";
        $outgoing_id = $_SESSION['unique_id'];
        $incoming_id = $_POST['incoming_id'];
        $message = $_POST['message'];
        if(!empty($message)){
            $sql = oci_parse($conn, "INSERT INTO messages (incoming_msg_id, outgoing_msg_id, msg)
                                    VALUES (:incoming_id, :outgoing_id, :message)");
            oci_bind_by_name($sql, ":incoming_id", $incoming_id);
            oci_bind_by_name($sql, ":outgoing_id", $outgoing_id);
            oci_bind_by_name($sql, ":message", $message);
            oci_execute($sql) or die();
        }
    } else {
		
        header("location: ../login.php");
		
    }



	/*ORALCE SQL CODE bugged i think
	session_start();
	if (isset($_SESSION['unique_id'])) {
		include_once "config.php";
		$outgoing_id = $_SESSION['unique_id'];
		$incoming_id = $_POST['incoming_id'];
		$message = $_POST['message'];
			if (!empty($message)) {
				$sql = oci_parse($conn, "INSERT INTO messages (incoming_msg_id, outgoing_msg_id, msg)
                                VALUES (:incoming_id, :outgoing_id, :message)");
				oci_bind_by_name($sql, ":incoming_id", $incoming_id);
				oci_bind_by_name($sql, ":outgoing_id", $outgoing_id);
				oci_bind_by_name($sql, ":message", $message);
				oci_execute($sql) or die();
			}
	} else {
    header("location: ../login.php");
} */

	
	/* MYSQL CODE
    session_start();
    if(isset($_SESSION['unique_id'])){
        include_once "config.php";
        $outgoing_id = $_SESSION['unique_id'];
        $incoming_id = mysqli_real_escape_string($conn, $_POST['incoming_id']);
        $message = mysqli_real_escape_string($conn, $_POST['message']);
        if(!empty($message)){
            $sql = mysqli_query($conn, "INSERT INTO messages (incoming_msg_id, outgoing_msg_id, msg)
                                        VALUES ({$incoming_id}, {$outgoing_id}, '{$message}')") or die();
        }
    }else{
        header("location: ../login.php");
    }
	*/

    
?>