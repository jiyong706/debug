<?php

$conn = oci_connect('dbuser211694', 'ce1234', 'azza.gwangju.ac.kr/orcl');
$sql = "INSERT INTO employee(ssn, name, sex, dnum, superssn )
					VALUES(1006, :ename_b, :gender_b, 123, 1004)";
					
$stid = oci_parse($conn, $sql);

$ename = "Gil-dong Hong";
$gender = "M";
oci_bind_by_name($stid, ":ename_b", $ename );
oci_bind_by_name($stid, ":gender_b", $gender );


$result = (oci_execute($stid) ) ? 'Success' : 'Fail';
echo $result;

oci_free_statement($stid);
oci_close($conn);

?>