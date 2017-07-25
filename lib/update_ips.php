<?php 

require('options.php');
require('csrf.php');

// Check session token to see if it matches - Defend against XSS attacks

if ($_SESSION["csrf"] = $csrf ) {
	

 	# Get list of chosen locales (this will add checkboxes to checkboxes)
	$file = fopen("../data/banned.db.txt", "r");
	$chosen_db = fread($file, filesize("../data/banned.db.txt"));
	$chosen = explode(";", $chosen_db);
	fclose($file);


	# Also load our entire list of regions to check our chosen list's sanity
	$file = fopen("../data/regions.all.db.txt", "r");
	$regions_db = fread($file, filesize("../data/regions.all.db.txt"));
	$regions = explode(";", $regions_db);
	fclose($file);

	# if something exists in banned.db.txt, it better exist in our locales lib
	function sanity_check($r, $c) {

		// $diff = array_diff($c, $r);
		// if(count($diff) == 0) {
		// 	return true;
		// } else {
		// 	return false;
		// }
		return true;	
	}

	// Check it now...
	if (sanity_check($regions, $chosen)) {

		// Looks like our chosen regions are good -- Start fetching IP's

        # Setup Array
        $iplist = [];

        $ipfile = fopen("../data/ips.txt", "w");

        # Amazon EC2 Server List @ https://ip-ranges.amazonaws.com/ip-ranges.json
        $json = file_get_contents("https://ip-ranges.amazonaws.com/ip-ranges.json");

        # Parse Servers List
        $ec2 = json_decode($json, true);
        $ec2_prefixes = $ec2["prefixes"];

        # Add results to IP Array
        foreach ($ec2_prefixes as $svr) {
            array_push($iplist, $svr["ip_prefix"]);
        }

        # Get countries params from request

        $codes = [];
        foreach ($chosen as $country) {
        	
			if(strlen($country) > 0) {
				$c = explode(":",$country);
        		array_push($codes, $c[1]);
			}

        }

        foreach ($codes as $country_code) {

                # TODO: Error check this list -> Not 100% necessary if we know what we're looking for.
                $ips = file_get_contents("http://www.ipdeny.com/ipblocks/data/countries/".strtolower($country_code).".zone");

                foreach (explode("\n", $ips) as $ip) {
                        array_push($iplist, $ip);
                }
        }
    
        #####################################################################
        # Output
        #####################################################################
        $out_list =  implode("\n", $iplist);
        fwrite($ipfile, $out_list);
        fclose($ipfile);

        // Don't echo out whole list - it will get cut off at buffer limit
        foreach ($iplist as $ip) {
        	echo $ip."\n";
        }

        // require('update_templates.php');

	} else {
		echo "Sanity check fail";
	}

} else {
	echo "CSRF validation failed";
}


?>