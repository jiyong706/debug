<?php

$conn = oci_connect('dbuser211694', 'ce1234', 'azza.gwangju.ac.kr/orcl');
$sql = "INSERT INTO employee(ssn, name, sex, dnum, superssn, BDATE)
					VALUES(1006, :ename_b, :gender_b, 123, 1004, :birthdate_b)";
					
$stid = oci_parse($conn, $sql);


$ename = "Gil-dong Hong";
$gender = "M";
$birthdate = "00/01/01";
oci_bind_by_name($stid, ":ename_b", $ename );
oci_bind_by_name($stid, ":gender_b", $gender );
oci_bind_by_name($stid, ":birthdate_b", $birthdate);


$result = (oci_execute($stid) ) ? 'Success' : 'Fail';
echo $result;

oci_free_statement($stid);
oci_close($conn);

?>