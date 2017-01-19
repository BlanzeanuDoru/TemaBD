<?php 
session_start();
   require_once("utils/db_connection.php");
   require_once("utils/functions.php");

   if(!isset($_SESSION["logged_in"]) || !$_SESSION["special"]) {
   	    redirect_to("index.php");
   }

  $query = "SELECT P.id_produs,P.name nam,P.price,C.name categorie,A.name afectiune FROM produse P JOIN categorii C ON P.id_categorie=C.id_categorie JOIN afectiuni A ON P.id_afectiune=A.id_afectiune;";
  $result = get_query_assoc($connection, $query);

?>

<!DOCTYPE html>
<html>
<head>
	<title>Produse</title>
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
		    $string = "<div class=\"produse\">";
		    $string .= "<table class=\"table\">";
		    $string .= "<tr><th>Nume</th><th>Categorie</th><th>Afectiune</th><th>Price</th><th>Actions</th></tr>";
            while($prod=mysqli_fetch_assoc($result)) {
  	            $string .= "<tr>";
  	            $string .= "<td>{$prod["nam"]}</td>";
  	            $string .= "<td>{$prod["categorie"]}</td>";
                $string .= "<td>{$prod["afectiune"]}</td>";
  	            $string .= "<td>{$prod["price"]}</td>";
  	            $string .= "<td><a class=\"btn btn-primary btn-xs edit_btn\" href=\"edit_prod.php?id={$prod["id_produs"]}\">Edit</a>";
                $string .= "<a class=\"btn btn-danger btn-xs delete_btn\" href=\"delete_prod.php?id={$prod["id_produs"]}\">Delete</a></td>";
                $string .= "</tr>";
            }
            $string .= "</table>";
            $string .= "</div>";
            echo $string;
		?>

       <a class="btn btn-primary" href="index.php">Back</a>
       <a class="btn btn-primary" href="add_prod.php">Add</a><br>
       <?php
           if(isset($_SESSION["error-message"])) {
            echo "<span class=\"bg-danger\">{$_SESSION["error-message"]}</span>";
            unset($_SESSION["error-message"]);
           }
       ?>

</body>
</html>