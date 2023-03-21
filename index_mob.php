<?php
// Create the function, so you can use it
function isMobile() {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}
// If the user is on a mobile device, redirect them
if(!isMobile() && (!isset($_GET["mob"]))){
    header("Location: https://home.ariekraakjr.nl/gps/index.php");
}
?>
<html>
<body>

<?php
	if($_GET["track"]){
?>
<iframe src="https://home.ariekraakjr.nl/gps/map.php?track=<?php echo htmlspecialchars($_GET["track"]); ?>" style="border:0px #ffffff none;" name="myiFrame" scrolling="no" frameborder="0" marginheight="0px" marginwidth="0px" height="80%" width="100%"  align="right" allowfullscreen></iframe>
<?php
	} else {
?>
<iframe src="https://home.ariekraakjr.nl/gps/map.php?zoom=12&center=53.14,5.86" style="border:0px #ffffff none;" name="myiFrame" scrolling="no" frameborder="0" marginheight="0px" marginwidth="0px" height="80%" width="100%"  align="right" allowfullscreen></iframe>
<?php
	}
?>


<p>
<style type="text/css">
.tg  {border-collapse:collapse;border-spacing:0;}
.tg td{border-color:black;border-style:solid;border-width:1px;font-family:Arial, sans-serif;font-size:14px;
  overflow:hidden;padding:10px 5px;word-break:normal;}
.tg th{border-color:black;border-style:solid;border-width:1px;font-family:Arial, sans-serif;font-size:14px;
  font-weight:normal;overflow:hidden;padding:10px 5px;word-break:normal;}
.tg .tg-kftd{background-color:#efefef;text-align:left;vertical-align:top}
.tg .tg-0lax{text-align:left;vertical-align:top}
.tg .tg-73oq{border-color:#000000;text-align:left;vertical-align:top}
a {
  text-decoration: none;
}
</style>
<table class="tg">
<thead>
  <tr>
    <th class="tg-0lax">Route</th>
    <th class="tg-kftd">Download</th>
    <th class="tg-73oq">Fullscreen</th>
  </tr>
</thead>
<tbody>
<!--   <tr>
    <td class="tg-0lax"></td>
    <td class="tg-kftd"></td>
   <td class="tg-0lax"></td>
  </tr>-->

<?php

$fileList = glob('gpx/*.gpx');
foreach($fileList as $filename){
    //Use the is_file function to make sure that it is not a directory.
    if(is_file($filename)){
		$track = preg_replace('/gpx\/|.gpx/i','',$filename);
//        echo '<a href=\'https://home.ariekraakjr.nl/gps/index.php?track=', $track, '\'>', $track, '</a><br>'; 
//        echo '<a href=\'', $filename, '\'>', $track, '.gpx</a> '; 
		echo '<tr><td class="tg-0lax"><a href=\'https://home.ariekraakjr.nl/gps/index.php?track=', $track, '\'>', $track, '</a></td><td class="tg-kftd"><a href=\'', $filename, '\'>gpx</a></td><td class="tg-0lax"><a href=\'https://home.ariekraakjr.nl/gps/map.php?track=', $track, '\'>', show, '</a></td></tr>';
			
			

    }   
}

?>
</tbody>
</table>


</body>
</html>