
<?php
session_start();
include_once "php/config.php";
if (!isset($_SESSION['unique_id'])) {
    header("location: login.php");
    //exit; // Terminate script execution after redirection
}

// Report any errors
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<?php include_once "header.php"; ?>

<body>
    <div class="wrapper">
        <section class="chat-area">
            <header>
                <?php
                $user_id = $_GET['user_id'];
                $sql = oci_parse($conn, "SELECT * FROM users WHERE unique_id = :user_id");
                oci_bind_by_name($sql, ":user_id", $user_id);
                oci_execute($sql);
                $row = oci_fetch_assoc($sql);

                if (!$row) {
                    header("location: users.php");
                    //exit; // Terminate script execution after redirection
                }
                ?>

                <a href="users.php" class="back-icon"><i class="fas fa-arrow-left"></i></a>
                <img src="php/images/<?php echo $row['IMG'] ?>" alt="">

                <div class="details">
                    <span><?php echo $row['FNAME'] . " " . $row['LNAME'] ?></span>
                    <p><?php echo $row['STATUS'] ? $row['STATUS'] : ''; ?></p>
                </div>
            </header>
            <div class="chat-box"></div>
            <form action="#" class="typing-area" accept-charset="UTF-8">
                <input type="hidden" class="incoming_id" name="incoming_id" value="<?php echo isset($user_id) ? $user_id : ''; ?>">
                <input type="text" name="message" class="input-field" placeholder="Type a message here..." autocomplete="off">
                <button><i class="fab fa-telegram-plane"></i></button>
            </form>
        </section>
    </div>

    <script src="javascript/chat.js"></script>
</body>

</html>
