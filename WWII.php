<!DOCTYPE html>
<?php
$server = 'whale.csse.rose-hulman.edu';

// Connect to MSSQL
$link = odbc_connect("Driver={SQL Server Native Client 10.0};Server=whale.csse.rose-hulman.edu;Database=Nadine_WWII", 'coxbc', 'whales');

if (!$link) {
    die('Something went wrong while connecting to MSSQL');
}
?>
<?php
session_cache_expire( 20 );
session_start(); // NEVER FORGET TO START THE SESSION!!!
$inactive = 300;
if(isset($_SESSION['start']) ) {
	$session_life = time() - $_SESSION['start'];
	if($session_life > $inactive){
		$_SESSION['loggedin'] = false;
		session_destroy();
		header("Location: /Login.php");
	}
}
$_SESSION['start'] = time();
if($_SESSION['loggedin'] != true){
header("Location: /Login.php");
exit();
}




//session_start();
//if ($_SESSION['loggedin'] !== TRUE) {
  // header("Location: /Login.php");
   //echo 'You must log in first';
   //exit();
   
//$inactive = 5; 
//$session_life = time() - $_session['timeout'];
//if($session_life > $inactive)
//{  session_destroy(); header("Location: /Login.php");     }
//$_session['timeout']=time();
//}
?>
<html>
<head>
<title>WWII</title>
<script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyBf_PyFp4dnlbBqm_H3cALDykYQm8dUKBI&sensor=false">
</script>
<link rel="stylesheet" type="text/css" href="styles.css" media="screen" />
<script>

var markersArray = [];

function initialize()
{
	var mapProp = {
	  center:new google.maps.LatLng(51.508742,-0.120850),
	  zoom:5,
	  mapTypeId:google.maps.MapTypeId.ROADMAP
	};
	this.map=new google.maps.Map(document.getElementById("googleMap"),mapProp);	
	
	var styles = [
  {
    featureType: "road",
    stylers: [
      { visibility: "off" }
    ]
  },{
	featureType: "poi",
	stylers: [
		{ visibility: "simplified" }
	]
	},{
	featureType: "administrative.province",
	elementType: "geometry.stroke",
	stylers: [
		{visibility: "off"}
	]
	
	}
  
];

	map.setOptions({styles: styles});
	
	if (window.XMLHttpRequest)
   {// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
   }
	
	xmlhttp.onreadystatechange = function()
	{
		var element = "";
		if (xmlhttp.readyState==4 && xmlhttp.status==200){
//			document.getElementById("txtHint").innerHTML=xmlhttp.responseText;
			element = xmlhttp.responseText
		}
		
		
		var battles = element.split("<br>");
		
		var i = 0;
		while(i < battles.length){
		
			var battletbl = battles[i].split(",");
			var name = battletbl[0];
			var id = parseInt(battletbl[1]);
			
			var lat = parseFloat(battletbl[2]);
			var longi = parseFloat(battletbl[3]);
			

			var marker = new google.maps.Marker({
			position: new google.maps.LatLng(lat, longi),
			map: map,
			title: name
			});
			
			markersArray.push(marker);
			
			i = i + 1;
		}
		
		
	}
	xmlhttp.open("GET","dbaccess.php?" +"country=" + $_GET['countrytbl'],true);
	xmlhttp.send();

}

function clearOverlays() {
  for (var i = 0; i < markersArray.length; i++ ) {
    markersArray[i].setMap(null);
  }
}

function TestFunction(){
	
	if (window.XMLHttpRequest)
   {// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
   }
	
	xmlhttp.onreadystatechange = function()
	{
	
		clearOverlays();
	/*
		for ( i in markersarray){
	//		alert("Got one");
			markersarray[i].setMap(null);
			map.removeOverlay(markersarray[i]);
	//		markersarray.length = 0;
		}
	*/
		var element = "";
		if (xmlhttp.readyState==4 && xmlhttp.status==200){
//			document.getElementById("txtHint").innerHTML=xmlhttp.responseText;
			element = xmlhttp.responseText
		}
		
				var battles = element.split("<br>");
		
		var i = 0;
		while(i < battles.length){
		
			var battletbl = battles[i].split(",");
			var name = battletbl[0];
			var id = parseInt(battletbl[1]);
			
			var lat = parseFloat(battletbl[2]);
			var longi = parseFloat(battletbl[3]);
			
			var marker = new google.maps.Marker({
			position: new google.maps.LatLng(lat, longi),
			map: map,
			title: name
			});
			
			markersArray.push(marker);
			GEvent.addListener(marker, "click", function()
{marker.openInfoWindowHtml('here I am');});

			i = i + 1;
		}
		
	}
//	alert("OHNO");
//	alert(document.getElementById("countrytable").value);
	if(document.getElementById("countrytable").value == "Any"){
		xmlhttp.open("GET","dbaccess.php",true);
	}
	else{
		xmlhttp.open("GET","dbaccess.php?" +"country=" + document.getElementById("countrytable").value,true);
	}
	xmlhttp.send();
		
}	

google.maps.event.addDomListener(window, 'load', initialize);

</script>
</head>
<body>

<h1>WWII</h1>

<div id="googleMap" style="width: 100%; height: 700px; border:1px solid gray;""></div>
<div id="slider" style="width: 98%; position:absolute; margin: 0 auto;" >
<div style="float: left">Start of WWII</div> <div style="float: right">END of WWII</div>
<input id="slide" type="range"
 min="5" max="200" step="5" value="100"
 onchange="updateSlider(this.value)" style="width: 100%;"/>
</div><br/><br/>
<table cellspacing="0" name="comboBoxes">
	<tr>

		<td id="logoTbl">
			<label><strong>Countries</strong></label>
			<select id = "countrytable" name = "countrytbl" ONCHANGE = "TestFunction()">
			<?php
				echo '<option>' . "Any" . '</option>';
				$data = odbc_exec($link, "SELECT Name FROM Country ORDER BY Name");

				while (odbc_fetch_row($data)) {
				echo '<option>' . odbc_result($data, 1) . '</option>';
				}
			?>
			</select>
		</td>

		<td id="logoTbl">
		
			<label><strong>Commanders</strong></label>
			<select name = "cmdrtbl" ONCHANGE = "TestFunction()">
			<?php

				$data = odbc_exec($link, "SELECT Name FROM Commander ORDER BY Name");
				while (odbc_fetch_row($data)) {
				echo '<option value=odbc_result($data, 1)>' . odbc_result($data, 1) . '</option>';
				}
			?>
			</select>
		</td>
	</tr>
</table>
</body>
</html>
