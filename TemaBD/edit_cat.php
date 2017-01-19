<?php 
session_start();
   require_once("utils/db_connection.php");
   require_once("utils/functions.php");
   //verifica daca userul ajuns aici este logat si este admin
   if(!isset($_SESSION["logged_in"]) || !$_SESSION["special"]) {
   	    redirect_to("index.php");
   }
    //se face update la categoria cu id-ul trimis
   if (isset($_POST["submit"]) && $_POST["submit"]=="Edit") {
      $name = $_POST["nume"];
      $id_categorie = $_POST["id_categorie"];
      $query = "UPDATE categorii SET name='{$name}' WHERE id_categorie={$id_categorie};";
      $res = get_query_assoc($connection, $query);
      redirect_to("categorii.php");
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
        //se scoate categoria ceruta pentru a afisa campurile cu valorile completate cu ce e in baza de date
        $query = "SELECT * FROM categorii WHERE id_categorie={$_GET["id"]};";
        $result = get_query_assoc($connection, $query);
        $row = mysqli_fetch_assoc($result);
    ?>

	<form action="edit_cat.php" method="POST">
		<div class="form-group text-center">
			<label>Id_produs <input type="text" class="form-control" name="id_categorie" value="<?php echo $row["id_categorie"]; ?>" readonly></label>
		</div>
		<div class="form-group text-center">
			<label>Nume <input type="text" class="form-control" name="nume" value="<?php echo $row["name"]; ?>"></label>
		</div>
		<div class="form-group text-center">
          <a class="btn btn-primary" href="categorii.php">Back</a>
        	<input class="btn btn-primary" type="submit" value="Edit" name="submit">
        </div>
	</form>
</div>
<div class="col-md-1"></div>


</body>
</html>