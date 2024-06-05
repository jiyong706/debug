<?php
    
session_start();
include_once "config.php";
$outgoing_id = $_SESSION['unique_id'];

//$sql = "SELECT * FROM users WHERE unique_id <> :outgoing_id ORDER BY user_id DESC";
$sql = "SELECT * FROM users";
$statement = oci_parse($conn, $sql);
//oci_bind_by_name($statement, ":outgoing_id", $outgoing_id);
oci_execute($statement);

$output = "";
$row_count = oci_fetch_all($statement, $users);


if ($row_count == 0) {
    $output .= "No users are available to chat";
} elseif ($row_count > 0) {
    include_once "data.php";
}

echo $output;








    /*
    session_start();
    include_once "config.php";
    $outgoing_id = $_SESSION['UNIQUE_ID'];
    $sql = "SELECT * FROM users WHERE NOT unique_id = :outgoing_id ORDER BY user_id DESC";
    
    $statement = oci_parse($conn, $sql);
   

    oci_bind_by_name($statement, ":outgoing_id", $outgoing_id);
    oci_execute($statement);
   
    $output = "";

    $result = oci_fetch_all($statement, $rows);
    //$row = oci_fetch_assoc($statement);
    $row_count = $row['ROW_COUNT'];

    if ($row_count == null) {
    $output .= "No users are available to chat ";
        include_once "data.php";
    } 
    elseif ($row_count > 0) {
        include_once "data.php";
    }
    echo $output;
    echo "Total users: " . $row_count;
    */


    

	/* Mysql Code
    session_start();
    include_once "config.php";
    $outgoing_id = $_SESSION['unique_id'];
    $sql = "SELECT * FROM users WHERE NOT unique_id = {$outgoing_id} ORDER BY user_id DESC";
    $query = mysqli_query($conn, $sql);
    $output = "";
    if(mysqli_num_rows($query) == 0){
        $output .= "No users are available to chat";
    }elseif(mysqli_num_rows($query) > 0){
        include_once "data.php";
    }
    echo $output;
	*/
?>