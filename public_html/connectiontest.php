<?php



  $hostname = "azza.gwangju.ac.kr";
  $username = "dbuser211694";
  $password = "ce1234";
  $dbname = "orcl";

  $conn = oci_connect($username, $password, $hostname.'/'.$dbname);
  if (!$conn) {
    $e = oci_error();
    echo "Database connection error: (error probably from config.php) " . $e['message'];
  }

//$conn = oci_connect('dbuser211694', 'ce1234', 'azza.gwangju.ac.kr/orcl');
$stid = oci_parse($conn, 'select table_name from user_tables');
oci_execute($stid);

echo "<table>\n";
while(($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) != false){
	echo "<tr>\n";
	foreach($row as $item){
		echo " <td>".($item !== null ? htmlentities($item, ENT_QUOTES): "&nbsp;")."</td>\n";
	echo "</tr>\n";
	}

}
echo "</table>\n";
oci_close($conn);

?>