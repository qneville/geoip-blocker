<?php 

	# Set/read the CSRF token
	require('./lib/csrf.php');
	require('./lib/options.php');

	# Open list of all regions
	$file = fopen("./data/regions.all.db.txt", "r");
	$locales_lib = fread($file, filesize("./data/regions.all.db.txt"));
	$locales = explode(";", $locales_lib);
	$locales_dict = [];

	# Loop over all these regions
	foreach($locales as $locale) {
		$tmp = explode(":", $locale);
		array_push($locales_dict, $locale);
	}

	# Get list of chosen locales (this will add checkboxes to checkboxes)
	$file = fopen("./data/banned.db.txt", "r");
	if (filesize("./data/banned.db.txt") > 0) {
		$chosen_db = fread($file, filesize("./data/banned.db.txt"));
		$chosen = explode(";", $chosen_db);
	} else {
		$chosen = [];
	}

	fclose($file);

	# if something exists in banned.db.txt, it better exist in our locales lib
	function sanity_check($locales, $chosen) {

		$diff = array_diff($chosen, $locales);
		if(count($diff) == 0) {
			return true;
		} else {
			return false;
		}
			
	}

	# TODO: Make this sanity check necessary for the page to load. We will have to rebuild or blank the ip list later. Possibly a big security hole if a hacker can change /data/banned.db.txt or /data/regions.all.db.txt
	$sc = sanity_check($locales, $chosen);

	function in_locales($locale, $chosen) {
		return(array_search($locale, $chosen) !== False ? "checked" : "");
	}

	# This is pretty optional - it would be nice to show ips in box on load. Include them here

	$file = fopen("./data/ips.txt", "r");
	$ips_list = fread($file, filesize("./data/ips.txt"));
	$ips_array = explode("\n",$ips_list);
?>