<?php
session_start(); 
if(!isset($_GET["BodyShopID"]) AND !isset($_SESSION['BodyShopID'])) {
	header('Location: guimissingbodyshop.php');
	}
$Page = "Damage Categories";
require_once('counter.inc');

$_SESSION['damagepage'] = 1;
$_SESSION['damage'] = 'Miscellaneous';

$stmt = $pdo->prepare('SELECT * FROM requestor WHERE RequestorID = :RequestorID');
$stmt->execute(array('RequestorID' => $_SESSION['RequestorID']));
$result = $stmt->fetch(PDO::FETCH_ASSOC);

/*
$status = strlen($result['AccidentFrontBumperPic1']) . ' ' . strlen($result['AccidentFrontBumperPic2']) . ' ';
$status .= strlen($result['AccidentDriverFrontFenderPic1'])  . ' ' . strlen($result['AccidentDriverFrontFenderPic2']) . ' ';
$status .= strlen($result['AccidentHoodPic1'])  . ' ' . strlen($result['AccidentHoodPic2']) . ' ';
$status .= strlen($result['AccidentPassengerFrontFenderPic1'])  . ' ' . strlen($result['AccidentPassengerFrontFenderPic2'])  . ' ';
$status .= strlen($result['AccidentDriverFrontDoorPic1'])  . ' ' . strlen($result['AccidentDriverFrontDoorPic2'])  . ' ';
$status .= strlen($result['AccidentPassengerFrontDoorPic1']) . ' ' . strlen($result['AccidentPassengerFrontDoorPic2'])  . ' ';
$status .= strlen($result['AccidentDriverRearDoorPic1']) . ' ' . strlen($result['AccidentDriverRearDoorPic2'])  . ' ';
$status .= strlen($result['AccidentRoofPic1']) . ' ' . strlen($result['AccidentRoofPic2']) . ' ';
$status .= strlen($result['AccidentPassengerRearDoorPic1']) . ' ' . strlen($result['AccidentPassengerRearDoorPic2'])  . ' ';
$status .= strlen($result['AccidentDriverQuarterPanelPic1']) . ' ' . strlen($result['AccidentDriverQuarterPanelPic2'])  . ' ';
$status .= strlen($result['AccidentTrunkPic1']) . ' ' . strlen($result['AccidentTrunkPic2']) . ' ';
$status .= strlen($result['AccidentPassengerQuarterPanelPic1']) . ' ' . strlen($result['AccidentPassengerQuarterPanelPic2']) . ' ';
$status .= strlen($result['AccidentRearBumperPic1']) . ' ' . strlen($result['AccidentRearBumperPic2']) . ' ';
*/

$sqlm = "SELECT * FROM bodyshoplocation WHERE Deleted <> 'Y' AND BodyShopID=" . $_SESSION['BodyShopID'];
$sqlm = $pdo->prepare($sqlm);
$sqlm->execute();
while ($resultm = $sqlm->fetch(PDO::FETCH_ASSOC)){
	if ($resultm['Longitude'] == 0.00000000) {
		//Look up longitude and latitude
		$address = $resultm['LocationAddress'] . ',' . $resultm['Province']; // Google HQ
		$prepAddr = str_replace(' ','+',$address);
		$geocode=file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&sensor=false');
		$output= json_decode($geocode);
		$latitude = $output->results[0]->geometry->location->lat;
		$longitude = $output->results[0]->geometry->location->lng;
		
		//update database table
		$sql = 'UPDATE bodyshoplocation SET Longitude = :Longitude, Latitude = :Latitude WHERE LocationID=:LocationID';
		$sql = $pdo->prepare($sql);
		$sql->execute(array('Longitude' => $longitude, 'Latitude' => $latitude, 'LocationID' => $resultm['LocationID']));
		}
	else {
		$latitude = $resultm['Latitude'];
		$longitude = $resultm['Longitude'];
		}
	}


$ip = $_SERVER['REMOTE_ADDR'];
$sqlip = "SELECT * FROM geoip WHERE network_start_ip<='" . $ip . "' AND network_last_ip >= '" . $ip . "'";
$sqlip = $pdo->prepare($sqlip);
$sqlip->execute();
$resultip = $sqlip->fetch(PDO::FETCH_ASSOC);

$clientlatitude = $resultip['latitude'];
$clientlongitude = $resultip['longitude'];
?>

