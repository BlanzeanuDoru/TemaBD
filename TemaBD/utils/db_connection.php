<?php
  //1. Create a database connection
  define("DB_SERVER", "localhost");
  define("DB_USER", "temaUSER");
  define("DB_PASS", "secret");
  define("DB_NAME", "temabd");
  $connection = mysqli_connect(DB_SERVER,DB_USER,DB_PASS,DB_NAME);
 // test if connection occured
   if(mysqli_connect_errno()){
	   die("Database connection failed: " .
	    mysqli_connect_error() .
		" (" . mysqli_connect_errno() . ")" 
		);
   }
?>