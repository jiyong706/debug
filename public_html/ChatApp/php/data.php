<?php
    
session_start();
include_once "config.php";
$outgoing_id = $_SESSION['unique_id'];




// Query to fetch users
$sql4 = "SELECT * FROM users WHERE unique_id <> :outgoing_id ORDER BY user_id DESC";

$statement4 = oci_parse($conn, $sql4);
oci_bind_by_name($statement4, ":outgoing_id", $outgoing_id);
oci_execute($statement4);


while ($row = oci_fetch_assoc($statement4)) {
    // Query to fetch the latest message for each user
    $sql2 = "SELECT * FROM messages WHERE (incoming_msg_id = :unique_id OR outgoing_msg_id = :unique_id)
             AND (outgoing_msg_id = :outgoing_id OR incoming_msg_id = :outgoing_id)
             ORDER BY msg_id DESC";
             
    $statement2 = oci_parse($conn, $sql2);
    oci_bind_by_name($statement2, ":unique_id", $row['UNIQUE_ID']);
    oci_bind_by_name($statement2, ":outgoing_id", $outgoing_id);
    oci_execute($statement2);

    // Fetch the latest message
    $row2 = oci_fetch_assoc($statement2);
    if ($row2) {
        $result = $row2['MSG'];
        $msg = strlen($result) > 28 ? substr($result, 0, 28) . '...' : $result;
        $you = $outgoing_id == $row2['OUTGOING_MSG_ID'] ? "You: " : "";
    } else {
        $msg = "No message available";
        $you = "";
    }

    $offline = $row['STATUS'] == "Offline now" ? "offline" : "";


    // Get the current timestamp using JavaScript on the client-side
    $timestamp = '<span class="timestamp"><script>document.write(new Date().toLocaleTimeString())</script></span>';



    $output .= '<a href="chat.php?user_id=' . $row['UNIQUE_ID'] . '">
                    <div class="content">
                        <img src="php/images/' . $row['IMG'] . '" alt="">
                        <div class="details">
                            <span>' . $row['FNAME'] . " " . $row['LNAME'] . '</span>
                           <p>' . $you . $msg . '</p>
                            ' . $timestamp . '
                        </div>
                    </div>
                    <div class="status-dot ' . $offline . '"><i class="fas fa-circle"></i></div>
                </a>';
}






    /* almost working code
        session_start();
        include_once "config.php";
        $outgoing_id = $_SESSION['unique_id'];
        $sql4 = "SELECT * FROM users WHERE unique_id <> :outgoing_id ORDER BY user_id DESC";;
        
        $statement4 = oci_parse($conn, $sql4);
        oci_bind_by_name($statement4, ":outgoing_id", $outgoing_id);
        oci_execute($statement4);
        
        $sql2 = "SELECT * FROM messages WHERE (incoming_msg_id = :unique_id OR outgoing_msg_id = :unique_id)
                AND (outgoing_msg_id = :outgoing_id OR incoming_msg_id = :outgoing_id)
                ORDER BY msg_id DESC FETCH FIRST 1 ROWS ONLY";

        $statement2 = oci_parse($conn, $sql2);
        oci_bind_by_name($statement2, ":unique_id", $row['UNIQUE_ID']);
        oci_bind_by_name($statement2, ":outgoing_id", $outgoing_id);
        oci_execute($statement2);

        $row2 = oci_fetch_assoc($statement2);
        if ($row2) {
            $result = $row2['MSG'];
            $msg = strlen($result) > 28 ? substr($result, 0, 28) . '...' : $result;
            $you = $outgoing_id == $row2['OUTGOING_MSG_ID'] ? "You: " : "";
        } else {
            $msg = "No message available";
            $you = "";
        }

        $hid_me = $outgoing_id == $row['UNIQUE_ID'] ? "hide" : "";


        while($row = oci_fetch_assoc($statement4)){

        
       

        $offline = $row['STATUS'] == "Offline now" ? "offline" : "";
        $output .= '<a href="chat.php?user_id=' . $row['UNIQUE_ID'] . '">
                    <div class="content">
                        <img src="php/images/' . $row['IMG'] . '" alt="">
                        <div class="details">
                            <span>' . $row['FNAME'] . " " . $row['LNAME'] . '</span>
                            <p>' . $you . $msg .' </p>
                        </div>
                    </div>
                    <div class="status-dot ' . $offline . ' "><i class="fas fa-circle"></i></div>
                </a>';
        
        }
    */
    


   /*

    while ($row = oci_fetch_assoc($statement)) {
    $sql2 = "SELECT * FROM messages WHERE (incoming_msg_id = :unique_id OR outgoing_msg_id = :unique_id)
                AND (outgoing_msg_id = :outgoing_id OR incoming_msg_id = :outgoing_id)
                ORDER BY msg_id DESC FETCH FIRST 1 ROWS ONLY";
    $statement2 = oci_parse($conn, $sql2);
    oci_bind_by_name($statement2, ":unique_id", $row['UNIQUE_ID']);
    oci_bind_by_name($statement2, ":outgoing_id", $outgoing_id);
    oci_execute($statement2);

    $row2 = oci_fetch_assoc($statement2);
    if ($row2) {
        $result = $row2['MSG'];
        $msg = strlen($result) > 28 ? substr($result, 0, 28) . '...' : $result;
        $you = $outgoing_id == $row2['OUTGOING_MSG_ID'] ? "You: " : "";
    } else {
        $msg = "No message available";
        $you = "";
    }

    $offline = $row['STATUS'] == "Offline now" ? "offline" : "";
    $hid_me = $outgoing_id == $row['UNIQUE_ID'] ? "hide" : "";





    $output .= '<a href="chat.php?user_id=' . $row['UNIQUE_ID'] . '">
                    <div class="content">
                        <img src="php/images/' . $row['IMG'] . '" alt="">
                        <div class="details">
                            <span>' . $row['FNAME'] . " " . $row['LNAME'] . '</span>
                            <p>' . $you . $msg . '</p>
                        </div>
                    </div>
                    <div class="status-dot ' . $offline . '"><i class="fas fa-circle"></i></div>
                </a>';
} */




	
	/* MYSQL CODE
    while($row = mysqli_fetch_assoc($query)){
        $sql2 = "SELECT * FROM messages WHERE (incoming_msg_id = {$row['unique_id']}
                OR outgoing_msg_id = {$row['unique_id']}) AND (outgoing_msg_id = {$outgoing_id} 
                OR incoming_msg_id = {$outgoing_id}) ORDER BY msg_id DESC LIMIT 1";
        $query2 = mysqli_query($conn, $sql2);
        $row2 = mysqli_fetch_assoc($query2);
        (mysqli_num_rows($query2) > 0) ? $result = $row2['msg'] : $result ="No message available";
        (strlen($result) > 28) ? $msg =  substr($result, 0, 28) . '...' : $msg = $result;
        if(isset($row2['outgoing_msg_id'])){
            ($outgoing_id == $row2['outgoing_msg_id']) ? $you = "You: " : $you = "";
        }else{
            $you = "";
        }
        ($row['status'] == "Offline now") ? $offline = "offline" : $offline = "";
        ($outgoing_id == $row['unique_id']) ? $hid_me = "hide" : $hid_me = "";

        $output .= '<a href="chat.php?user_id='. $row['unique_id'] .'">
                    <div class="content">
                    <img src="php/images/'. $row['img'] .'" alt="">
                    <div class="details">
                        <span>'. $row['fname']. " " . $row['lname'] .'</span>
                        <p>'. $you . $msg .'</p>
                    </div>
                    </div>
                    <div class="status-dot '. $offline .'"><i class="fas fa-circle"></i></div>
                </a>';
    }*/
?>