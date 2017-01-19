<?php 
session_start();
   require_once("utils/db_connection.php");
   require_once("utils/functions.php");

   if(!isset($_SESSION["logged_in"])) {
        redirect_to("index.php");
   }
   //alegere interogare in functie de butonul ales
   if (isset($_GET["id"])) {
    if ($_GET["id"] === "1") {
        $query = "SELECT * FROM comenzi C JOIN users U ON C.id_user=U.id_user WHERE C.id_user= ANY";
        $query .= "(SELECT id_user FROM users WHERE admin=1);";
    }
    if ($_GET["id"] === "2") {
        $query = "SELECT * FROM comenzi C JOIN users U ON C.id_user=U.id_user WHERE C.pret>=";
        $query .= "(SELECT AVG(pret) FROM comenzi);";
    }
    if ($_GET["id"] === "3") {
        $query = "SELECT MAX(count_column) max, U.username FROM ( SELECT count(id_produs) as count_column, id_comanda as id_cmd FROM produse_vandute Group by id_comanda ) AS t JOIN comenzi C ON C.id_comanda=t.id_cmd JOIN users U ON C.id_user=U.id_user;";
    }
    if ($_GET["id"] === "4") {
        $query = "SELECT C.pret 'total', t.avg 'media', U.username FROM ( SELECT avg(price) 'avg', PV.id_comanda FROM produse P JOIN produse_vandute PV ON PV.id_produs=P.id_produs group by id_comanda ) as t JOIN comenzi C ON C.id_comanda=t.id_comanda JOIN users U ON U.id_user=C.id_user ORDER BY total DESC;";
    }

   }
   
   //executa interogarea
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
      //afisare intr-un tabel a rezultatelor
       if (isset($_GET["id"]) && ($_GET["id"] === "1" || $_GET["id"] ==="2")) {

        $string = "<table class=\"table table-hover\">";
        $string .= "<tr><th>Time</th><th>Username</th><th>Pret</th></tr>";
        while($comanda = mysqli_fetch_assoc($result)) {
        	$string .= "<tr>";
   	        $string .= "<td>{$comanda["time"]}</td>";
            $string .= "<td>{$comanda["username"]}</td>";
   	        $string .= "<td>{$comanda["pret"]} lei</td>";
   	        $string .= "<div class=\"details\" time=\"{$comanda["time"]}\"> ";


            $query = "SELECT * FROM produse_vandute as PV JOIN produse as P ON PV.id_produs=P.id_produs JOIN comenzi as C ON C.id_comanda=PV.id_comanda WHERE C.id_user={$comanda["id_user"]} and C.TIME='{$comanda["time"]}';";
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
        }
        else {
          if (isset($_GET["id"]) && $_GET["id"] ==="3") {
            $row = mysqli_fetch_assoc($result);
            $string = "<b>Numarul maxim de produse dintr-o comanda:</b> {$row["max"]}<br>";
            $string .= "<b>Comanda a fost facuta de:</b> {$row["username"]}<br>";
            echo $string;
          }
          if (isset($_GET["id"]) && $_GET["id"] ==="4") {
            $string = "<table class=\"table table-hover\">";
            $string .= "<tr><th>Total</th><th>Media</th><th>Username</th></tr>";
            while($row=mysqli_fetch_assoc($result)) {
              $string .= "<tr>";
              $string .= "<td>{$row["total"]}</td>";
              $string .= "<td>{$row["media"]}</td>";
              $string .= "<td>{$row["username"]}</td>";
              $string .= "</tr>";
            }
            $string .= "</table>";
            echo $string;
          }
        }
    	?>
    <a class="btn btn-primary" href="index.php">Back</a>
    </div>

    <div class="col-md-6">
    	<div class="cont">
        <?php
        if (isset($_GET["id"]) && ($_GET["id"] === "1" || $_GET["id"] ==="2"))
          echo "<span>Produsele comandate</span><br>";
        ?>
    	</div>
    </div>

</body>
</html>