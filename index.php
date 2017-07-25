<?php
	
	require("./lib/get_regions.php");

?>

<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>GEO IP Block List</title>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<script
  src="https://code.jquery.com/jquery-2.2.4.js"
  integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI="
  crossorigin="anonymous"></script>

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

<link rel="stylesheet" href="css/bootstrap.min.css"/>
<link rel="stylesheet" href="css/bootstrap-theme.min.css"/>
<link rel="stylesheet" href="css/style.css"/>

</head>

<body class="blocker">

	<nav id="navbar" style="height: 80px;" class="navbar navbar-default">

	    <div class="navbar-header">
	      <a class="navbar-brand" href="#">
	        <img src="./img/logo.svg">
	        <span>GEO IP BLOCK TOOL</span>
	      </a>
	    </div>

	</nav>
	
	<div class="container">
		
		<div class="panel panel-default">
			<div class="panel-heading">
				Region Block Manager
			</div>
			
			<div class="panel-body">
				<div class="container-fluid">
					<form id="ipblocklist_form">
						<div class="row">
							<div class="col-md-4">
							
								<?php foreach ($locales as $locale): ?>
									<?php $l = explode(":", $locale) ?>
									<input type="checkbox"<?php echo in_locales($locale, $chosen)?> class="rg" id="rg_<?php echo $l[1] ?>" name="rg[]" value="<?php echo $locale ?>"/><label for="rg_<?php echo $l[1] ?>">&nbsp; <?php echo $l[0] ?></label> <br>

								<?php endforeach; ?>

								<hr>
								<span class="muted">Our servers get nailed by EC2 services as well. Please consider also blocking these.</span><br>
								<input type="checkbox" checked="checked" class="svc" disabled="disabled" id="svc_aws ?>" name="svc[]" value="aws"/><label for="aws">&nbsp; Amazon EC2 </label> 

								
							</div>
							<div class="col-md-8 relative">
							  <div class="ipfloater">
								<h2>Raw list of IP addresses <span class="ipcount">(<?php echo count($ips_array)  ?>)</span></h2>
								<input type="submit" class="btn btn-lg btn-block btn-primary" value="Re-Generate IP List">
								<div class="well" style="max-height: 800px; overflow-y: scroll;">

									<pre id="ip_raw">
<?php foreach($ips_array as $ip) {echo $ip."\n";} ?>
									</pre>

								</div>
							   </div>
							</div>

						</div>
					</form>
				</div>
			</div>
			<div class="panel-footer">
				<ul class="nav nav-pills">

					<li class="active"><a target="_blank" href="./templates/iptables.sh.php">BASH Script</a></li>
					<li class="active"><a target="_blank" href="./templates/blocklist.ps.php">Powershell Script</a></li>
				</ul>
			</div>
		</div>
		
	</div>
	
	<script>
	$(document).ready(function() {

		//Update Chosen Region Database on checkbox change
		$(".rg").change(function() {
		
			var checked_regions = []

			$('.rg:checkbox:checked').each(function () {
			    	checked_regions.push($(this).val());
			});

			data = {"rgs":checked_regions, "csrf":<?php echo $_SESSION["csrf"]?>};

			//console.log(data);
			$.ajax({
				type:"POST",
				url: "./lib/update_regions.php",
				data: { "data" : data },
			})
			.done(function(success){
				console.log(success)
			})
			.fail(function(error){ 
				console.log(error)
			});

		})

		// Generate the aggragated ip list
		$( "#ipblocklist_form" ).submit(function( event ) {


		  // Ignoring form array of rg[] on PHP side. Simply regens list of IP's and displays the result in the box.
		  event.preventDefault();
		  
		  $.ajax({
				type:"GET",
				data: {'csrf':<?php echo $_SESSION["csrf"] ?>},
				url: "./lib/update_ips.php"
			})
			.done(function(iplist){
				console.log(iplist)
				$("#ip_raw").text(iplist);

				$(".ipcount").text(iplist.split("\n").length);
			})
			.fail(function(error){ 
				console.log(error)
			});

		  
		  
		});
	})
		
	</script>
	
</body>
</html>

		