<meta charset="utf-8">
<?php 

	$name = $_POST["name"];
	$id = $_POST["id"];
  $password = $_POST["password"]; 

	$conn = oci_connect("dbuser211927", "754124", "azza.gwangju.ac.kr/orcl","AL32UTF8");
	/* AL32UTF8는 글씨 깨지지 말라고 넣은 것 */
   $sql = "insert into users (name, id, password) values ('$name', '$id', '$password')";
	$stid = oci_parse($conn, $sql);

  $result = (oci_execute($stid)) ? 'Succes' : 'Fail';

	if($result == 'Succes'){
	echo("
           <script>
             window.alert('회원가입이 완료가 되었습니다.')
             location.href = '2. login.html';
           </script>
         ");
	}

	oci_free_statement($stid);
	oci_close($conn);
?>
