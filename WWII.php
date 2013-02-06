<!DOCTYPE html>
<?php

session_start();
if ($_SESSION['loggedin'] !== TRUE) {
   header("Location: /wwiidatabase/Login.php");
   echo 'You must log in first';
   exit();
}
?>
<html>
<head>

<title>WWII</title>
<script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyDY0kkJiTPVd2U7aTOAwhc9ySH6oHxOIYM&sensor=false">
</script>
<link rel="stylesheet" type="text/css" href="styles.css" media="screen" />
<script>
function initialize()
{
var mapProp = {
  center:new google.maps.LatLng(51.508742,-0.120850),
  zoom:5,
  mapTypeId:google.maps.MapTypeId.ROADMAP
  };
var map=new google.maps.Map(document.getElementById("googleMap")
  ,mapProp);
};

google.maps.event.addDomListener(window, 'load', initialize);

</script>
    <script type="text/javascript">
      function initialize() {
        var mapOptions = {
          zoom: 4,
          center: new google.maps.LatLng(-25.363882, 131.044922),
          mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        var map = new google.maps.Map(document.getElementById("map_canvas"),
            mapOptions);

        var marker = new google.maps.Marker({
          position: map.getCenter(),
          map: map,
          title: 'Click to zoom'
        });

        google.maps.event.addListener(map, 'center_changed', function() {
          // 3 seconds after the center of the map has changed, pan back to the
          // marker.
          window.setTimeout(function() {
            map.panTo(marker.getPosition());
          }, 3000);
        });

        google.maps.event.addListener(marker, 'click', function() {
          map.setZoom(8);
          map.setCenter(marker.getPosition());
        });
      }

      google.maps.event.addDomListener(window, 'load', initialize);
	</script>
	<script>
	function testphpCall(){
	alert("check");
	}
	</script>
</head>
<body>

<h1>WWII</h1>
<?php
	function test(){
	echo 'Test';
	echo '<script type="text/javascript">'
   , 'testphpCall();'
   , '</script>';
	}
   ?>
<div id="map_canvas" style="width:100%; height:100%"></div>
<div id="googleMap" style="width: 100%; height: 700px; border:1px solid gray;""></div>
<div id="slider" style="width: 100%; position:absolute; margin: 0 auto;" >
<div style="float: left">Start of WWII</div> <div style="float: right">END of WWII</div>
<input id="slide" type="range"
 min="5" max="200" step="5" value="100"
 onchange="updateSlider(this.value)" style="width: 100%;"/>
</div><br/><br/>

	<tr>

		
		<td id="logoTbl">
			<label><strong>Type</strong></label>
			<select onchange="test()">
			<option value="volvo"">Volvo</option>
			<option value="saab">Saab</option>
			<option value="mercedes">Mercedes</option>
			<option value="audi">Audi</option>
			</select>
		</td>
	</tr>
</table>
</body>
</html>
