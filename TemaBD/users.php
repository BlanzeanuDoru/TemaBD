<?php 
session_start();
   require_once("utils/db_connection.php");
   require_once("utils/functions.php");
   //verifica daca userul ajuns aici este logat si este admin
   if(!isset($_SESSION["logged_in"]) || !$_SESSION["special"]) {
   	    redirect_to("index.php");
   }
  //trimite interogarea la baza de date
  $query = "SELECT t.count_nr, U.username, U.email, U.admin, U.id_user FROM users U LEFT OUTER JOIN ( SELECT count(*) count_nr, id_user FROM comenzi GROUP BY id_user ) as t ON U.id_user=t.id_user ORDER BY U.username;";
  $result = get_query_assoc($connection, $query);

?>

<!DOCTYPE html>
<html>
<head>
	<title>Users</title>
	<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
    <script
  src="bootstrap/jquery.min.js"
  integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
  crossorigin="anonymous"></script>
  <script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="scripts/script.js"></script>
</head>
<body>


		<?php
        //se dispun informatiile despre useri intr-un tabel
		    $string = "<div class=\"users\">";
		    $string .= "<table class=\"table\">";
		    $string .= "<tr><th>Username</th><th>email</th><th>Numar comenzi</th><th>admin</th><th>Actions</th></tr>";
            while($usr=mysqli_fetch_assoc($result)) {
                $count   = $usr["count_nr"] !== NULL ? $usr["count_nr"] : 0;
  	            $string .= "<tr>";
  	            $string .= "<td>{$usr["username"]}</td>";
  	            $string .= "<td>{$usr["email"]}</td>";
                $string .= "<td>{$count}</td>";
                if ($usr["admin"]) {
                  $string .= "<td><span class=\"glyphicon glyphicon-ok\"></span></td>";
                }
                else {
                  $string .= "<td><span class=\"glyphicon glyphicon-remove\"></span></td>";
                }
  	            $string .= "<td><a class=\"btn btn-primary btn-xs edit_btn\" href=\"edit_user.php?id={$usr["id_user"]}\">Edit</a>";
                $string .= "<a class=\"btn btn-danger btn-xs delete_btn\" href=\"delete_user.php?id={$usr["id_user"]}\">Delete</a></td>";
                $string .= "</tr>";
            }
            $string .= "</table>";
            $string .= "</div>";
            echo $string;
		?>

       <a class="btn btn-primary" href="index.php">Back</a>
       <a class="btn btn-primary" href="add_user.php">Add</a><br>
       <?php
           if(isset($_SESSION["error-message"])) {
            echo "<span class=\"bg-danger\">{$_SESSION["error-message"]}</span>";
            unset($_SESSION["error-message"]);
           }
       ?>

</body>
</html>