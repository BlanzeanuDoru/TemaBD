<?php 
session_start();
   require_once("utils/db_connection.php");
   require_once("utils/functions.php");
   //verifica daca userul ajuns aici este logat si este admin
   if(!isset($_SESSION["logged_in"]) || !$_SESSION["special"]) {
   	    redirect_to("index.php");
   }
  //trimite interogarea la baza de date
  $query = "SELECT t.count_nr, C.name,C.id_categorie FROM categorii C LEFT OUTER JOIN ( SELECT count(*) count_nr, id_categorie FROM produse GROUP BY id_categorie ) as t ON C.id_categorie=t.id_categorie ORDER BY C.name;";
  $result = get_query_assoc($connection, $query);

?>

<!DOCTYPE html>
<html>
<head>
	<title>Categorii</title>
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
        //scrie rezultatele din baza de date intr-un tabel
		    $string = "<div class=\"categorii\">";
		    $string .= "<table class=\"table\">";
		    $string .= "<tr><th>Nume</th><th>Numar produse</th><th>Actions</th></tr>";
            while($cat=mysqli_fetch_assoc($result)) {
                $count   = $cat["count_nr"] !== NULL ? $cat["count_nr"] : 0;
  	            $string .= "<tr>";
  	            $string .= "<td>{$cat["name"]}</td>";
                $string .= "<td>{$count}</td>";
  	            $string .= "<td><a class=\"btn btn-primary btn-xs edit_btn\" href=\"edit_cat.php?id={$cat["id_categorie"]}\">Edit</a>";
                $string .= "<a class=\"btn btn-danger btn-xs delete_btn\" href=\"delete_cat.php?id={$cat["id_categorie"]}\">Delete</a></td>";
                $string .= "</tr>";
            }
            $string .= "</table>";
            $string .= "</div>";
            echo $string;
		?>
       
       <a class="btn btn-primary" href="index.php">Back</a>
       <a class="btn btn-primary" href="add_cat.php">Add</a><br>
       <?php
           if(isset($_SESSION["error-message"])) {
            echo "<span class=\"bg-danger\">{$_SESSION["error-message"]}</span>";
            unset($_SESSION["error-message"]);
           }
       ?>

</body>
</html>