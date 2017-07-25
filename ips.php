
<?php
# Download list of IP addresses

# This may or may not display newlines correctly

	$file = fopen("./data/ips.txt", "r");
	$ips_list = fread($file, filesize("./data/ips.txt"));
	$ips_array = explode("\n",$ips_list);

	foreach ($ips_array as $ip){
		echo $ip."\n";
	}

?>
