<?php 
  session_start();
  include_once "php/config.php";
  
  if(!isset($_SESSION['unique_id'])){
    header("location: login.php");
  }
?>
<?php include_once "header.php";   ?>
<body>
  <div class="wrapper">
    <section class="users">
      <header>
        <div class="content">
          <?php 
            $sql = oci_parse($conn, "SELECT * FROM users WHERE unique_id = :unique_id");
            oci_bind_by_name($sql, ":unique_id", $_SESSION['unique_id']);
            oci_execute($sql);
            if (($row = oci_fetch_assoc($sql)) !== false) {
            // User record found
             // Access individual column values using $row['column_name']
}
            
          ?>
          <img src="php/images/<?php echo $row['IMG']; ?>" alt="">
          <div class="details">
            <span><?php echo $row['FNAME']. " " . $row['LNAME'] ?></span>
            <p><?php echo $row['STATUS'] ?></p>
          </div>
        </div>
        <a href="php/logout.php?logout_id=<?php echo $row['UNIQUE_ID']; ?>" class="logout">Logout</a>
      </header>
      <div class="search">
        <span class="text">Select a user to start a chat</span>
        <input type="text" placeholder="Enter name to search...">
        <button><i class="fas fa-search"></i></button>
      </div>
      <div class="users-list">
        <a href="#">
            <div class="content">
                <img src="php/images/<?php echo $row['IMG']; ?>" alt="">
      </div>
    </section>
  </div>

  <script src="javascript/users.js"></script>

</body>
</html>
