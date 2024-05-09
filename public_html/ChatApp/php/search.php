<?php





session_start();
include_once "config.php";

$outgoing_id = $_SESSION['unique_id'];
$searchTerm = strtolower($_POST['searchTerm']); // Convert search term to lowercase

// Split the search term into first name and last name
$searchTerms = explode(' ', $searchTerm);
$firstName = isset($searchTerms[0]) ? $searchTerms[0] : '';
$lastName = isset($searchTerms[1]) ? $searchTerms[1] : '';

$sql = "SELECT * FROM users WHERE unique_id != :outgoing_id AND (LOWER(fname) LIKE '%' || :firstName || '%' AND LOWER(lname) LIKE '%' || :lastName || '%')";
 






$query = oci_parse($conn, $sql);
oci_bind_by_name($query, ":outgoing_id", $outgoing_id);
oci_bind_by_name($query, ":firstName", $firstName);
oci_bind_by_name($query, ":lastName", $lastName);
oci_execute($query);

$output = "";




while ($row = oci_fetch_assoc($query)) {

    $offline = $row['STATUS'] == "Offline now" ? "offline" : ""; // Check if user is offline

    
    $output .= '<a href="chat.php?user_id='. $row['UNIQUE_ID'] .'">
                    <div class="content">
                    <img src="php/images/'. $row['IMG'] .'" alt="">
                    <div class="details">
                        <span>'. $row['FNAME']. " " . $row['LNAME'] .'</span>
                        <p> Click here to message </p>
                    </div>
                    </div>
                    <div class="status-dot '. $offline .'"><i class="fas fa-circle"></i></div>
                </a>';
}

if (empty($output)) {
    $output = 'No user found related to your search term';
}

echo $output;





	/* MYSQL CODE
    session_start();
    include_once "config.php";

    $outgoing_id = $_SESSION['unique_id'];
    $searchTerm = mysqli_real_escape_string($conn, $_POST['searchTerm']);

    $sql = "SELECT * FROM users WHERE NOT unique_id = {$outgoing_id} AND (fname LIKE '%{$searchTerm}%' OR lname LIKE '%{$searchTerm}%') ";
    $output = "";
    $query = mysqli_query($conn, $sql);
    if(mysqli_num_rows($query) > 0){
        include_once "data.php";
    }else{
        $output .= 'No user found related to your search term';
    }
    echo $output;*/
?>