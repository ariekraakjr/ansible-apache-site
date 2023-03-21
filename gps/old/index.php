<?php
// Funcntion for calculating distance between to sets of lat/long points

function distance($lat1, $lon1, $lat2, $lon2, $unit = 'K') {
//function distance(
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

//function getDistanceBetweenPointsNew($latitude1, $longitude1, $latitude2, $longitude2, $unit = 'kilometers') {
//  $theta = $longitude1 - $longitude2; 
//  $distance = (sin(deg2rad($latitude1)) * sin(deg2rad($latitude2))) + (cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * cos(deg2rad($theta))); 
//  $distance = acos($distance); 
//  $distance = rad2deg($distance); 
//  $distance = $distance * 60 * 1.1515; 
//  switch($unit) { 
//    case 'miles': 
//      break; 
//    case 'kilometers' : 
//      $distance = $distance * 1.609344; 
//  }  
//  //return (round($distance,2));
//  return $distance;
//}

echo '<p>', gettype(52.9685697), ' ', distance(52.9685697, 5.612061, 52.9677297, 5.6125546), '<p>';

// Read GPX file, find track lat/lon attributes and loop

$xml=simplexml_load_file("SUP Away Grou - Rondje Burd.gpx");

echo $xml->trk->name;
echo "<br>";

//$last_lat = false;
//$last_lon = false;
$total_distance = 0;

foreach( $xml->trk->trkseg->{'trkpt'} as $trkpt ) {
    $trkptlat = $trkpt->attributes()->lat;
    $trkptlon = $trkpt->attributes()->lon;
//	if(!isset($last_lat) && !isset($last_lon) ){
//		$last_lat = $trkptlat;
//		$last_lon = $trkptlon;
//	}

    if($last_lat){
        $total_distance+=distance($trkptlat, $trkptlon, $last_lat, $last_lon);
//		echo 'longlat: ', $trkptlat, ' ', $trkptlon, '| prevlonglat: ', $last_lat, ' ', $last_lon, ' | Distance:', distance($trkptlat, $trkptlon, $last_lat, $last_lon), '<br>';
//		echo 'longlat: ', $trkptlat, ' ', $trkptlon, '| prevlonglat: ', $last_lat, ' ', $last_lon, ' | ', distance(settype($trkptlat,double), settype($trkptlon,double), settype($last_lat,double), settype($last_lon,double)), ' ', '<br>';
//		echo $trkptlat, ', ', $trkptlon, ', ', $last_lat, ', ', $last_lon, ' | ', gettype($trkptlat), ' ', gettype($trkptlon), ' ', gettype($last_lat), ' ', gettype($last_lon), ' | ' ;
		//echo $total_distance, '<br>';
    }
    $last_lat = $trkptlat;
    $last_lon = $trkptlon;
}
echo 'Totaal: ', round($total_distance,2);

?>