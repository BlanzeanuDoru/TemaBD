<?php 
session_start();
   require_once("utils/db_connection.php");
   require_once("utils/functions.php");

   if(!isset($_SESSION["logged_in"]) || !$_SESSION["special"]) {
   	    redirect_to("index.php");
   }

   if (isset($_POST["submit"]) && $_POST["submit"]=="Edit") {
      $id_user = $_POST["id_user"];
      $username = $_POST["username"];
      $password = $_POST["password"];
      $email = $_POST["email"];
      $admin = $_POST["admin"] ==="on" ? 1 : 0;
      $query = "UPDATE users SET username='{$username}', email='{$email}', admin={$admin}, password='{$password}' WHERE id_user={$id_user};";

      $res = get_query_assoc($connection, $query);
      redirect_to("users.php");
   }

?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
    <script
  src="bootstrap/jquery.min.js"
  integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
  crossorigin="anonymous"></script>
  <script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="scripts/script.js"></script>
</head>
<body>


<div class="col-md-1"></div>
<div class="col-md-10">


    <?php
        $query = "SELECT * FROM users WHERE id_user={$_GET["id"]}";
        $result = get_query_assoc($connection, $query);
        $row = mysqli_fetch_assoc($result);
    ?>

	<form action="edit_user.php" method="POST">
		<div class="form-group text-center">
			<label>Id_user <input type="text" class="form-control" name="id_user" value="<?php echo $row["id_user"]; ?>" readonly></label>
		</div>
    
    <div class="form-group text-center">
      <label>Username <input type="text" class="form-control" name="username" value="<?php echo $row["username"]; ?>"></label>
    </div>
    <div class="form-group text-center">
      <label>Password <input type="password" class="form-control" name="password" value="<?php echo $row["password"]; ?>"></label>
    </div>
    <div class="form-group text-center">
      <label>Email <input type="email" class="form-control" name="email" value="<?php echo $row["email"]; ?>"></label>
    </div>
		<div class="form-group text-center">
			<label>Admin <?php
                       if($row["admin"]) {
                        echo "<input class=\"form-control\" type=\"checkbox\" name=\"admin\" checked>";
                       }
                       else {
                        echo "<input class=\"form-control\" type=\"checkbox\" name=\"admin\" unchecked>";
                       }
                   ?>
		</div>
		
		<div class="form-group text-center">
		    <a class="btn btn-primary" href="users.php">Back</a>
        	<input class="btn btn-primary" type="submit" value="Edit" name="submit">
        </div>
	</form>
</div>
<div class="col-md-1"></div>


</body>
</html>