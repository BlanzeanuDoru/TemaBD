<?php 
session_start();
   require_once("utils/db_connection.php");
   require_once("utils/functions.php");
   //verifica daca userul ajuns aici este logat si este admin
   if(!isset($_SESSION["logged_in"]) || !$_SESSION["special"]) {
   	    redirect_to("index.php");
   }

  //trimite interogarea la baza de date
  $query = "SELECT t.count_nr, A.name, A.id_afectiune FROM afectiuni A LEFT OUTER JOIN ( SELECT count(*) count_nr, id_afectiune FROM produse GROUP BY id_afectiune ) as t ON A.id_afectiune=t.id_afectiune ORDER BY A.name;";
  $result = get_query_assoc($connection, $query);

?>

<!DOCTYPE html>
<html>
<head>
	<title>Afectiuni</title>
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
		    $string = "<div class=\"afectiuni\">";
		    $string .= "<table class=\"table\">";
		    $string .= "<tr><th>Nume</th><th>Numar produse</th><th>Actions</th></tr>";
            while($af=mysqli_fetch_assoc($result)) {
                $count   = $af["count_nr"] !== NULL ? $af["count_nr"] : 0;
  	            $string .= "<tr>";
  	            $string .= "<td>{$af["name"]}</td>";
                $string .= "<td>{$count}</td>";
  	            $string .= "<td><a class=\"btn btn-primary btn-xs edit_btn\" href=\"edit_af.php?id={$af["id_afectiune"]}\">Edit</a>";
                $string .= "<a class=\"btn btn-danger btn-xs delete_btn\" href=\"delete_af.php?id={$af["id_afectiune"]}\">Delete</a></td>";
                $string .= "</tr>";
            }
            $string .= "</table>";
            $string .= "</div>";
            echo $string;
		?>
       
       <a class="btn btn-primary" href="index.php">Back</a>
       <a class="btn btn-primary" href="add_af.php">Add</a><br>
       <?php
           if(isset($_SESSION["error-message"])) {
            echo "<span class=\"bg-danger\">{$_SESSION["error-message"]}</span>";
            unset($_SESSION["error-message"]);
           }
       ?>

</body>
</html>