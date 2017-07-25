<?php 

	require('options.php');
	require('csrf.php');

	// Check session token to see if it matches - Defend against XSS attacks

	$json = $_POST["data"];	
	$csrf =  $json["csrf"];
	
	if ($_SESSION["csrf"] = $csrf ) {
		

		$file = fopen("../data/banned.db.txt", "w");

	 	foreach($json["rgs"] as $rg){
		    
 	    	fwrite($file, $rg.";");

	 	}
	 	fclose($file);

	}
	
?>