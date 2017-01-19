<?php 
session_start();
   require_once("utils/db_connection.php");
   require_once("utils/functions.php");
   //verifica daca userul ajuns aici este logat si e admin
   if(!isset($_SESSION["logged_in"]) || !$_SESSION["special"]) {
   	    redirect_to("index.php");
   }
   //proceseaza cererea cand se apasa butonul submit - face insert
   if (isset($_POST["submit"]) && $_POST["submit"]=="Add") {
      $username = $_POST["username"];
      $pass = $_POST["password"];
      $email = $_POST["email"];
      $admin = $_POST["admin"] === "on" ? 1 : 0;

      $query = "INSERT INTO users (username, password, email, admin) VALUES ('{$username}', '{$pass}', '{$email}', {$admin});";
      echo $query;
      $res = get_query_assoc($connection, $query);
      if(!$res) {
        $_SESSION["error-message"] = "Cannot add user.";
      }
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

	<form action="add_user.php" method="POST">
		<div class="form-group text-center">
			<label>Username <input type="text" class="form-control" name="username" placeholder="username"></label>
		</div>
		<div class="form-group text-center">
			<label>Password <input type="password" class="form-control" name="password" placeholder="password"></label>
		</div>
    <div class="form-group text-center">
        <label>Email<input type="email" class="form-control" name="email" placeholder="example@domain.com"></label>
    </div>
		<div class="form-group text-center">
			<label>Admin <input type="checkbox" class="form-control" name="admin" unchecked></label>
		</div>
		<div class="form-group text-center">
          <a class="btn btn-primary" href="users.php">Back</a>
        	<input class="btn btn-primary" type="submit" value="Add" name="submit">
        </div>
	</form>
</div>
<div class="col-md-1"></div>


</body>
</html>