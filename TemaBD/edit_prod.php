<?php 
session_start();
   require_once("utils/db_connection.php");
   require_once("utils/functions.php");
   //verifica daca userul ajuns aici este logat si este admin
   if(!isset($_SESSION["logged_in"]) || !$_SESSION["special"]) {
   	    redirect_to("index.php");
   }
   //se face update la produsul cu id-ul trimis
   if (isset($_POST["submit"]) && $_POST["submit"]=="Edit") {
      $id_prod = $_POST["id_produs"];
      $name = $_POST["nume"];
      $id_categorie = $_POST["id_categorie"];
      $price = floatval($_POST["price"]);
      $query = "UPDATE produse SET name='{$name}', id_categorie={$id_categorie}, price={$price} WHERE id_produs={$id_prod};";
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
        //se scot informatii despre produsul pe care dorim sa il editam pentru a afisa la utilizator campurile deja completate
        $query = "SELECT * FROM produse WHERE id_produs={$_GET["id"]}";
        $result = get_query_assoc($connection, $query);
        $row = mysqli_fetch_assoc($result);

        $query = "SELECT * FROM categorii";
        $result_cat = get_query_assoc($connection, $query);

        $query = "SELECT * FROM afectiuni";
        $result_af = get_query_assoc($connection, $query);

    ?>

	<form action="edit_prod.php" method="POST">
		<div class="form-group text-center">
			<label>Id_produs <input type="text" class="form-control" name="id_produs" value="<?php echo $row["id_produs"]; ?>" readonly></label>
		</div>
		<div class="form-group text-center">
			<label>Nume <input type="text" class="form-control" name="nume" value="<?php echo $row["name"]; ?>"></label>
		</div>
		<div class="form-group text-center">
			<label>Categorie 

			
			<select name="id_categorie" class="form-control">
            <?php
                $string1 = "";
                while($cat=mysqli_fetch_assoc($result_cat)) {
                	$string1 .= "<option value=\"{$cat["id_categorie"]}\">{$cat["name"]}</option>";
                }
                echo $string1;
            ?>
            </select>

			</label>
		</div>
    <div class="form-group text-center">
      <label>Categorie 

      
      <select name="id_afectiune" class="form-control">
            <?php
                $string2 = "";
                while($af=mysqli_fetch_assoc($result_af)) {
                  $string2 .= "<option value=\"{$af["id_afectiune"]}\">{$af["name"]}</option>";
                }
                echo $string2;
            ?>
            </select>

      </label>
    </div>

		<div class="form-group text-center">
			<label>Price <input type="text" class="form-control" name="price" value="<?php echo $row["price"]; ?>"></label>
		</div>
		<div class="form-group text-center">
		    <a class="btn btn-primary" href="produse.php">Back</a>
        	<input class="btn btn-primary" type="submit" value="Edit" name="submit">
        </div>
	</form>
</div>
<div class="col-md-1"></div>


</body>
</html>