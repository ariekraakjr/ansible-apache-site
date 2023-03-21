<?php

	
	include('config/config.php');

	if(isset($_GET["minkmselect"])){
		$minKm = $_GET["minkmselect"];
	} else {
		$minKm = 0;
	}

	if(isset($_GET["maxkmselect"])){
		$maxKm = $_GET["maxkmselect"];
	} else {
		$maxKm = 250;
	}

	
	function distance($lat1, $lon1, $lat2, $lon2, $unit = 'K') {
	settype($lat1,double);
	settype($lon1,double);
	settype($lat2,double);
	settype($lon2,double);
	
	$theta = $lon1 - $lon2;
	$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
	$dist = acos($dist);
	$dist = rad2deg($dist);
	$miles = $dist * 60 * 1.1515;
	$unit = strtoupper($unit);
	
	if ($unit == 'K') {
		return ($miles * 1.609344);
	} else if ($unit == 'N') {
		return ($miles * 0.8684);
	} else {
		return $miles;
	}
	}
	
	
	function isMobile() {
		return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
	}

	if((isMobile() && (!isset($_GET["nomob"]))) or (isset($_GET["mob"])) ){
		$mob = true;
		$siteName .= " - (Mobiele versie)";
		$cardWidth = 100;
	} else {
		$cardWidth = 70;
	}

	

//main
	$tableGpxInfo .= "<table class=\"tg\">\n<thead>\n<tr>\n<th class=\"tg-0lax\">Route</th>\n<th class=\"tg-kfta\">Afstand</th>\n<th class=\"tg-kftd\">Download</th>\n<th class=\"tg-73oq\">Fullscreen</th>\n</tr>\n</thead>\n<tbody>";
	$fileList = glob('gpx/*.gpx');
	$total_distance_max = 0;
	foreach($fileList as $filename){
		if(is_file($filename)){
	
			$xml=simplexml_load_file($filename);
			$total_distance = 0;
			
//			print "#########################\n0 : ".$total_distance."\n";
//			print "last_lat : ".$last_lat."\n";
//			print "last_lon : ".$last_lon."\n";
			
			foreach( $xml->trk->trkseg->{'trkpt'} as $trkpt ) {
				$trkptlat = $trkpt->attributes()->lat;
				$trkptlon = $trkpt->attributes()->lon;
				if($last_lat){
					$total_distance+=distance($trkptlat, $trkptlon, $last_lat, $last_lon);
				}
				$last_lat = $trkptlat;
				$last_lon = $trkptlon;
			}	
				//settype($total_distance, "integer");
				$total_distance =  round($total_distance,1);
//				print "1 : ".$total_distance."\n";
	
			if( round($total_distance,0) > $total_distance_max ){
				$total_distance_max = (round($total_distance,0) +1);
//				print round($total_distance,0)." | ".$total_distance_max."<br>\n";
			}
	
			if($total_distance >= $minKm && $total_distance <= $maxKm) {
				$track = preg_replace('/gpx\/|.gpx/i','',$filename);
//				print "2 : ".$total_distance."\n".$track."\n<br>\n";
				$tableGpxInfo .= "<tr>\n<td class=\"tg-0lax\"><a href=\"".$baseUrl.$_SERVER['PHP_SELF']."?track=".$track."&minkmselect=".$minKm."&maxkmselect=".$maxKm;
				if(isset($_GET["nomob"])) { $tableGpxInfo .=  "&nomob"; }
				$tableGpxInfo .= "\">".$track."</a></td><td class=\"tg-kfta\">".$total_distance."km</td><td class=\"tg-kftd\"><a href=\"".$filename."\">gpx</a></td><td class=\"tg-0lax\"><a href=\"https://home.ariekraakjr.nl/gps/map.php?track=".$track."\">".show."</a></td>\n</tr>\n";
				if(isset($activeTracks)){
					$activeTracks .= ",";
				}
				$activeTracks .= $track;
//				unset($last_lat);
//				unset($last_lon);
			}
		}   
		unset($last_lat);
		unset($last_lon);
	}
	$tableGpxInfo .= "</tbody>\n</table>\n";

	if(isset($_GET["track"])){
		$activeTracks = $_GET["track"];
	}

//echo $activeTracks;
?>


<html>
	<head>
		<title><?php echo $siteName; ?></title>
		<base target="_top"></base>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="viewport" content="initial-scale=1.0, user-scalable=no">
<!--		<link rel="stylesheet" href="css/multithumb-slider.css">
		<script src="js/multithumb-slider.js" type="text/javascript"></script> -->

<style type="text/css">
body {font-family:Arial, sans-serif;font-size:14px;}
.tg  {border-collapse:collapse;border-spacing:0;}
.tg td{border-color:black;border-style:solid;border-width:1px;font-family:Arial, sans-serif;font-size:14px;
  overflow:hidden;padding:10px 5px;word-break:normal;}
.tg th{border-color:black;border-style:solid;border-width:1px;font-family:Arial, sans-serif;font-size:14px;
  font-weight:normal;overflow:hidden;padding:10px 5px;word-break:normal;}
.tg .tg-kftd{background-color:#efefef;text-align:right;vertical-align:top}
.tg .tg-kfta{background-color:#efefef;text-align:right;vertical-align:top}
.tg .tg-0lax{text-align:left;vertical-align:top}
.tg .tg-73oq{border-color:#000000;text-align:right;vertical-align:top}
a {
  text-decoration: none;
  color: #000000;
}
</style>

	</head>
<body>



<iframe src="https://home.ariekraakjr.nl/gps/map.php?zoom=12&center=53.14,5.86&track=<?php echo $activeTracks; ?>" style="border:0px #ffffff none;" name="myiFrame" scrolling="no" frameborder="0" marginheight="0px" marginwidth="0px" height="80%" width="<?php echo $cardWidth; ?>%"  align="right" allowfullscreen></iframe>

<hr>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
Afstand: min
	<select name="minkmselect" id="minkmselect" onchange="this.form.submit()">
		<?php
			foreach ($kmSelectValues as $value) {
				print "<option value=".$value;
				if($value == $minKm) { print " selected";}
				print ">".$value."</option>\n";
			} 
		?>
	</select>
max
	<select name="maxkmselect" id="maxkmselect" onchange="this.form.submit()">
		<?php
			foreach ($kmSelectValues as $value) {
				print "<option value=".$value;
				if($value == $maxKm) { print " selected";}
				print ">".$value."</option>\n";
			} 
		?>
    </select>
<a href="<?php echo $_SERVER['PHP_SELF']; ?>">reset</a>

</form>


<?php print "\n".$tableGpxInfo."\n"; ?>


</body>
</html>



