<?php 

session_start();
if (isset($_SESSION['unique_id'])) {
    include_once "config.php";
    $outgoing_id = $_SESSION['unique_id'];
    $incoming_id = $_POST['incoming_id'];

    $output = "";

    $sql = "SELECT messages.msg_id, messages.msg, messages.outgoing_msg_id, users.img
            FROM messages
            LEFT JOIN users ON users.unique_id = messages.outgoing_msg_id
            WHERE (messages.outgoing_msg_id = :outgoing_id AND messages.incoming_msg_id = :incoming_id)
                OR (messages.outgoing_msg_id = :incoming_id AND messages.incoming_msg_id = :outgoing_id)
            ORDER BY messages.msg_id";

    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ":outgoing_id", $outgoing_id);
    oci_bind_by_name($stmt, ":incoming_id", $incoming_id);
    oci_execute($stmt);

    while ($row = oci_fetch_assoc($stmt)) {
        if ($row['OUTGOING_MSG_ID'] == $outgoing_id) {
            $output .= '<div class="chat outgoing">
                            <div class="details">
                                <p>' . $row['MSG'] . '</p>
                            </div>
                        </div>';
        } else {
            $output .= '<div class="chat incoming">
                            <img src="php/images/' . $row['IMG'] . '" alt="">
                            <div class="details">
                                <p>' . $row['MSG'] . '</p>
                            </div>
                        </div>';
        }
    }

    if ($output === "") {
        $output .= '<div class="text">No messages are available. Once you send a message, they will appear here.</div>';
    }

    echo $output;
} else {
    header("location: ../login.php");
}




/*
session_start();
if (isset($_SESSION['unique_id'])) {
    include_once "config.php";
    $outgoing_id = $_SESSION['unique_id'];
    $incoming_id = $_POST['incoming_id'];
    $output = "";

    $sql = "SELECT messages.*, users.img
            FROM messages
            LEFT JOIN users ON users.unique_id = messages.outgoing_msg_id
            WHERE (outgoing_msg_id = :outgoing_id AND incoming_msg_id = :incoming_id)
            OR (outgoing_msg_id = :incoming_id AND incoming_msg_id = :outgoing_id)
            ORDER BY msg_id";

    $query = oci_parse($conn, $sql);
    oci_bind_by_name($query, ":outgoing_id", $outgoing_id);
    oci_bind_by_name($query, ":incoming_id", $incoming_id);
    oci_execute($query);

    if ($query === false) {
        $error = oci_error($query);
        echo "Query execution failed: " . $error['message'];
        exit;
    }

    while ($row = oci_fetch_assoc($query)) {
        if ($row['OUTGOING_MSG_ID'] == $outgoing_id) {
            $output .= '<div class="chat outgoing">
                            <div class="details">
                                <p>'. $row['MSG'] .'</p>
                            </div>
                        </div>';
        } else {
            $output .= '<div class="chat incoming">
                            <img src="php/images/'.$row['IMG'].'" alt="">
                            <div class="details">
                                <p>'. $row['MSG'] .'</p>
                            </div>
                        </div>';
        }
    }

    if (empty($output)) {
        $output .= '<div class="text">No messages are available. Once you send a message, they will appear here.</div>';
    }

    echo $output;
} else {
	// redirects to login page if user is not logged in
	
    header("location: ../login.php");
	
} */



	/* mysql code
    session_start();
    if(isset($_SESSION['unique_id'])){
        include_once "config.php";
        $outgoing_id = $_SESSION['unique_id'];
        $incoming_id = mysqli_real_escape_string($conn, $_POST['incoming_id']);
        $output = "";
        $sql = "SELECT * FROM messages LEFT JOIN users ON users.unique_id = messages.outgoing_msg_id
                WHERE (outgoing_msg_id = {$outgoing_id} AND incoming_msg_id = {$incoming_id})
                OR (outgoing_msg_id = {$incoming_id} AND incoming_msg_id = {$outgoing_id}) ORDER BY msg_id";
        $query = mysqli_query($conn, $sql);
        if(mysqli_num_rows($query) > 0){
            while($row = mysqli_fetch_assoc($query)){
                if($row['outgoing_msg_id'] === $outgoing_id){
                    $output .= '<div class="chat outgoing">
                                <div class="details">
                                    <p>'. $row['msg'] .'</p>
                                </div>
                                </div>';
                }else{
                    $output .= '<div class="chat incoming">
                                <img src="php/images/'.$row['img'].'" alt="">
                                <div class="details">
                                    <p>'. $row['msg'] .'</p>
                                </div>
                                </div>';
                }
            }
        }else{
            $output .= '<div class="text">No messages are available. Once you send message they will appear here.</div>';
        }
        echo $output;
    }else{
        header("location: ../login.php");
    }*/

?>