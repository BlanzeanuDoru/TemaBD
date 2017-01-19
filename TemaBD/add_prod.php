<?php 
session_start();
   require_once("utils/db_connection.php");
   require_once("utils/functions.php");

   if(!isset($_SESSION["logged_in"]) || !$_SESSION["special"]) {
   	    redirect_to("index.php");
   }

   if (isset($_POST["submit"]) && $_POST["submit"]=="Add") {
      $name = $_POST["nume"];
      $id_categorie = $_POST["id_categorie"];
      $id_afectiune = $_POST["id_afectiune"];
      $price = floatval($_POST["price"]);
      $query = "INSERT INTO produse (name, id_categorie, id_afectiune, price) VALUES ('{$name}', {$id_categorie}, {$id_afectiune}, {$price});";
      $res = get_query_assoc($connection, $query);
      redirect_to("produse.php");
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
        //scot categoriile si afectiunile pentru a le afisa la utilizator sa aleaga
        $query = "SELECT * FROM categorii ORDER BY name ASC;";
        $result_cat = get_query_assoc($connection, $query);
        
        $query = "SELECT * FROM afectiuni ORDER BY name ASC;";
        $result_af = get_query_assoc($connection, $query);
    ?>

	<form action="add_prod.php" method="POST">
		<div class="form-group text-center">
			<label>Nume <input type="text" class="form-control" name="nume" value=""></label>
		</div>
		<div class="form-group text-center">
			<label>Categorie 

            <?php
                //alcatuiesc selecturile pentru a lasa userul sa selecteze carei categorie si afectiune apartine produsul
                $string1 = "<select name=\"id_categorie\" class=\"form-control\">";
                $string2 = "<select name=\"id_afectiune\" class=\"form-control\">";
                while($row=mysqli_fetch_assoc($result_cat)) {
                	$string1 .= "<option value=\"{$row["id_categorie"]}\">{$row["name"]}</option>";
                }
                while($row=mysqli_fetch_assoc($result_af)) {
                  $string2 .= "<option value=\"{$row["id_afectiune"]}\">{$row["name"]}</option>";
                }
                $string1 .= "</select>";
                $string2 .= "</select>";
                echo $string1;
            ?>
			</label>
		</div>
    <div class="form-group text-center">
        <label>Afectiune<?php echo $string2; ?></label>
    </div>

		<div class="form-group text-center">
			<label>Price <input type="text" class="form-control" name="price" value=""></label>
		</div>
		<div class="form-group text-center">
          <a class="btn btn-primary" href="produse.php">Back</a>
        	<input class="btn btn-primary" type="submit" value="Add" name="submit">
        </div>
	</form>
</div>
<div class="col-md-1"></div>


</body>
</html>