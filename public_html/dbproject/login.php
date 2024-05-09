<?php
$cid = $_POST["id"];
$password = $_POST["password"];

$conn = oci_connect('dbuser211927', '754124', 'azza.gwangju.ac.kr/orcl', 'AL32UTF8');

if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$sql = "SELECT * FROM users WHERE id = :id";
$stmt = oci_parse($conn, $sql);
oci_bind_by_name($stmt, ":id", $cid);
oci_execute($stmt);

$match = oci_fetch_array($stmt);

if (!$match[1]) {
    echo("
        <script>
            window.alert('등록되지 않은 아이디입니다!');
            history.go(-1);
        </script>
    ");
} else {
    $db_password = $match[2];

    if ($password != $db_password) {
        echo("
            <script>
                window.alert('비밀번호가 일치하지 않습니다!');
                history.go(-1);
            </script>
        ");

        exit();
    } else {
        session_start();
        $_SESSION['userid'] = $match[1];
        $_SESSION['username'] = $match[0];
         $_SESSION['userid'] = $match['NAME'];

        if (isset($_SESSION['userid'])) {
            echo("
                <script>
                    location.href = '1. main.php';
                </script>
            ");
        } else {
            echo("
                <script>
                    window.alert('로그인 실패!');
                    history.go(-1);
                </script>
            ");
        }
    }
    oci_close($conn);
}
?>