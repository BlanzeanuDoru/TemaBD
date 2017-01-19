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
      $name = $_POST["nume"];
      $query = "INSERT INTO afectiuni (name) VALUES ('{$name}');";
      $res = get_query_assoc($connection, $query);
      redirect_to("afectiuni.php");
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

	<form action="add_af.php" method="POST">
		<div class="form-group text-center">
			<label>Nume <input type="text" class="form-control" name="nume" value=""></label>
		</div>
		<div class="form-group text-center">
          <a class="btn btn-primary" href="afectiuni.php">Back</a>
        	<input class="btn btn-primary" type="submit" value="Add" name="submit">
        </div>
	</form>
</div>



<div class="col-md-1"></div>


</body>
</html>