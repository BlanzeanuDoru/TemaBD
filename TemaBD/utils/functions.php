<?php

function get_query_assoc($connection, $query) {

	$result = mysqli_query($connection,$query);
    if(!$result) {
    	//die("Database error: " . mysqli_error($connection));
    }
    return $result;
}

function redirect_to($path) {
     header("Location: " . $path);
	 exit;
}

?>