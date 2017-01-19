<?php 
session_start();
   require_once("utils/db_connection.php");
   require_once("utils/functions.php");

   if(!isset($_SESSION["logged_in"]) || !isset($_GET["id"]) || $_SESSION["user_id"] != $_GET["id"]) {
        redirect_to("index.php");
   }
   $id = $_GET["id"];
   $query = "SELECT * FROM comenzi WHERE id_user={$id};";
   $result = get_query_assoc($connection, $query);

?>


<!DOCTYPE html>
<html>
<head>
	<title>Drug Store</title>
	<link rel="stylesheet" type="text/css" href="stylesheets/comenzi.css">
	<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
  <script src="bootstrap/jquery.min.js"></script>
  <script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="scripts/script.js"></script>
</head>
<body>

    <div class="col-md-6">
    	<?php

        $string = "<table class=\"table table-hover\">";
        $string .= "<tr><th>Time</th><th>Pret</th></tr>";
        while($comanda = mysqli_fetch_assoc($result)) {
        	$string .= "<tr>";
   	        $string .= "<td>{$comanda["time"]}</td>";
   	        $string .= "<td>{$comanda["pret"]} lei</td>";
   	        $string .= "<div class=\"details\" time=\"{$comanda["time"]}\"> ";


            $query = "SELECT * FROM produse_vandute as PV JOIN produse as P ON PV.id_produs=P.id_produs JOIN comenzi as C ON C.id_comanda=PV.id_comanda WHERE C.id_user={$id} and C.TIME='{$comanda["time"]}';";
            $res = get_query_assoc($connection, $query);
            while($prod=mysqli_fetch_assoc($res)) {
            	$string .= "<div class=\"produs\">";
            	$string .= "<span class=\"name\">{$prod["name"]}</span>";
            	$string .= "<span class=\"pret\">{$prod["price"]} lei</span>";
            	$string .= "</div>";
            }
   	        $string .= "</div>";
   	        $string .= "</tr>";
        }
          $string .= "</table>";
          echo $string;
    	?>
    </div>
    <div class="col-md-6">
    	<div class="cont">
    		<span>Produsele comandate</span><br>
    	</div>
    </div>

</body>
</html>