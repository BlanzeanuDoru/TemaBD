<?php 
session_start();
   require_once("utils/db_connection.php");
   require_once("utils/functions.php");

   if(!isset($_SESSION["logged_in"]) || !$_SESSION["special"]) {
   	    redirect_to("index.php");
   }

   $id = $_GET["id"];
   $query = "DELETE FROM afectiuni WHERE id_afectiune={$id};";
   $res = get_query_assoc($connection, $query);
   if(!$res) {
   	$_SESSION["error-message"] = "Nu se poate sterge din baza de date. Valoarea aceasta e referentiata in alte tabele.";
   }

   redirect_to("afectiuni.php");

?>
