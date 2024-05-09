<?php

	$conn = oci_connect('dbuser211694', 'ce1234', 'azza.gwangju.ac.kr/orcl');
	$stid = oci_parse($conn, 'SELECT e.name, e.ssn from EMPLOYEE e
								join DEPARTMENT d  ON e.ssn = d.mgrssn
	
	');
	oci_execute($stid);


	
	
	
	echo "<table>\n";
	while(($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) != false){
		echo "<tr>\n";
		foreach($row as $item){
			echo " <td>".($item !== null ? htmlentities($item, ENT_QUOTES): "&nbsp;")."</td>\n";
		}
		echo "</tr>\n";

	}
	
	
	
	echo "</table>\n";
	oci_close($conn);	

?>