<?php 
session_start();
   require_once("utils/db_connection.php");
   require_once("utils/functions.php");
   //verifica daca userul ajuns aici este logat si este admin
   if(!isset($_SESSION["logged_in"]) || !$_SESSION["special"]) {
   	    redirect_to("index.php");
   }
   //se face delete la produse si la inregistrarile din comanda care retin acest produs
   $id = $_GET["id"];
   $query = "DELETE FROM produse_vandute WHERE id_produs={$id};";
   $res = get_query_assoc($connection ,$query);

   $query = "DELETE FROM produse WHERE id_produs={$id};";
   $res = get_query_assoc($connection, $query);
   if(!$res) {
      $_SESSION["error-message"] = "Nu se poate sterge din baza de date. Valoarea aceasta e referentiata in alte tabele.";
   }

   redirect_to("produse.php");

?>