<!DOCTYPE HTML>
<html>
	<head>
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta charset="utf-8" />
		<!--<meta name="viewport" content="width=device-width, initial-scale=1" />-->
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="assets/css/main.css" />
		<!--[if lte IE 8]><link rel="stylesheet" href="assets/css/ie8.css" /><![endif]-->
		<!--[if lte IE 9]><link rel="stylesheet" href="assets/css/ie9.css" /><![endif]-->
		<link rel="stylesheet" type="text/css" href="css/appsite.css">
        <style>
			.damagerightarrow img {
				width: 18px;
				height: 18px;
				}
			.wrappedup       {width:80%; max-width: 560px; height:100%; margin:0 auto;}
			.h_iframe        {position:relative; padding-top: 56%;}
			.h_iframe iframe {position:absolute;top:0;left:0;width:100%; height:100%;}
		</style>
		<script>
			window.scrollTo(0, 0);
		</script>
	</head>
	<body class="defaulttext" onload="this.requestFullscreen();hideAddressBar();"> 
		<div class="pagetitle">
			<h4 class="title">
				<div id="centerheader" >
					<?php include('guicall.inc'); ?>
					Select Damaged Panels 
				</div>
			</h4>
		</div>

		<!-- Nav -->
			<!--
			<nav id="nav">
				<ul class="container">
					<li><a href="#top">Top</a></li>
					<li><a href="#work">Work</a></li>
					<li><a href="#portfolio">Portfolio</a></li>
					<li><a href="#contact">Contact</a></li>
				</ul>
			</nav>
			-->
		<!-- Home -->
			<div id="damagepanelwrapper" class="wrapper style1 first" style="display:block;z-index:-1">
				<article id="top" >
					<div class="row" style="z-index:-1">
						<div class="12u 24u(mobile)" style="z-index:-1">
							<div id="bodytext" style="height:100%;overflow:hidden;z-index:10;" >
								<div style="height: 500px;width: 100%;height:99%;overflow: auto;padding-right: 20px;z-index:-1" >
									<form action="" style="width:300px;margin-left:auto;margin-right:auto;z-index:-1">
										<?php 
										switch ($_SESSION["VehicleType"]) {
											case "car":
												?>
												<!--<img src="images/car_green.jpg" alt="" style="position:absolute;top:100px;margin-left:auto;margin-right:auto;" />-->
												<img src="images/car_gray.jpg" alt="" usemap="#map" style="position:absolute;top:50px;margin-left:auto;margin-right:auto;opacity: 1.0; z-index:1;" />
												<map name="map">
													<area shape="poly" coords="66, 42, 89, 25, 111, 20, 139, 20, 169, 20, 199, 29, 217, 44, 212, 26, 183, 8, 150, 3, 123, 2, 102, 3, 82, 16, 69, 26" onclick="uploadphoto(1,'car','Front Bumper')" alt="Front Bumper" />
													<area shape="poly" coords="59, 107, 73, 107, 84, 67, 104, 24, 89, 24, 65, 43, 60, 60, 57, 82" onclick="uploadphoto(1,'car','Drivers Front Panel')" alt="Front Panel" />
													<area shape="poly" coords="76, 105, 84, 67, 106, 24, 142, 25, 177, 24, 187, 40, 197, 61, 203, 87, 207, 106, 183, 96, 155, 91, 124, 91, 94, 98" onclick="uploadphoto(1,'car','Hood')" alt="Hood" />
													<area shape="poly" coords="222, 107, 223, 85, 225, 58, 218, 44, 200, 28, 177, 24, 187, 38, 196, 59, 202, 84, 205, 104, 212, 111" onclick="uploadphoto(1,'car','Passengers Front Fender')" alt="Front Fender" />
													<area shape="poly" coords="58, 108, 68, 118, 68, 127, 57, 144, 45, 164, 15, 195, 1, 165, 16, 149, 41, 127, 25, 121, 32, 112, 41, 112, 49, 117" onclick="uploadphoto(1,'car','Drivers Front Door')" alt="Driver Front Door" />
													<area shape="poly" coords="210, 117, 223, 107, 233, 118, 247, 112, 256, 117, 242, 128, 277, 159, 280, 163, 272, 187, 268, 196, 240, 170" onclick="uploadphoto(1,'car','Passengers Front Door')" alt="Passenger Front Door" />
													<area shape="poly" coords="63, 188, 5, 247, 17, 251, 51, 251, 78, 221" onclick="uploadphoto(1,'car','Drivers Rear Door')" alt="Driver Rear Door" />
													<area shape="poly" coords="88, 156, 125, 149, 150, 149, 194, 155, 189, 245, 187, 265, 179, 272, 158, 277, 134, 278, 107, 275, 95, 268, 93, 242, 92, 193" onclick="uploadphoto(1,'car','Roof')" alt="Roof" />
													<area shape="poly" coords="220, 190, 214, 204, 205, 218, 230, 250, 260, 251, 265, 254, 276, 246" onclick="uploadphoto(1,'car','Passengers Rear Door')" alt="Passenger Rear Door" />
													<area shape="poly" coords="92, 243, 74, 262, 72, 265, 58, 271, 58, 295, 64, 309, 66, 330, 75, 343, 91, 352, 96, 353, 94, 311, 94, 268" onclick="uploadphoto(1,'car','Drivers Quarter Panel')" alt="Driver Quarter Panel" />
													<area shape="poly" coords="184, 310, 187, 314, 187, 346, 172, 359, 142, 359, 119, 359, 97, 354, 95, 313, 99, 310, 112, 316, 131, 319, 156, 317, 173, 313" onclick="uploadphoto(1,'car','Trunk')" alt="Trunk" />
													<area shape="poly" coords="190, 244, 208, 263, 210, 270, 220, 270, 223, 290, 218, 307, 216, 321, 210, 335, 194, 348, 187, 346, 186, 314, 188, 268" onclick="uploadphoto(1,'car','Passengers Quarter Panel')" alt="Passenger Quarter Panel" />
													<area shape="poly" coords="67, 331, 74, 343, 91, 354, 118, 360, 144, 361, 173, 358, 194, 350, 210, 337, 217, 323, 211, 352, 200, 367, 195, 376, 172, 381, 143, 382, 113, 382, 90, 378, 79, 369, 71, 353" onclick="uploadphoto(1,'car','Rear Bumper')" alt="Rear Bumper" />
													<!--
													<area shape="poly" coords="66, 42, 89, 25, 111, 20, 139, 20, 169, 20, 199, 29, 217, 44, 212, 26, 183, 8, 150, 3, 123, 2, 102, 3, 82, 16, 69, 26" href="guipersonaluploaddamage.php?page=1&VehicleType=<?php echo $_SESSION['VehicleType']; ?>&damage=Front Bumper" alt="Front Bumper" />
													<area shape="poly" coords="59, 107, 73, 107, 84, 67, 104, 24, 89, 24, 65, 43, 60, 60, 57, 82" href="guipersonaluploaddamage.php?page=1&VehicleType=<?php echo $_SESSION['VehicleType']; ?>&damage=Drivers Front Panel" alt="Front Panel" />
													<area shape="poly" coords="76, 105, 84, 67, 106, 24, 142, 25, 177, 24, 187, 40, 197, 61, 203, 87, 207, 106, 183, 96, 155, 91, 124, 91, 94, 98" href="guipersonaluploaddamage.php?page=1&VehicleType=<?php echo $_SESSION['VehicleType']; ?>&damage=Hood" alt="Hood" />
													<area shape="poly" coords="222, 107, 223, 85, 225, 58, 218, 44, 200, 28, 177, 24, 187, 38, 196, 59, 202, 84, 205, 104, 212, 111" href="guipersonaluploaddamage.php?page=1&VehicleType=<?php echo $_SESSION['VehicleType']; ?>&damage=Passengers Front Fender" alt="Front Fender" />
													<area shape="poly" coords="58, 108, 68, 118, 68, 127, 57, 144, 45, 164, 15, 195, 1, 165, 16, 149, 41, 127, 25, 121, 32, 112, 41, 112, 49, 117" href="guipersonaluploaddamage.php?page=1&VehicleType=<?php echo $_SESSION['VehicleType']; ?>&damage=Drivers Front Door" alt="Driver Front Door" />
													<area shape="poly" coords="210, 117, 223, 107, 233, 118, 247, 112, 256, 117, 242, 128, 277, 159, 280, 163, 272, 187, 268, 196, 240, 170" href="guipersonaluploaddamage.php?page=1&VehicleType=<?php echo $_SESSION['VehicleType']; ?>&damage=Passengers Front Door" alt="Passenger Front Door" />
													<area shape="poly" coords="63, 188, 5, 247, 17, 251, 51, 251, 78, 221" href="guipersonaluploaddamage.php?page=1&VehicleType=<?php echo $_SESSION['VehicleType']; ?>&damage=Drivers Rear Door" alt="Driver Rear Door" />
													<area shape="poly" coords="88, 156, 125, 149, 150, 149, 194, 155, 189, 245, 187, 265, 179, 272, 158, 277, 134, 278, 107, 275, 95, 268, 93, 242, 92, 193" href="guipersonaluploaddamage.php?page=1&VehicleType=<?php echo $_SESSION['VehicleType']; ?>&damage=Roof" alt="Roof" />
													<area shape="poly" coords="220, 190, 214, 204, 205, 218, 230, 250, 260, 251, 265, 254, 276, 246" href="guipersonaluploaddamage.php?page=1&VehicleType=<?php echo $_SESSION['VehicleType']; ?>&damage=Passengers Rear Door" alt="Passenger Rear Door" />
													<area shape="poly" coords="92, 243, 74, 262, 72, 265, 58, 271, 58, 295, 64, 309, 66, 330, 75, 343, 91, 352, 96, 353, 94, 311, 94, 268" href="guipersonaluploaddamage.php?page=1&VehicleType=<?php echo $_SESSION['VehicleType']; ?>&damage=Drivers Quarter Panel" alt="Driver Quarter Panel" />
													<area shape="poly" coords="184, 310, 187, 314, 187, 346, 172, 359, 142, 359, 119, 359, 97, 354, 95, 313, 99, 310, 112, 316, 131, 319, 156, 317, 173, 313" href="guipersonaluploaddamage.php?page=1&VehicleType=<?php echo $_SESSION['VehicleType']; ?>&damage=Trunk" alt="Trunk" />
													<area shape="poly" coords="190, 244, 208, 263, 210, 270, 220, 270, 223, 290, 218, 307, 216, 321, 210, 335, 194, 348, 187, 346, 186, 314, 188, 268" href="guipersonaluploaddamage.php?page=1&VehicleType=<?php echo $_SESSION['VehicleType']; ?>&damage=Passengers Quarter Panel" alt="Passenger Quarter Panel" />
													<area shape="poly" coords="67, 331, 74, 343, 91, 354, 118, 360, 144, 361, 173, 358, 194, 350, 210, 337, 217, 323, 211, 352, 200, 367, 195, 376, 172, 381, 143, 382, 113, 382, 90, 378, 79, 369, 71, 353" href="guipersonaluploaddamage.php?page=1&VehicleType=<?php echo $_SESSION['VehicleType']; ?>&damage=Rear Bumper" alt="Rear Bumper" />
													-->
												</map>
												<div style="width:50px;height:50px;"><img src="images/checkmark2.png" id="FrontBumper" border="0" style="position:absolute;top:40px;margin-left:auto;margin-right:auto;padding-left:125px;z-index:99999;visibility:hidden;"></div>
												<div style="width:50px;height:50px;"><img src="images/checkmark2.png" id="DriverFrontFender" border="0" style="position:absolute;top:110px;margin-left:auto;margin-right:auto;padding-left:50px;z-index:99999;visibility:hidden;"></div>
												<div style="width:50px;height:50px;"><img src="images/checkmark2.png" id="Hood" border="0" style="position:absolute;top:110px;margin-left:auto;margin-right:auto;padding-left:125px;z-index:99999;visibility:hidden;"></div>
												<div style="width:50px;height:50px;"><img src="images/checkmark2.png" id="PassengerFrontFender" border="0" style="position:absolute;top:110px;margin-left:auto;margin-right:auto;padding-left:200px;z-index:99999;visibility:hidden;"></div>
												<div style="width:50px;height:50px;"><img src="images/checkmark2.png" id="DriverFrontDoor" border="0" style="position:absolute;top:210px;margin-left:auto;margin-right:auto;padding-left:20px;z-index:99999;visibility:hidden;"></div>
												<div style="width:50px;height:50px;"><img src="images/checkmark2.png" id="PassengerFrontDoor" border="0" style="position:absolute;top:210px;margin-left:auto;margin-right:auto;padding-left:240px;z-index:99999;visibility:hidden;"></div>
												<div style="width:50px;height:50px;"><img src="images/checkmark2.png" id="DriverRearDoor" border="0" style="position:absolute;top:280px;margin-left:auto;margin-right:auto;padding-left:20px;z-index:99999;visibility:hidden;"></div>
												<div style="width:50px;height:50px;"><img src="images/checkmark2.png" id="Roof" border="0" style="position:absolute;top:260px;margin-left:auto;margin-right:auto;padding-left:125px;z-index:99999;visibility:hidden;"></div>
												<div style="width:50px;height:50px;"><img src="images/checkmark2.png" id="PassengerRearDoor" border="0" style="position:absolute;top:280px;margin-left:auto;margin-right:auto;padding-left:240px;z-index:99999;visibility:hidden;"></div>
												<div style="width:50px;height:50px;"><img src="images/checkmark2.png" id="DriverQuarterPanel" border="0" style="position:absolute;top:350px;margin-left:auto;margin-right:auto;padding-left:55px;z-index:99999;visibility:hidden;"></div>
												<div style="width:50px;height:50px;"><img src="images/checkmark2.png" id="Trunk" border="0" style="position:absolute;top:380px;margin-left:auto;margin-right:auto;padding-left:125px;z-index:99999;visibility:hidden;"></div>
												<div style="width:50px;height:50px;"><img src="images/checkmark2.png" id="PassengerQuarterPanel" border="0" style="position:absolute;top:350px;margin-left:auto;margin-right:auto;padding-left:195px;z-index:99999;visibility:hidden;"></div>
												<div style="width:50px;height:50px;"><img src="images/checkmark2.png" id="RearBumper" border="0" style="position:absolute;top:418px;margin-left:auto;margin-right:auto;padding-left:125px;z-index:99999;visibility:hidden;"></div>
												<?php 
												break;
											case "truck":
												?>
												<img src="images/pickup_grey.jpg" alt="" usemap="#map" style="position:absolute;top:50px;margin-left:auto;margin-right:auto;opacity: 1.0;z-index:1;" />
												<map name="map">
													<area shape="poly" coords="66, 55, 64, 28, 92, 12, 96, 4, 186, 4, 191, 10, 213, 25, 219, 31, 218, 57, 194, 35, 171, 28, 106, 27, 88, 36" onclick="uploadphoto(1,'truck','Front Bumper')" alt="Front Bumper" />
													<area shape="poly" coords="65, 132, 79, 132, 77, 127, 88, 122, 86, 36, 67, 53" onclick="uploadphoto(1,'truck','Drivers Front Panel')" alt="Drivers Front Panel" />
													<area shape="poly" coords="193, 107, 193, 34, 171, 28, 105, 29, 88, 35, 89, 109" onclick="uploadphoto(1,'truck','Hood')" alt="Hood" />
													<area shape="poly" coords="219, 132, 207, 132, 206, 125, 193, 122, 195, 34, 218, 57" onclick="uploadphoto(1,'truck','Passengers Front Fender')" alt="Passengers Front Fender" />
													<area shape="poly" coords="10, 217, 47, 189, 77, 151, 67, 132, 52, 144, 43, 137, 33, 142, 42, 149, 29, 160, 19, 170, 1, 183" onclick="uploadphoto(1,'truck','Drivers Front Door')" alt="Driver Front Door" />
													<area shape="poly" coords="207, 151, 219, 134, 230, 144, 242, 137, 248, 140, 242, 151, 257, 168, 280, 183, 273, 214, 238, 189" onclick="uploadphoto(1,'truck','damage=Passengers Front Door')" alt="Passenger Front Door" />
													<area shape="poly" coords="24, 282, 76, 242, 71, 215, 18, 252" onclick="uploadphoto(1,'truck','Drivers Rear Door')" alt="Driver Rear Door" />
													<area shape="poly" coords="85, 164, 141, 157, 199, 166, 199, 290, 83, 291" onclick="uploadphoto(1,'truck','Roof')" alt="Roof" />
													<area shape="poly" coords="265, 252, 259, 281, 206, 243, 214, 214" onclick="uploadphoto(1,'truck','Passengers Rear Door')" alt="Passenger Rear Door" />
													<area shape="poly" coords="68, 300, 64, 310, 65, 333, 60, 340, 58, 391, 63, 399, 69, 419, 71, 430, 80, 432, 80, 418, 88, 418, 88, 392, 95, 391, 95, 342, 90, 336, 89, 326, 93, 324, 93, 310, 82, 307" onclick="uploadphoto(1,'truck','Drivers Quarter Panel')" alt="Drivers Quarter Panel"  />
													<area shape="poly" coords="193, 417, 195, 397, 187, 390, 187, 343, 193, 336, 194, 322, 189, 319, 189, 306, 92, 309, 93, 323, 87, 325, 89, 337, 94, 342, 95, 391, 89, 391, 89, 418" onclick="uploadphoto(1,'truck','Trunk')" alt="Trunk" />
													<area shape="poly" coords="215, 304, 219, 310, 220, 339, 225, 347, 219, 402, 215, 425, 205, 432, 203, 420, 193, 420, 193, 397, 188, 390, 188, 342, 193, 335, 193, 321, 188, 317, 188, 306" onclick="uploadphoto(1,'truck','Passengers Quarter Panel')" alt="Passengers Quarter Panel" />
													<area shape="poly" coords="68, 430, 68, 444, 77, 450, 124, 451, 173, 451, 210, 449, 219, 438, 217, 426, 205, 431, 80, 431" onclick="uploadphoto(1,'truck','Rear Bumper')" alt="Rear Bumper" />
												</map>

												<div style="width:50px;height:50px;"><img src="images/checkmark2.png" id="FrontBumper" border="0" style="position:absolute;top:50px;margin-left:auto;margin-right:auto;padding-left:125px;z-index:99999;visibility:hidden;"></div>
												<div style="width:50px;height:50px;"><img src="images/checkmark2.png" id="DriverFrontFender" border="0" style="position:absolute;top:110px;margin-left:auto;margin-right:auto;padding-left:50px;z-index:99999;visibility:hidden;"></div>
												<div style="width:50px;height:50px;"><img src="images/checkmark2.png" id="Hood" border="0" style="position:absolute;top:110px;margin-left:auto;margin-right:auto;padding-left:125px;z-index:99999;visibility:hidden;"></div>
												<div style="width:50px;height:50px;"><img src="images/checkmark2.png" id="PassengerFrontFender" border="0" style="position:absolute;top:110px;margin-left:auto;margin-right:auto;padding-left:200px;z-index:99999;visibility:hidden;"></div>
												<div style="width:50px;height:50px;"><img src="images/checkmark2.png" id="DriverFrontDoor" border="0" style="position:absolute;top:220px;margin-left:auto;margin-right:auto;padding-left:20px;z-index:99999;visibility:hidden;"></div>
												<div style="width:50px;height:50px;"><img src="images/checkmark2.png" id="PassengerFrontDoor" border="0" style="position:absolute;top:220px;margin-left:auto;margin-right:auto;padding-left:230px;z-index:99999;visibility:hidden;"></div>
												<div style="width:50px;height:50px;"><img src="images/checkmark2.png" id="DriverRearDoor" border="0" style="position:absolute;top:300px;margin-left:auto;margin-right:auto;padding-left:20px;z-index:99999;visibility:hidden;"></div>
												<div style="width:50px;height:50px;"><img src="images/checkmark2.png" id="Roof" border="0" style="position:absolute;top:260px;margin-left:auto;margin-right:auto;padding-left:125px;z-index:99999;visibility:hidden;"></div>
												<div style="width:50px;height:50px;"><img src="images/checkmark2.png" id="PassengerRearDoor" border="0" style="position:absolute;top:300px;margin-left:auto;margin-right:auto;padding-left:230px;z-index:99999;visibility:hidden;"></div>
												<div style="width:50px;height:50px;"><img src="images/checkmark2.png" id="DriverQuarterPanel" border="0" style="position:absolute;top:420px;margin-left:auto;margin-right:auto;padding-left:55px;z-index:99999;visibility:hidden;"></div>
												<div style="width:50px;height:50px;"><img src="images/checkmark2.png" id="Trunk" border="0" style="position:absolute;top:460px;margin-left:auto;margin-right:auto;padding-left:125px;z-index:99999;visibility:hidden;"></div>
												<div style="width:50px;height:50px;"><img src="images/checkmark2.png" id="PassengerQuarterPanel" border="0" style="position:absolute;top:420px;margin-left:auto;margin-right:auto;padding-left:195px;z-index:99999;visibility:hidden;"></div>
												<div style="width:50px;height:50px;"><img src="images/checkmark2.png" id="RearBumper" border="0" style="position:absolute;top:490px;margin-left:auto;margin-right:auto;padding-left:125px;z-index:99999;visibility:hidden;"></div>
												<?php
												break;
											case "suvvan":
												?>
												<img src="images/suv_grey.jpg" alt="" usemap="#map" style="position:absolute;top:50px;margin-left:auto;margin-right:auto;opacity: 1.0;" />
												<map name="map">
													<area shape="poly" coords="65, 54, 71, 23, 98, 9, 138, 2, 150, 2, 172, 6, 195, 14, 207, 21, 211, 31, 217, 52, 189, 39, 138, 32, 92, 39" onclick="uploadphoto(1,'suvvan','Front Bumper')" alt="Front Bumper" />
													<area shape="poly" coords="61, 134, 76, 134, 76, 129, 85, 125, 91, 39, 64, 52, 59, 69" onclick="uploadphoto(1,'suvvan','Drivers Front Panel')" alt="Front Panel" />
													<area shape="poly" coords="194, 115, 188, 40, 136, 32, 91, 38, 87, 116, 140, 111" onclick="uploadphoto(1,'suvvan','Hood')" alt="Hood" />
													<area shape="poly" coords="205, 134, 219, 134, 223, 126, 223, 70, 217, 54, 189, 40, 194, 126" onclick="uploadphoto(1,'suvvan','Passengers Front Fender')" alt="Front Fender" />
													<area shape="poly" coords="9, 217, 45, 190, 76, 154, 65, 136, 53, 144, 41, 139, 32, 144, 40, 153, 27, 166, 2, 184" onclick="uploadphoto(1,'suvvan','Drivers Front Door')" alt="Driver Front Door" />
													<area shape="poly" coords="205, 153, 225, 181, 270, 219, 279, 185, 257, 171, 239, 153, 248, 146, 238, 140, 229, 144, 218, 137" onclick="uploadphoto(1,'suvvan','Passengers Front Door')" alt="Passenger Front Door" />
													<area shape="poly" coords="8, 266, 27, 288, 74, 252, 70, 224, 38, 247" onclick="uploadphoto(1,'suvvan','Drivers Rear Door')" alt="Driver Rear Door" />
													<area shape="poly" coords="84, 168, 95, 181, 95, 365, 183, 365, 185, 181, 196, 169, 142, 158" onclick="uploadphoto(1,'suvvan','Roof')" alt="Roof" />
													<area shape="poly" coords="204, 250, 211, 223, 241, 248, 248, 249, 270, 265, 253, 289" onclick="uploadphoto(1,'suvvan','Passengers Rear Door')" alt="Passenger Rear Door" />
													<area shape="poly" coords="59, 299, 69, 300, 84, 280, 94, 273, 93, 365, 67, 408, 58, 400" onclick="uploadphoto(1,'suvvan','Drivers Quarter Panel')" alt="Driver Quarter Panel" />
													<area shape="poly" coords="67, 410, 70, 414, 211, 414, 211, 404, 191, 368, 184, 366, 92, 365" onclick="uploadphoto(1,'suvvan','Trunk')" alt="Trunk" />
													<area shape="poly" coords="184, 270, 196, 280, 199, 288, 210, 299, 221, 298, 222, 396, 217, 409, 211, 403, 191, 367, 184, 367" onclick="uploadphoto(1,'suvvan','Passengers Quarter Panel')" alt="Passenger Quarter Panel" />
													<area shape="poly" coords="67, 414, 64, 427, 70, 439, 127, 443, 170, 443, 212, 439, 217, 412, 210, 415, 203, 421, 145, 423, 80, 422" onclick="uploadphoto(1,'suvvan','Rear Bumper')" alt="Rear Bumper" />
												</map>

												<div style="width:50px;height:50px;"><img src="images/checkmark2.png" id="FrontBumper" border="0" style="position:absolute;top:55px;margin-left:auto;margin-right:auto;padding-left:125px;z-index:99999;visibility:hidden;"></div>
												<div style="width:50px;height:50px;"><img src="images/checkmark2.png" id="DriverFrontFender" border="0" style="position:absolute;top:120px;margin-left:auto;margin-right:auto;padding-left:55px;z-index:99999;visibility:hidden;"></div>
												<div style="width:50px;height:50px;"><img src="images/checkmark2.png" id="Hood" border="0" style="position:absolute;top:120px;margin-left:auto;margin-right:auto;padding-left:125px;z-index:99999;visibility:hidden;"></div>
												<div style="width:50px;height:50px;"><img src="images/checkmark2.png" id="PassengerFrontFender" border="0" style="position:absolute;top:120px;margin-left:auto;margin-right:auto;padding-left:195px;z-index:99999;visibility:hidden;"></div>
												<div style="width:50px;height:50px;"><img src="images/checkmark2.png" id="DriverFrontDoor" border="0" style="position:absolute;top:220px;margin-left:auto;margin-right:auto;padding-left:20px;z-index:99999;visibility:hidden;"></div>
												<div style="width:50px;height:50px;"><img src="images/checkmark2.png" id="PassengerFrontDoor" border="0" style="position:absolute;top:220px;margin-left:auto;margin-right:auto;padding-left:230px;z-index:99999;visibility:hidden;"></div>
												<div style="width:50px;height:50px;"><img src="images/checkmark2.png" id="DriverRearDoor" border="0" style="position:absolute;top:310px;margin-left:auto;margin-right:auto;padding-left:20px;z-index:99999;visibility:hidden;"></div>
												<div style="width:50px;height:50px;"><img src="images/checkmark2.png" id="Roof" border="0" style="position:absolute;top:310px;margin-left:auto;margin-right:auto;padding-left:125px;z-index:99999;visibility:hidden;"></div>
												<div style="width:50px;height:50px;"><img src="images/checkmark2.png" id="PassengerRearDoor" border="0" style="position:absolute;top:310px;margin-left:auto;margin-right:auto;padding-left:230px;z-index:99999;visibility:hidden;"></div>
												<div style="width:50px;height:50px;"><img src="images/checkmark2.png" id="DriverQuarterPanel" border="0" style="position:absolute;top:400px;margin-left:auto;margin-right:auto;padding-left:55px;z-index:99999;visibility:hidden;"></div>
												<div style="width:50px;height:50px;"><img src="images/checkmark2.png" id="Trunk" border="0" style="position:absolute;top:440px;margin-left:auto;margin-right:auto;padding-left:125px;z-index:99999;visibility:hidden;"></div>
												<div style="width:50px;height:50px;"><img src="images/checkmark2.png" id="PassengerQuarterPanel" border="0" style="position:absolute;top:400px;margin-left:auto;margin-right:auto;padding-left:195px;z-index:99999;visibility:hidden;"></div>
												<div style="width:50px;height:50px;"><img src="images/checkmark2.png" id="RearBumper" border="0" style="position:absolute;top:480px;margin-left:auto;margin-right:auto;padding-left:125px;z-index:99999;visibility:hidden;"></div>
												<?php
												break;
										}
										?>
										<a href="guipersonaluploaddamage.php?page=1&VehicleType=<?php echo $_SESSION['VehicleType']; ?>&damage=Miscellaneous" style="text-decoration: none"></a>
										<input type="hidden" id="inputfrontbumper" value="off">
										<input type="hidden" id="inputdriversfrontpanel" value="off">
										<input type="hidden" id="inputhood" value="off">
										<input type="hidden" id="inputpassengersfrontfender" value="off">
										<input type="hidden" id="inputdriversfrontdoor" value="off">
										<input type="hidden" id="inputpassengersfrontdoor" value="off">
										<input type="hidden" id="inputdriversreardoor" value="off">
										<input type="hidden" id="inputroof" value="off">
										<input type="hidden" id="inputpassengersreardoor" value="off">
										<input type="hidden" id="inputdriversquarterpanel" value="off">
										<input type="hidden" id="inputtrunk" value="off">
										<input type="hidden" id="inputpassengersquarterpanel" value="off">
										<input type="hidden" id="inputrearbumper" value="off">
									</form>
								</div>
							</div>
						</div>
					</div>
				</article>
			</div>
			<div id="pagefooter" style="vertical-align:top;padding-bottom:0px;" >
				<h4 class="title" style="margin-top:0px;padding-top:0px;">
					<div id="centerfooter" style="margin-bottom: 0px;padding-bottom:0px;" >
						<!--
						<a id="miscellaneousphotos" class="" style="float:left;margin-right: 20px;" href="guimiscellaneousuploaddamage.php?page=1&damage=Miscellaneous">
							<img src="images/camerarapid.png" border="0" width="48" height="39">
						</a>
						<span style="padding-top: 10px; margin-left: -10px; float:left;">
						<?php
						if (strlen($result['AccidentMiscellaneousPic1']) > 0 AND strlen($result['AccidentMiscellaneousPic2']) > 0) { echo " = 2"; }
						if (strlen($result['AccidentMiscellaneousPic1']) == 0 AND strlen($result['AccidentMiscellaneousPic2']) > 0) { echo " = 1"; }
						if (strlen($result['AccidentMiscellaneousPic1']) > 0 AND strlen($result['AccidentMiscellaneousPic2']) == 0) { echo " = 1"; }
						?>
						</span>
						onmouseover="javascript:donebuttonclicked();" 
						-->
						<input disabled id="done" type="button" class="roundedgraysubmit" value="Done" style="width:185px;height:30px;margin=bottom:10px;" onclick="javascript:donebuttonclicked();">
						<?php echo 'To be deleted: ' . $_SESSION['RequestorID'];?>
						<div style="clear:both;"></div>
					</div>
				</h4>
			</div>

		<!-- Upload 1st Photo -->
			<div id="uploadphotowrapper" class="wrapper style1" style="display:none;">
				<article id="uploadphoto" >
					<div class="pagetitle" style="text-align:center;">
						<div class="12u 24u(mobile)" style="text-align:center;">
							<!--<h4 class="title" style="text-align:center;margin-top:-15px;">-->
								<div >
									<div class="row">
										<div class="6u 12u(mobile)" style="float:left;margin:-5px 0px 5px 0px;padding-bottom: 10px;" id="Step1">
											<h4 class="title">
												<img src="images/back.png" border="0" width="24" height="24" style="float:left;" onclick="returntopanelselection();" />
												<span style="height:50px;">Step 1</span>
											</h4>
										</div>
										<div class="6u 12u(mobile)" style="float:left;margin:-5px 0px 5px 0px;padding-bottom: 10px;" id="Step2">
											<h4 class="title"><span>Step 2</span></h4>
										</div>
									</div>
									<div class="row" style="width:100%; valign:top;text-align:center;">
										<p id="photoinstructions" style="width:100%;text-align:center;font-family:arial;font-size:18pt;"></p>
										<div style="width:100%;">
											<img id="imgstep1" class="damageimage" src="" style="max-width:100%;height:auto;widht:100%;margin-left:auto;margin-right:auto;" />
											<form action="" method="post" enctype="multipart/form-data" style="margin-top:20px;">
												<input type="hidden" name="damagearea1" id="damagearea1">
												<!--<input type="file" name="imgfile" id="imgfile" onchange="submit();">-->
												<div style="width:100%;text=align:center;height:10px;">
													<label for="imgfile">
														<img class="image centered" src="images/cameraicon.jpg" id="camerapic" name="camerapic" style="margin-right: auto;margin-left: auto;" />
													</label>
													<?php $content = 'One moment...'; ?>
													<input class="rounded" name="imgfile" id="imgfile" type="file"  accept="image/*;capture=camera" onblur="document.getElementById('camerapic').style.display='none';" onchange="document.getElementById('camerapic').style.display='none';document.getElementById('bodycontent').style.display='none';submit();" style="display:none;margin-top:10px;"/>
												</div>
											</form>
										</div>
									</div>
								</div>
							<!--</h4>-->
						</div>
					</div>
					<div style="height: 100%;width: 100%;overflow: auto;" >
						<div id="" style="background-color: #ffffff;text-align:center;">
							<!--<p id="photoinstruction" class="paragraphtext" style="width:100%;background-color: <?php echo $backgroundcolor;?>;margin:0px 0px;margin-left:auto;margin-right:auto;font-family:arial;font-size:18pt;">Example <?php echo $damagepage; ?></p>-->
							<div id="bodycontent" style="text-align:center;display:block;">
								<br />
								<br />
								<br />
								<!--<a href="guipersonaldamage.php" class="roundedsubmit" style="padding-top:10px;height:30px;">Damage List</a>-->
							</div>
							<?php if ($damagepage == 1) { ?>
								<div style="width:100%;height: 300px;text-align:center;">
									<br />
									<p style="margin-left:auto;margin-right:auto;">Experiencing camera problems? Click <a href="http:guilpersonaluploaddamage.php?<?php echo $_SERVER["QUERY_STRING"]; ?>">here</a>.</p>
									<div style="clear: both;"><br /></div>
								</div>
							<?php } ?>
						</div>
					</div>
					<div style="clear: both;"><br /></div>
				</article>
			</div>

		<!-- Upload 2nd Photo -->
			<div id="uploadphotowrapper2" class="wrapper style1" style="display:none;">
				<article id="uploadphoto2"  >
					<div class="pagetitle" style="text-align:center;">
						<div class="12u 24u(mobile)" style="text-align:center;">
							<div class="clearcolors" >
								<div class="row">
									<div class="6u 12u(mobile)" style="float:left;margin:-5px 0px 5px 0px;padding-bottom: 10px;" id="Step1_2">
										<h4 class="title"><span style="height:50px;">Step 1</span><img src="images/checkmark2.png" align="right" style="margin-right: 20px" ></h4>
									</div>
									<div class="6u 12u(mobile)" style="float:left;margin:-5px 0px 5px 0px;padding-bottom: 10px;" id="Step2_2">
										<h4 class="title"><span>Step 2</span></h4>
									</div>
								</div>
								<div class="row" style="width:100%; valign:top;text-align:center;">
									<p id="photoinstructions2" style="width:100%;text-align:center;font-family:arial;font-size:18pt;"></p>
									<div style="width:100%;" >
										<img id="imgstep2" class="damageimage" src="" style="max-width:100%;height:auto;widht:100%;margin-left:auto;margin-right:auto;" />
										<form action="" method="post" enctype="multipart/form-data" style="margin-top:20px;">
											<input type="hidden" name="damagearea2" id="damagearea2">
											<div style="width:100%;text=align:center;height:10px;">
												<label for="imgfile2">
													<img class="image centered" src="images/cameraicon.jpg" id="camerapic2" name="camerapic2" style="margin-right: auto;margin-left: auto;" />
												</label>
												<input class="rounded" name="imgfile2" id="imgfile2" type="file"  accept="image/*;capture=camera" onblur="document.getElementById('camerapic2').style.display='none';" onchange="document.getElementById('camerapic2').style.display='none';document.getElementById('bodycontent').style.display='none';submit();" style="display:none;margin-top:10px;"/>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div style="clear: both;"><br /></div>
				</article>
			</div>

		<!-- VIN -->
			<div id="uploadphotowrappervin" class="wrapper style1" style="display:none;">
				<article id="uploadphotovin"  >
					<div class="pagetitle" style="text-align:center;">
						<div class="12u 24u(mobile)" style="text-align:center;">
							<div class="clearcolors" >
								<div class="row" >
									<div class="12u 24u(mobile)" style="float:left;margin:-5px 0px 5px 0px;padding-bottom: 10px;" id="Step1_2">

										<h4 class="title">
											<div class="pagetitle" >
												<?php include('guicall.inc'); ?>
												VIN Number Photo
											</div>
										</h4>
									
										<!--<h4 class="title"><span style="height:50px;">VIN Number Photo</span><img src="images/checkmark2.png" align="right" style="margin-right: 20px" ></h4>-->
									</div>
								</div>
								<div class="row" style="width:100%; valign:top;text-align:center;">
									<p id="photoinstructions3" style="width:100%;text-align:center;font-family:arial;font-size:18pt;"></p>
									<div style="width:100%;" >
										<div class="wrappedup" style="width:80%;height:100%;margin:0 auto;z-index: 10;" >
											<div class="h_iframe">
											<!-- width="560" height="315" -->
												<iframe style="" src="https://www.youtube.com/embed/SM2Q7RXeqY4?autoplay=0" frameborder="0" allowfullscreen></iframe>
											</div>
										</div>
										<p style="margin-top:20px;">Take a photo of the VIN number this number provides vital information about the vehicle need to produce an estimate.</p>
										<form action="" method="post" enctype="multipart/form-data" style="margin-top:20px;">
											<div style="width:100%;text=align:center;height:10px;">
												<label for="imgfilevin">
													<img class="image centered" src="images/cameraicon.jpg" id="camerapicvin" name="camerapicvin" style="margin-right: auto;margin-left: auto;" />
												</label>
												<input class="rounded" name="imgfilevin" id="imgfilevin" type="file"  accept="image/*;capture=camera" onblur="document.getElementById('camerapicvin').style.display='none';" onchange="document.getElementById('camerapicvin').style.display='none';document.getElementById('bodycontent').style.display='none';" style="display:none;margin-top:10px;"/>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div style="clear: both;"><br /></div>
				</article>
			</div>

		<!-- PERSONAL CONTACT -->
			<div id="wrapperpersonalcontact" class="wrapper style1" style="display:none;">
				<article id="personalconact"  >
					<!--<div class="pagetitle" style="text-align:center;">
						<div class="12u 24u(mobile)" style="text-align:center;">-->
							<div class="clearcolors" >
								<div class="row" >
									<div class="12u 24u(mobile)" style="float:left;margin:-5px 0px 5px 0px;padding-bottom: 10px;" id="Step1_2">

										<h4 class="title">
											<div class="pagetitle" >
												<?php include('guicall.inc'); ?>
												Contact Information
											</div>
										</h4>
									
										<!--<h4 class="title"><span style="height:50px;">VIN Number Photo</span><img src="images/checkmark2.png" align="right" style="margin-right: 20px" ></h4>-->
									</div>
								</div>
								<div class="row" >
									<div class="12u 24u(mobile)" style="float:left;margin:-5px 0px 5px 0px;padding-bottom: 10px;">
										<div style="margin-top:50px;height:100%;width:100%;overflow:hidden;" >
											<div style="width: 100%;height:100%;overflow: auto;padding-right: 20px;" >
												<div style="text-align:center;">
													<img src="logos/<?php echo $_SESSION['TheLogoFile']; ?>">
												</div>
												<form action="" method="post"  style="width:300px;margin-left:auto;margin-right:auto;">
													<input type="hidden" name="RequestorID" id="RequestorID" value="<?php echo $_SESSION['RequestorID']; ?>" />
													<p class="paragraphtitle">First Name
														<br /><input class="rounded" style="width:20em;" type="text" name="firstname" id="firstname" size="15" maxlength="50" placeholder="first name" value='<?php echo $_COOKIE["FirstName"]; ?>' required >
														<br />Last Name
														<br /><input class="rounded" style="width:20em;" type="text" name="lastname" id="lastname" size="15" maxlength="50" placeholder="last name" value="<?php echo $_COOKIE['LastName']; ?>" required >
													</p>
													<p class="paragraphtitle">Your Phone
														<br /><input class="rounded" style="width:20em;" type="text" name="phone" id="phone" size="15" maxlength="50" value="<?php echo $_COOKIE['Phone']; ?>" required >
													</p>
													<p class="paragraphtitle">Your E-mail Address
														<br /><input class="rounded" style="width:20em;" type="email" name="emailaddress" id="emailaddress" size="15" maxlength="50" value="<?php echo $_COOKIE['Email']; ?>" required >
													</p>
													<hr style="width:20em;" />
													<p class="paragraphtitle">Notes
														<br /><textarea class="rounded" name="notes" id="notes" rows="5" style="width:20em;" ></textarea>
													</p>
													<div style="text-align:center;">
														<input type="button" class="roundedsubmit" value="NEXT" onclick="savecontactinfo();" >
													</div>
													<br /><br /><br />
												</form>
											</div>
										</div>
									</div>
								</div>
							</div>
						<!--</div>
					</div>-->
					<div style="clear: both;"><br /></div>
				</article>
			</div>

		<!-- Returning User Location -->
			<?php
			$sql = $pdo->prepare('SELECT * FROM bodyshop WHERE BodyShopID=' . $_SESSION['BodyShopID']);
			$sql->execute();
			$result = $sql->fetch(PDO::FETCH_ASSOC);
			$GPS = $result['GPS'];

			$sql = $pdo->prepare('SELECT * FROM bodyshoplocation WHERE BodyShopID=' . $_SESSION['BodyShopID']);
			$sql->execute();
			$num_rows = $sql->rowCount();
			$result = $sql->fetch(PDO::FETCH_ASSOC);

			?>
			<div id="wrapperreturnlocation" class="wrapper style1" style="display:none;">
				<article id="returnlocation"  >
					<!--<div class="pagetitle" style="text-align:center;">
						<div class="12u 24u(mobile)" style="text-align:center;">-->
							<div class="clearcolors" >
								<div class="row" >
									<div class="12u 24u(mobile)" style="float:left;margin:-5px 0px 5px 0px;padding-bottom: 10px;" id="Step1_2">

										<h4 class="title">
											<div class="pagetitle" >
												<?php include('guicall.inc'); ?>
												Pick Location
											</div>
										</h4>
									
										<!--<h4 class="title"><span style="height:50px;">VIN Number Photo</span><img src="images/checkmark2.png" align="right" style="margin-right: 20px" ></h4>-->
									</div>
								</div>
								<div class="row" >
									<div class="12u 24u(mobile)" style="float:left;margin:-5px 0px 5px 0px;padding-bottom: 10px;">
										<div style="margin-top:50px;height:100%;width:100%;overflow:hidden;" >
											<div style="width: 100%;height:100%;overflow: auto;padding-right: 20px;" >
												<div style="text-align:center;">
													<img src="logos/<?php echo $_SESSION['TheLogoFile']; ?>">
												</div>
												<form action="" method="post"  style="width:300px;margin-left:auto;margin-right:auto;">
													<input type="hidden" name="RequestorID" id="RequestorID" value="<?php echo $_SESSION['RequestorID']; ?>" />
													<p class="paragraphtitle">Choose a Location
														<select class="rounded" name="bodyshoplocation" id="bodyshoplocation" style="width:15em;" required >
															<!--<option value=""> ~ Choose ~ </option>-->
															<?php
															$stmt = $pdo->prepare("SELECT LocationID, LocationName, Province FROM bodyshoplocation WHERE BodyShopID = :BodyShopID AND DELETED <> 'Y' ORDER BY Province, LocationName");
															$stmt->execute(array('BodyShopID' => $_SESSION['BodyShopID']));
															while ($result = $stmt->fetch(PDO::FETCH_ASSOC))
																{
																if ($result['LocationID'] == $_COOKIE['Location']) {
																	echo '<option SELECTED value="' . $result['LocationID'] . '">' . $result['Province'] . " - " . $result['LocationName'] . '</option>';
																	}
																else {
																	echo '<option value="' . $result['LocationID'] . '">' . $result['Province'] . " - " . $result['LocationName'] . '</option>';
																	}
																}
															?>
														</select>
														<?php
														if ($_COOKIE['Location'] and ($GPS == 'Y' or $num_rows >= 5)) {
															?>
															<div style="text-align:center;">
																<br />
																<!--<a href="http:guigpscurrent.php?BodyShopID=<?php echo $_SESSION['BodyShopID']; ?>" class="roundedsubmit" style="padding-top:10px;height:30px;">Select By GPS</a>-->
																<input type="button" class="roundedsubmit" value="Select By GPS" style="padding-top:5px;padding-bottom:5px;height:40px;width:170px;"  onclick="gpslookup();">
															</div>
															<?
															}
														?>
													</p>
													<div style="text-align:center;">
														<input type="button" class="roundedsubmit" value="SUBMIT NOW" style="padding-top:5px;padding-bottom:5px;height:40px;width:170px;" onclick="savedropdownlocation();" >
													</div>
												</form>
											</div>
										</div>
									</div>
								</div>
							</div>
						<!--</div>
					</div>-->
					<div style="clear: both;"><br /></div>
				</article>
			</div>

		<!-- Dropdown Submit -->
			<div id="wrapperdropdownsubmit" class="wrapper style1" style="display:none;">
				<article id="dropdownsubmit"  >
					<!--<div class="pagetitle" style="text-align:center;">
						<div class="12u 24u(mobile)" style="text-align:center;">-->
							<div class="clearcolors" >
								<div class="row" >
									<div class="12u 24u(mobile)" style="float:left;margin:-5px 0px 5px 0px;padding-bottom: 10px;" id="Step1_2">

										<h4 class="title">
											<div class="pagetitle" >
											</div>
										</h4>
									
										<!--<h4 class="title"><span style="height:50px;">VIN Number Photo</span><img src="images/checkmark2.png" align="right" style="margin-right: 20px" ></h4>-->
									</div>
								</div>
								<div class="row" >
									<div class="12u 24u(mobile)" style="float:left;margin:-5px 0px 5px 0px;padding-bottom: 10px;">
										<div style="margin-top:50px;height:100%;width:100%;overflow:hidden;" >
											<div style="width: 100%;height:100%;overflow: auto;padding-right: 20px;" >
												<div style="text-align:center;">
													<img src="logos/<?php echo $_SESSION['TheLogoFile']; ?>">
												</div>
												<div style="text-align:center;">
													<p class="paragraphitalic">Success, your request for an estimate has been received. 
													<br /><br />Thank you, we will be in touch soon.</p>
												</div>
											</div>
											<div style="text-align:center;">
												<?php
												$folderpath = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
												$path = "";
												if (strpos($folderpath, 'test') > 0) { $path = 'test/'; }
												if (strpos($folderpath, 'development') > 0) { $path = 'development/'; }
												?>
												<a href="http://www.bodyshop.systems/<?php echo $path; ?>guiquoteoptions.php?BodyShopID=<?php echo $_SESSION['BodyShopID']; ?>" class="roundedsubmit" style="padding-top:10px;height:40px;">Another Vehicle</a>
											</div>
										</div>
									</div>
								</div>
							</div>
						<!--</div>
					</div>-->
					<div style="clear: both;"><br /></div>
				</article>
			</div>

		<!-- GPS -->
			<div id="wrappergps" class="wrapper style1" style="display:none;">
				<article id="gps"  >
					<!--<div class="pagetitle" style="text-align:center;">
						<div class="12u 24u(mobile)" style="text-align:center;">-->
							<div class="clearcolors" >
								<div class="row" >
									<div class="12u 24u(mobile)" style="float:left;margin:-5px 0px 5px 0px;padding-bottom: 10px;" id="Step1_2">

										<h4 class="title">
											<div class="pagetitle" >
											</div>
										</h4>
									
										<!--<h4 class="title"><span style="height:50px;">VIN Number Photo</span><img src="images/checkmark2.png" align="right" style="margin-right: 20px" ></h4>-->
									</div>
								</div>
								<div class="row" >
									<div class="12u 24u(mobile)" style="float:left;margin:-5px 0px 5px 0px;padding-bottom: 10px;">
										<div style="margin-top:50px;height:100%;width:100%;overflow:hidden;" >
											<div style="width: 100%;height:100%;overflow: auto;padding-right: 20px;" >
												<div style="text-align:center;">
													<div id="map-canvas" style="width: 800px; height: 500px;margin-left:auto;margin-right:auto;">
													</div>
													This is where the map is located.
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						<!--</div>
					</div>-->
					<div style="clear: both;"><br /></div>
				</article>
			</div>

		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/jquery.scrolly.min.js"></script>
			<script src="assets/js/skel.min.js"></script>
			<script src="assets/js/skel-viewport.min.js"></script>
			<script src="assets/js/util.js"></script>
			<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
			<script src="assets/js/main.js"></script>
			<script>
				function uploadphoto(page,vehicletype,damagearea) {
					var page = page;
					var damagearea = damagearea;
					//alert ('p:' + page + ' d:' + damagearea);
					document.getElementById('uploadphotowrapper').style.display = "block";
					document.getElementById('uploadphotowrapper').style.visibility = "visible";
					document.getElementById('damagepanelwrapper').style.display = "none";
					document.getElementById('done').style.visibility = "hidden";
					if (page==1) {
						document.getElementById('damagearea1').value = damagearea;
						document.getElementById('Step1').style.backgroundColor = '#51A7FA';
						document.getElementById('Step2').style.backgroundColor = '#BEBEBE';
						document.getElementById('photoinstructions').innerHTML = 'Take the first photo from straight on';
						switch (damagearea) {
							case 'Drivers Front Panel':
								document.getElementById('imgstep1').src = 'images/' + vehicletype + '_driver_front_fender1.jpg';
								break;
							case 'Drivers Front Door':
								document.getElementById('imgstep1').src = 'images/' + vehicletype + '_driver_front_door1.jpg';
								break;
							case 'Drivers Rear Door':
								document.getElementById('imgstep1').src = 'images/' + vehicletype + '_driver_rear_door1.jpg';
								break;
							case 'Drivers Quarter Panel':
								document.getElementById('imgstep1').src = 'images/' + vehicletype + '_driver_quarter1.jpg';
								break;
							case 'Passengers Front Fender':
								document.getElementById('imgstep1').src = 'images/' + vehicletype + '_passenger_front_fender1.jpg';
								break;
							case 'Passengers Front Door':
								document.getElementById('imgstep1').src = 'images/' + vehicletype + '_passanger_front_door1.jpg';
								break;
							case 'Passengers Rear Door':
								document.getElementById('imgstep1').src = 'images/' + vehicletype + '_passanger_rear_door1.jpg';
								break;
							case 'Passengers Quarter Panel':
								document.getElementById('imgstep1').src = 'images/' + vehicletype + '_passanger_quarter1.jpg';
								break;
							case 'Front Bumper':
								document.getElementById('imgstep1').src = 'images/' + vehicletype + '_front_bumper1.jpg';
								break;
							case 'Hood':
								document.getElementById('imgstep1').src = 'images/' + vehicletype + '_hood1.jpg';
								break;
							case 'Roof':
								document.getElementById('imgstep1').src = 'images/' + vehicletype + '_roof1.jpg';
								break;
							case 'Trunk':
								document.getElementById('imgstep1').src = 'images/' + vehicletype + '_trunk1.jpg';
								break;
							case 'Rear Bumper':
								document.getElementById('imgstep1').src = 'images/' + vehicletype + '_rear_bumper1.jpg';
								break;
							case 'Miscellaneous':
								document.getElementById('imgstep1').src = 'images/' + vehicletype + '_miscellaneous1.jpg';
								break;
							}
						}
					else  {
						document.getElementById('damagearea2').value = damagearea;
						document.getElementById('Step2').style.backgroundColor = '#51A7FA';
						document.getElementById('photoinstructions2').innerHTML = 'Now take a photo from a side angle';
						switch (damagearea) {
							case 'Drivers Front Panel':
								document.getElementById('imgstep2').src = 'images/' + vehicletype + '_driver_front_fender2.jpg';
								break;
							case 'Drivers Front Door':
								document.getElementById('imgstep2').src = 'images/' + vehicletype + '_driver_front_door2.jpg';
								break;
							case 'Drivers Rear Door':
								document.getElementById('imgstep2').src = 'images/' + vehicletype + '_driver_rear_door2.jpg';
								break;
							case 'Drivers Quarter Panel':
								document.getElementById('imgstep2').src = 'images/' + vehicletype + '_driver_quarter2.jpg';
								break;
							case 'Passengers Front Fender':
								document.getElementById('imgstep2').src = 'images/' + vehicletype + '_passenger_front_fender2.jpg';
								break;
							case 'Passengers Front Door':
								document.getElementById('imgstep2').src = 'images/' + vehicletype + '_passanger_front_door2.jpg';
								break;
							case 'Passengers Rear Door':
								document.getElementById('imgstep2').src = 'images/' + vehicletype + '_passanger_rear_door2.jpg';
								break;
							case 'Passengers Quarter Panel':
								document.getElementById('imgstep2').src = 'images/' + vehicletype + '_passanger_quarter2.jpg';
								break;
							case 'Front Bumper':
								document.getElementById('imgstep2').src = 'images/' + vehicletype + '_front_bumper2.jpg';
								break;
							case 'Hood':
								document.getElementById('imgstep2').src = 'images/' + vehicletype + '_hood2.jpg';
								break;
							case 'Roof':
								document.getElementById('imgstep2').src = 'images/' + vehicletype + '_roof2.jpg';
								break;
							case 'Trunk':
								document.getElementById('imgstep2').src = 'images/' + vehicletype + '_trunk2.jpg';
								break;
							case 'Rear Bumper':
								document.getElementById('imgstep2').src = 'images/' + vehicletype + '_rear_bumper2.jpg';
								break;
							case 'Miscellaneous':
								document.getElementById('imgstep2').src = 'images/' + vehicletype + '_miscellaneous2.jpg';
								break;
							}
						}
					//location.href = '#uploadphoto2';
				}
			</script>

			<script>
				if (window.File && window.FileReader && window.FileList && window.Blob) {
					document.getElementById('imgfile').onchange = function(){
						var files = document.getElementById('imgfile').files;
						var damagearea = document.getElementById('damagearea1').value;
						for(var i = 0; i < files.length; i++) {
							resizeAndUpload(files[i],damagearea,1);
						}
						document.getElementById('uploadphotowrapper2').style.display = "block";
						document.getElementById('uploadphotowrapper2').style.visibility = "visible";
						document.getElementById('uploadphotowrapper').style.display = "none";
						document.getElementById('uploadphotowrapper').style.visibility = "hidden";
						document.getElementById('done').style.visibility = "hidden";
						document.getElementById('Step1_2').style.backgroundColor = '#BEBEBE';
						document.getElementById('Step2_2').style.backgroundColor = '#51A7FA';
						document.getElementById('photoinstructions').innerHTML = 'Now take a photo from a side angle';
						uploadphoto(2,'<?php echo $_SESSION["VehicleType"]; ?>',damagearea);
						//document.getElementById('imageuploadform').submit();
					};

					document.getElementById('imgfile2').onchange = function(){
						var files = document.getElementById('imgfile2').files;
						var damagearea = document.getElementById('damagearea2').value;
						for(var i = 0; i < files.length; i++) {
							resizeAndUpload(files[i],damagearea,2);
						}
						document.getElementById('uploadphotowrapper2').style.display = "none";
						document.getElementById('uploadphotowrapper').style.display = "none";
						document.getElementById('damagepanelwrapper').style.display = "block";
						document.getElementById('damagepanelwrapper').style.visibility = "visible";
						checkmarksonoff();
						document.getElementById('done').style.visibility = "visible";
						document.getElementById('uploadphotowrapper2').style.visibility = "hidden";
						document.getElementById('uploadphotowrapper').style.visibility = "hidden";
					};

					document.getElementById('imgfilevin').onchange = function(){
						var files = document.getElementById('imgfilevin').files;
						for(var i = 0; i < files.length; i++) {
							resizeAndUpload(files[i],'VIN Number',0);
						}
						document.getElementById('uploadphotowrapper2').style.display = "none";
						document.getElementById('uploadphotowrapper').style.display = "none";
						document.getElementById('damagepanelwrapper').style.display = "none";
						document.getElementById('damagepanelwrapper').style.visibility = "hidden";
						document.getElementById('done').style.visibility = "hidden";
						document.getElementById('uploadphotowrapper2').style.visibility = "hidden";
						document.getElementById('uploadphotowrapper').style.visibility = "hidden";
						document.getElementById('uploadphotowrappervin').style.display = "none";
						document.getElementById('uploadphotowrappervin').style.visibility = "hidden";
						document.getElementById('wrapperpersonalcontact').style.display = "block";
						document.getElementById('wrapperpersonalcontact').style.visibility = "visible";
					};
				} else {
					alert('The File APIs are not fully supported in this browser.');
				}
				 
				function resizeAndUpload(file,thedamagearea,thepage) {
				var reader = new FileReader();
					reader.onloadend = function() {
				 
					var tempImg = new Image();
					tempImg.src = reader.result;
					tempImg.onload = function() {
				 
						var MAX_WIDTH = 800;
						var MAX_HEIGHT = 800;
						var tempW = tempImg.width;
						var tempH = tempImg.height;
						if (tempW > tempH) {
							if (tempW > MAX_WIDTH) {
							   tempH *= MAX_WIDTH / tempW;
							   tempW = MAX_WIDTH;
							}
						} else {
							if (tempH > MAX_HEIGHT) {
							   tempW *= MAX_HEIGHT / tempH;
							   tempH = MAX_HEIGHT;
							}
						}
				 
						var canvas = document.createElement('canvas');
						canvas.width = tempW;
						canvas.height = tempH;
						var ctx = canvas.getContext("2d");
						ctx.drawImage(this, 0, 0, tempW, tempH);
						var dataURL = canvas.toDataURL("image/jpeg");
				 
						var xhr = new XMLHttpRequest();
						xhr.onreadystatechange = function(ev){
							//document.getElementById('filesInfo').innerHTML = 'Done!';
							<?php
							if ($_GET['page'] == "1") 
								{
								?>
								//document.getElementById('filesInfo').innerHTML = '<a href="guipersonaluploaddamage.php?page=2&damage=<?php echo $_GET['damage']; ?>">2nd Image</a>';
								<?php
								}
							else
								{
								?>
								//echo 'window.location = "guipersonaldamage.php";';
								//document.getElementById('filesInfo').innerHTML = '<a href="guipersonaldamage.php">Done</a>';
								<?php
								}
							?>
							<?php
							if ($_GET['page'] == "1") 
								{
								//echo 'window.location = "guipersonaluploaddamage.php?page=2&damage=' . $_GET['damage'] . '";';
								}
							else
								{
								//echo 'window.location = "guipersonaldamage.php";';
								}
							?>
						};
						xhr.open('POST', 'uploadResized.php', true);
						xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
						var data = 'image=' + dataURL + '&damagepage=' + thepage + '&damagearea=' + thedamagearea + '&RequestorID=' + <?php echo $_SESSION['RequestorID']; ?>;
						//alert('input' + thedamagearea.toLowerCase().replace(/\s/g,''));
						if (thedamagearea != 'VIN Number') {
							document.getElementById('input' + thedamagearea.toLowerCase().replace(/\s/g,'')).value = 'on';
							}
						xhr.send(data);
					  }
				 
				   }
				   reader.readAsDataURL(file);
				}

			</script>
			<script>
				//checkmark controls
				function checkmarksonoff() {
					donebutton = 'off';
					if (document.getElementById('inputfrontbumper').value == 'on') { 	
						document.getElementById('FrontBumper').style.visibility = 'visible'; 
						donebutton = 'on';
						}
					if (document.getElementById('inputdriversfrontpanel').value == 'on') { 	
						document.getElementById('DriverFrontFender').style.visibility = 'visible'; 
						donebutton = 'on';
						}
					if (document.getElementById('inputhood').value == 'on') { 	
						document.getElementById('Hood').style.visibility = 'visible'; 
						donebutton = 'on';
						}
					if (document.getElementById('inputpassengersfrontfender').value == 'on') { 	
						document.getElementById('PassengerFrontFender').style.visibility = 'visible'; 
						donebutton = 'on';
						}
					if (document.getElementById('inputdriversfrontdoor').value == 'on') { 	
						document.getElementById('DriverFrontDoor').style.visibility = 'visible'; 
						donebutton = 'on';
						}
					if (document.getElementById('inputpassengersfrontdoor').value == 'on') { 	
						document.getElementById('PassengerFrontDoor').style.visibility = 'visible'; 
						donebutton = 'on';
						}
					if (document.getElementById('inputdriversreardoor').value == 'on') { 	
						document.getElementById('DriverRearDoor').style.visibility = 'visible'; 
						donebutton = 'on';
						}
					if (document.getElementById('inputroof').value == 'on') { 	
						document.getElementById('Roof').style.visibility = 'visible'; 
						donebutton = 'on';
						}
					if (document.getElementById('inputpassengersreardoor').value == 'on') { 	
						document.getElementById('PassengerRearDoor').style.visibility = 'visible'; 
						donebutton = 'on';
						}
					if (document.getElementById('inputdriversquarterpanel').value == 'on') { 	
						document.getElementById('DriverQuarterPanel').style.visibility = 'visible'; 
						donebutton = 'on';
						}
					if (document.getElementById('inputtrunk').value == 'on') { 	
						document.getElementById('Trunk').style.visibility = 'visible'; 
						donebutton = 'on';
						}
					if (document.getElementById('inputpassengersquarterpanel').value == 'on') { 	
						document.getElementById('PassengerQuarterPanel').style.visibility = 'visible'; 
						donebutton = 'on';
						}
					if (document.getElementById('inputrearbumper').value == 'on') { 	
						document.getElementById('RearBumper').style.visibility = 'visible'; 
						donebutton = 'on';
						}
					if (donebutton == 'on') {
						document.getElementById('done').disabled = false;
						document.getElementById('done').className = 'roundedgreensubmit';
					}
				}
			</script>
			<script>
				//Alert window if DONE is disabled, otherwise move to next section
				function donebuttonclicked() {
					if (document.getElementById('done').disabled == true) {
						alert('No photos have been captured yet.');
						}
					else {
						document.getElementById('uploadphotowrapper2').style.display = "none";
						document.getElementById('uploadphotowrapper').style.display = "none";
						document.getElementById('damagepanelwrapper').style.display = "none";
						document.getElementById('damagepanelwrapper').style.visibility = "hidden";
						document.getElementById('done').style.visibility = "hidden";
						document.getElementById('uploadphotowrapper2').style.visibility = "hidden";
						document.getElementById('uploadphotowrapper').style.visibility = "hidden";
						document.getElementById('uploadphotowrappervin').style.display = "block";
						document.getElementById('uploadphotowrappervin').style.visibility = "visible";
						}
				}
			</script>
			<script>
				function returntopanelselection() {
					document.getElementById('uploadphotowrapper2').style.display = "none";
					document.getElementById('uploadphotowrapper').style.display = "none";
					document.getElementById('damagepanelwrapper').style.display = "block";
					document.getElementById('damagepanelwrapper').style.visibility = "visible";
					checkmarksonoff();
					document.getElementById('done').style.visibility = "visible";
					document.getElementById('uploadphotowrapper2').style.visibility = "hidden";
					document.getElementById('uploadphotowrapper').style.visibility = "hidden";
				}
			</script>
			<script>
				function savecontactinfo() {
					document.getElementById('wrapperpersonalcontact').style.display = "none";
					document.getElementById('wrapperpersonalcontact').style.visibility = "hidden";
					document.getElementById('wrapperreturnlocation').style.display = "block";
					document.getElementById('wrapperreturnlocation').style.visibility = "visible";
					var firstname = document.getElementById("firstname").value;
					var lastname = document.getElementById("lastname").value;
					var phone = document.getElementById("phone").value;
					var emailaddress = document.getElementById("emailaddress").value;
					var notes = document.getElementById("notes").value;
					var requestorid = <?php echo $_SESSION['RequestorID']; ?>;
					var url = "guipersonalcontactsave.php";
					var data = "firstname=" + firstname + "&lastname=" + lastname + "&phone=" + phone + "&emailaddress=" + emailaddress + "&notes=" + notes + "&RequestorID=" + requestorid;
					//request.onreadystatechange = "";
					//request.open("POST", url, true);
					//request.send(null);
					var xmlhttp;
					if (window.XMLHttpRequest)
					  {// code for IE7+, Firefox, Chrome, Opera, Safari
					  xmlhttp=new XMLHttpRequest();
					  }
					else
					  {// code for IE6, IE5
					  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
					  }
					xmlhttp.onreadystatechange=function()
					  {
					  if (xmlhttp.readyState==4 && xmlhttp.status==200)
						{
						//document.getElementById("myDiv1").innerHTML=xmlhttp.responseText;
						}
					  }
					xmlhttp.open("POST",url,true);
					xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
					xmlhttp.send(data);
				}
			</script>
			<script>
				function savedropdownlocation() {
					document.getElementById('wrapperreturnlocation').style.display = "none";
					document.getElementById('wrapperreturnlocation').style.visibility = "hidden";
					document.getElementById('wrapperdropdownsubmit').style.display = "block";
					document.getElementById('wrapperdropdownsubmit').style.visibility = "visible";
					var bodyshoplocation = document.getElementById("bodyshoplocation").value;
					var requestorid = <?php echo $_SESSION['RequestorID']; ?>;
					var url = "guipersonalsend.php";
					var data = "bodyshoplocation=" + bodyshoplocation + "&RequestorID=" + requestorid;
					var xmlhttp;
					if (window.XMLHttpRequest)
					  {// code for IE7+, Firefox, Chrome, Opera, Safari
					  xmlhttp=new XMLHttpRequest();
					  }
					else
					  {// code for IE6, IE5
					  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
					  }
					xmlhttp.onreadystatechange=function()
					  {
					  if (xmlhttp.readyState==4 && xmlhttp.status==200)
						{
						//document.getElementById("myDiv2").innerHTML=xmlhttp.responseText;
						}
					  }
					xmlhttp.open("POST",url,true);
					xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
					xmlhttp.send(data);
				}
			</script>
			<script>
				function gpslookup() {
					document.getElementById('wrapperreturnlocation').style.display = "none";
					document.getElementById('wrapperreturnlocation').style.visibility = "hidden";
					document.getElementById('wrappergps').style.display = "block";
					document.getElementById('wrappergps').style.visibility = "visible";
				}
			</script>
			<script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyA7IZt-36CgqSGDFK8pChUdQXFyKIhpMBY&sensor=true" type="text/javascript"></script>
		    <script type="text/javascript">
				var latitude = 0;
				var longitude = 0;
				var map;
				var geocoder;
				var marker;
				var people = new Array();
				var latlng;
				var infowindow;
				
				$(document).ready(function() {
					ViewCustInGoogleMap();
				});

				function ViewCustInGoogleMap() {
					latitude = <?php echo $clientlatitude; ?>;
					longitude = <?php echo $clientlongitude; ?>;
					if (latitude == 0) {
						latitude = <?php echo $latitude; ?>;
						}
					
					if (longitude == 0) {
						longitude = <?php echo $longitude; ?>;
						}
						
					var mapOptions = {
						center: new google.maps.LatLng(latitude, longitude),   // Coimbatore = (11.0168445, 76.9558321)
						zoom: 7,
						mapTypeId: google.maps.MapTypeId.ROADMAP
					};
					map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);

					<?php

					$sqlm = "SELECT * FROM bodyshoplocation WHERE Deleted <> 'Y' AND BodyShopID=" . $_SESSION['BodyShopID'];
					$sqlm = $pdo->prepare($sqlm);
					$sqlm->execute();
					
					$data = '[{ "DisplayText": "<h3>Your Location</h3>", "ADDRESS": "", "LatitudeLongitude": "' . $clientlatitude . ',' . $clientlongitude . '", "MarkerId": "Customer" },';

					while ($resultm = $sqlm->fetch(PDO::FETCH_ASSOC)){
						if ($resultm['Longitude'] == 0.00000000) {
							//Look up longitude and latitude
							$address = $resultm['LocationAddress'] . ',' . $resultm['Province']; // Google HQ
							$prepAddr = str_replace(' ','+',$address);
							$geocode=file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&sensor=false');
							$output= json_decode($geocode);
							$latitude = $output->results[0]->geometry->location->lat;
							$longitude = $output->results[0]->geometry->location->lng;
							
							//update database table
							$sql = 'UPDATE bodyshoplocation SET Longitude = :Longitude, Latitude = :Latitude WHERE LocationID=:LocationID';
							$sql = $pdo->prepare($sql);
							$sql->execute(array('Longitude' => $longitude, 'Latitude' => $latitude, 'LocationID' => $resultm['LocationID']));
							}
						else {
							$latitude = $resultm['Latitude'];
							$longitude = $resultm['Longitude'];
							}
						//$selectlocation = '<a href="guilocationselected.php?LocationID=' . $resultm['LocationID'] . '">Select This Location</a>';
						$data .= '{ "DisplayText": "' . '<b>Name: </b>' . $resultm['LocationName'] . '<br /><b>Address: </b>' . $resultm['LocationAddress'] . '<br /><b>Phone: </b>' . $resultm['Phone'] . '<br /><b>E-mail: </b>' . $resultm['EmailAddress'] . '<br /><a href=guipersonalsend.php?Type=gps&LocationID=' . $resultm['LocationID'] . '>SELECT THIS LOCATION</a>", "ADDRESS": "' . $resultm['LocationAddress'] . ',' . $resultm['Province'] . '", "LatitudeLongitude": "' . $latitude . ',' . $longitude . '", "MarkerId": "Bodyshop" },';
						//$data .= '{ "DisplayText": "' . '<b>Name: </b>' . $resultm['LocationName'] . '<br /><b>Address: </b>' . $resultm['LocationAddress'] . '<br /><b>Phone: </b>' . $resultm['Phone'] . '<br /><b>E-mail: </b>' . $resultm['EmailAddress'] . '", "ADDRESS": "' . $resultm['LocationAddress'] . ',' . $resultm['Province'] . '", "LatitudeLongitude": "' . $latitude . ',' . $longitude . '", "MarkerId": "Bodyshop" },';
						}
					$data = substr($data,0,strlen($data) - 1);
					$data .= ']';
					?>

					//var data = '[{ "DisplayText": "adcv", "ADDRESS": "Seattle, Washington", "LatitudeLongitude": "47.6097,-122.3331", "MarkerId": "Customer" },{ "DisplayText": "abcd", "ADDRESS": "Tacoma, Washington", "LatitudeLongitude": "47.2530556,-122.4430556", "MarkerId": "Customer"}]';
					var data = '<?php echo $data; ?>';

					people = JSON.parse(data); 

					for (var i = 0; i < people.length; i++) {
						setMarker(people[i]);
					}

				}

				function setMarker(people) {
					geocoder = new google.maps.Geocoder();
					infowindow = new google.maps.InfoWindow();
					if ((people["LatitudeLongitude"] == null) || (people["LatitudeLongitude"] == 'null') || (people["LatitudeLongitude"] == '')) {
						geocoder.geocode({ 'address': people["Address"] }, function(results, status) {
							if (status == google.maps.GeocoderStatus.OK) {
								latlng = new google.maps.LatLng(results[0].geometry.location.lat(), results[0].geometry.location.lng());
								marker = new google.maps.Marker({
									position: latlng,
									map: map,
									draggable: false,
									html: people["DisplayText"],
									icon: "images/marker/" + people["MarkerId"] + ".png"
								});
								//marker.setPosition(latlng);
								//map.setCenter(latlng);
								google.maps.event.addListener(marker, 'click', function(event) {
									infowindow.setContent(this.html);
									infowindow.setPosition(event.latLng);
									infowindow.open(map, this);
								});
							}
							else {
								alert(people["DisplayText"] + " -- " + people["Address"] + ". This address couldn't be found");
							}
						});
					}
					else {
						var latlngStr = people["LatitudeLongitude"].split(",");
						var lat = parseFloat(latlngStr[0]);
						var lng = parseFloat(latlngStr[1]);
						latlng = new google.maps.LatLng(lat, lng);
						if (people["MarkerId"] == 'Customer') {
							var image = 'images/blue-dot.png';
							marker = new google.maps.Marker({
								position: latlng,
								map: map,
								draggable: false,               // cant drag it
								html: people["DisplayText"],    // Content display on marker click
								icon: image       // Give ur own image
							});
							}
						else {
							var image = 'images/red-dot.png';
							marker = new google.maps.Marker({
								position: latlng,
								map: map,
								draggable: false,               // cant drag it
								html: people["DisplayText"],    // Content display on marker click
								icon: image       // Give ur own image
							});
							}
						//marker.setPosition(latlng);
						//map.setCenter(latlng);
						google.maps.event.addListener(marker, 'click', function(event) {
							//window.location.href = marker.url;
							infowindow.setContent(this.html);
							infowindow.setPosition(event.latLng);
							infowindow.open(map, this);
						});
					}
				}
			</script>
	</body>
</html>
