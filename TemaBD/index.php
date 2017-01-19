<?php 
session_start();
   require_once("utils/db_connection.php");
   require_once("utils/functions.php");

   //daca se apasa butonul de login si indeplineste conditiile de login, se retin campuri despre faptul ca e logat, username, id, si daca este admin
   if(isset($_POST["login"])) {
        $username = mysqli_real_escape_string($connection, $_POST["username"]);
        $password = mysqli_real_escape_string($connection, $_POST["password"]);
        $query = "SELECT * FROM users where ";
        $query .= "username='{$username}' and password='{$password}';";

        $result = mysqli_query($connection, $query);
        if(!$result) {
          die("Database error: " . mysqli_error($connection));
        }
        else {
          $_SESSION["logged_in"] = true;
          $_SESSION["username"] = $username;
          $usr = mysqli_fetch_assoc($result);
          $_SESSION["user_id"] = $usr["id_user"];
          $_SESSION["special"] = $usr["admin"] == 1 ? true : false;
          
        }
   }

   //daca se apasa butonul de logout se sterg variabilele care retineau informatii despre cel logat
   if(isset($_POST["logout"])) {
       unset($_SESSION["logged_in"]);
       unset($_SESSION["username"]);
       unset($_SESSION["user_id"]);
       unset($_SESSION["special"]);
   }

   //daca se apasa butonul de checkout se introduce in baza de date comanda, se ia id-ul si apoi se introduc produsele asociate comenzii
   if(isset($_POST["checkout"])) {
    if(isset($_SESSION["logged_in"])) {
       $price = round($_POST["price"],3);
       $date = date('Y/m/d H:i:s', time());
       $query = "INSERT INTO comenzi (id_user, pret, time) VALUES ({$_SESSION["user_id"]}, {$price}, '{$date}');";
       $result = get_query_assoc($connection, $query);

       $query = "SELECT * FROM comenzi WHERE id_user={$_SESSION["user_id"]} and time='{$date}';";
       $result = get_query_assoc($connection, $query);
       $row = mysqli_fetch_assoc($result);
       $id_comanda = $row["id_comanda"];

       foreach($_POST as $key => $value) {
           if(substr($key, 0, 4) === "item") {
               $query = "INSERT INTO produse_vandute (id_comanda, id_produs) VALUES ({$id_comanda}, {$value});";
               $res = get_query_assoc($connection, $query);
           }
       }
       $message = "Comanda a fost plasata!";
     } 
     else {
            $message = "Nu se poate plasa comanda! Logati-va!";
       }
   }


?>
<!DOCTYPE html>
<html>
<head>
	<title>Drug Store</title>
	<link rel="stylesheet" type="text/css" href="stylesheets/style.css">
	<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
  <script src="bootstrap/jquery.min.js"></script>
  <script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="scripts/script.js"></script>
</head>
<body>

