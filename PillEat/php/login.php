<?php 

	//ORACLE SQL CODE
	session_start();
	include_once "config.php";
	$email = $_POST['email'];
	$password = $_POST['password'];


	$email = addslashes($email);
	$password = addslashes($password);

	if (!empty($email) && !empty($password)) {
		$sql = oci_parse($conn, "SELECT * FROM users WHERE email = :email");
		oci_bind_by_name($sql, ":email", $email);
		oci_execute($sql);
			if ($row = oci_fetch_assoc($sql)) {
				$user_pass = md5($password);
				$enc_pass = $row['PASSWORD'];
				if ($user_pass === $enc_pass) {
					$status = "Active now";
					$sql2 = oci_parse($conn, "UPDATE users SET status = :status WHERE unique_id = :unique_id");
					oci_bind_by_name($sql2, ":status", $status);
					oci_bind_by_name($sql2, ":unique_id", $row['UNIQUE_ID']);
					if (oci_execute($sql2)) {
						$_SESSION['unique_id'] = $row['UNIQUE_ID'];
						echo "success";
					} 
					else{
						echo "Something went wrong. Please try again!";
					}
				} 
				else{
					echo "Email or Password is Incorrect!";
				}
			} 
			else {
				echo "$email - This email does not exist!";
			}
	}
	else 
	{
		echo "All input fields are required!";
	}






	/* MYSQL CODE
    session_start();
    include_once "config.php";
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    if(!empty($email) && !empty($password)){
        $sql = mysqli_query($conn, "SELECT * FROM users WHERE email = '{$email}'");
        if(mysqli_num_rows($sql) > 0){
            $row = mysqli_fetch_assoc($sql);
            $user_pass = md5($password);
            $enc_pass = $row['password'];
            if($user_pass === $enc_pass){
                $status = "Active now";
                $sql2 = mysqli_query($conn, "UPDATE users SET status = '{$status}' WHERE unique_id = {$row['unique_id']}");
                if($sql2){
                    $_SESSION['unique_id'] = $row['unique_id'];
                    echo "success";
                }else{
                    echo "Something went wrong. Please try again!";
                }
            }else{
                echo "Email or Password is Incorrect!";
            }
        }else{
            echo "$email - This email not Exist!";
        }
    }else{
        echo "All input fields are required!";
    }
	*/
?>