<div class="container-fluid wrapper">
	<div class="row header">
        <div class="title">
            <span class="title">Farmacy</span>
        </div>
		<!--For the login and cart-->
        <div class="login-cart-menu">
            <!--Cart button-->
            <div class="dropdown cart-dropdown">
              <button class="btn btn-default dropdown-toggle cart" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-shopping-cart"></span>
              <span class="caret"></span></button>
              <ul class="dropdown-menu dropdown-menu-right">
                <li role="separator" class="divider"></li>
                <li class="total"><a>Total: 0 produse</a></li>
                <li class="checkout"><a><button class="btn btn-danger btn-xs checkout">Checkout</button></a></li>

                <form action="index.php" method="POST" class="hidden-form">
                  <input type="submit" name="checkout" value="checkout">
                </form>

              </ul>
            </div>
            
            
            <!--Login button-->
            <?php 
                if(!isset($_SESSION["logged_in"])) {
                  require("widgets/login_drop.php");
                }
                else {
                  require("widgets/show_username.php");
                }
            ?>
            
            
        </div>
        
	</div>
	
	<div class="row body">
	    
        
      <div class="col-xs-2 menu">
            <div class="searchbar">
              <input type="text" class="form-control search_text" placeholder="Product Search">
              <form action="index.php" method="POST">
                <select class="form-control" name="id_categorie">
                <option value="-1">All</option>
                <?php
                    $string = "";
                    $query = "SELECT * FROM categorii";
                        $result = get_query_assoc($connection, $query);
                        while($category = mysqli_fetch_assoc($result)) {
                          $category_name = $category["name"];
                          $string .= "<option value=\"{$category["id_categorie"]}\">{$category_name}</option>";
                        }
                        echo $string;
                ?>
                </select>
                <select class="form-control" name="id_afectiune">
                <option value="-1">All</option>
                <?php
                    $string = "";
                    $query = "SELECT * FROM afectiuni";
                        $result = get_query_assoc($connection, $query);
                        while($af = mysqli_fetch_assoc($result)) {
                          $af_name = $af["name"];
                          $string .= "<option value=\"{$af["id_afectiune"]}\">{$af_name}</option>";
                        }
                        echo $string;
                ?>
                </select>
                <button type="submit" class="btn btn-default search-button" name="search_button" aria-label="Left Align">
                  <span class="glyphicon glyphicon-search" aria-hidden="true"></span> Search
                </button>
              </form>
            </div>
            

            <?php

            if (isset($_SESSION["special"]) && $_SESSION["special"] == 1) {
              $string = "<div class=\"admin_actions text-center\">";
              $string .= "<a href=\"produse.php\">Administrare Produse</a><br>";
              $string .= "<a href=\"categorii.php\">Administrare Categorii</a><br>";
              $string .= "<a href=\"afectiuni.php\">Administrare Afectiuni</a><br>";
              $string .= "<a href=\"users.php\">Administrare Utilizatori</a><br>";
              $string .= "<h4>Statistics:</h4>";
              $string .= "<a href=\"query.php?id=1\">Comenzi date de admini</a><br>";
              $string .= "<a href=\"query.php?id=2\">Comenzi cu pretul peste medie</a><br>";
              $string .= "<a href=\"query.php?id=3\">Numarul maxim de produse dintr-o comanda</a><br>";
              $string .= "<a href=\"query.php?id=4\">Media pretului produselor pe comanda</a><br>";
              $string .= "</div>";
              echo $string;
            }
            
              if (isset($message)) {
                echo "<p class=\"bg-danger\">{$message}</p>";
              }


            ?>

            

	    </div>
        
        
        
        
	    <div class="col-xs-10 content">
	        <?php 
                if (isset($_POST["search_button"])) {
                  if ($_POST["id_categorie"] != "-1" && $_POST["id_afectiune"] != "-1") {
                      $query = "SELECT id_categorie,id_produs, name, price FROM produse  WHERE id_categorie={$_POST["id_categorie"]} and id_afectiune={$_POST["id_afectiune"]};";  
                  }
                  else {
                    if ($_POST["id_categorie"] != "-1") {
                      $query = "SELECT id_categorie,id_produs, name, price FROM produse  WHERE id_categorie={$_POST["id_categorie"]};"; 
                    }
                    else {
                      if ($_POST["id_afectiune"] != "-1") {
                          $query = "SELECT id_categorie,id_produs, name, price FROM produse  WHERE id_afectiune={$_POST["id_afectiune"]};"; 
                      }
                      else {
                        $query = "SELECT id_categorie,id_produs, name, price FROM produse;";
                      }
                    }
                  }
                  
                }
                else {
                  $query = "SELECT id_categorie,id_produs, name, price FROM produse;";
                }
                $string = "";
                $result = get_query_assoc($connection, $query);
                while($produs = mysqli_fetch_assoc($result)) {
                	$name = $produs["name"];
                	$price = $produs["price"];
                	$string .= "<div class=\"produs\" prodid=\"{$produs["id_produs"]}\">";
                  $string .= "<div class=\"image\"><img src=\"images/unknown.jpg\"></div>";
                  $string .= "<div class=\"information\">";
                	$string .= "<span class=\"name\">{$name}</span><br>";
                  $string .= "<span class=\"categorie\" style=\"display: none;\">{$produs["id_categorie"]}</span><br>";
                	$string .= "<span class=\"price\">{$price}</span><br>";
                  $string .= "<button class=\"btn btn-success btn-xs to-cart\">Add to cart</button>";
                  $string .= "</div>";
                  $string .= "</div>";
                }
                echo $string;
	        ?>
	    </div>
	</div>
	
	<div class="row footer">
		<span class="copyright">© Copyright - Doru Blanzeanu 2016</span>
	</div>
</div>

</body>
</html>

