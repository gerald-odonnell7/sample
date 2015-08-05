<?php 
session_start(); 
$Page = "GUI Quote Options";
require_once('counter.inc');
$_SESSION['BodyShopID'] = $_GET['BodyShopID'];

if(!isset($_GET["BodyShopID"])) {
	header('Location: guimissingbodyshop.php');
	}
else
	{
	$_SESSION['TheLogoFile'] = $_SESSION['BodyShopID'] . "." . $result['BodyShopLogo'];

	if (strstr($_SERVER[REQUEST_URI],"development")) {
		$_SESSION['foldername'] = "http://www.bodyshop.systems/development/";
		}
	else if (strstr($_SERVER[REQUEST_URI],"test")) {
		$_SESSION['foldername'] = "http://www.bodyshop.systems/test/";
		}
	else {
		$_SESSION['foldername'] = "http://www.bodyshop.systems/";
		}

	//Verify it is a valid BODY SHOP
	$stmt = $pdo->prepare('SELECT * FROM bodyshop WHERE BodyShopID = :BodyShopID');
	$stmt->execute(array('BodyShopID' => $_SESSION['BodyShopID']));
	$num_rows = $stmt->rowCount();
	$result = $stmt->fetch(PDO::FETCH_ASSOC);
	if ($num_rows == 0) {
		header('Location: guiunmatchedbodyshop.php');
		}
	else
		{
		$_SESSION['BodyShopName'] = $result['BodyShopName'];
		$_SESSION['TheLogoFile'] = $_SESSION['BodyShopID'] . "." . $result['BodyShopLogo'];

		//Use this to fetch the new record so to use through out the application
		$initialkey = mt_rand(1,999999);

		//Initialize the record of the customer
		$insstmt = $pdo->prepare('INSERT INTO requestor (InitialKey, Deleted) VALUES (:InitialKey, :Deleted);');
		$insstmt->execute(array('InitialKey' => $initialkey, 'Deleted' => 'N'));
		$stmtc = $pdo->prepare('SELECT RequestorID FROM requestor WHERE InitialKey = :InitialKey');
		$stmtc->execute(array('InitialKey' => $initialkey));
		$resultc = $stmtc->fetch(PDO::FETCH_ASSOC);

		$_SESSION['RequestorID'] = $resultc['RequestorID'];

		}
	}

?>
<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en" translate="yes">
	<head>
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<link rel="shortcut icon" sizes="57x57" href="57x57/<?php echo $_SESSION['BodyShopID']; ?>.<?php echo $result['Icon57x57']; ?>" />
		<link rel="shortcut icon" sizes="72x72" href="72x72/<?php echo $_SESSION['BodyShopID']; ?>.<?php echo $result['Icon72x72']; ?>" />
		<link rel="shortcut icon" sizes="114x114" href="114x114/<?php echo $_SESSION['BodyShopID']; ?>.<?php echo $result['Icon114x114']; ?>" />
		<link rel="shortcut icon" sizes="144x144" href="144x144/<?php echo $_SESSION['BodyShopID']; ?>.<?php echo $result['Icon144x144']; ?>" />
		<link rel="apple-touch-icon" sizes="57x57" href="57x57/<?php echo $_SESSION['BodyShopID']; ?>.<?php echo $result['Icon57x57']; ?>" />
		<link rel="apple-touch-icon" sizes="72x72" href="72x72/<?php echo $_SESSION['BodyShopID']; ?>.<?php echo $result['Icon72x72']; ?>" />
		<link rel="apple-touch-icon" sizes="114x114" href="114x114/<?php echo $_SESSION['BodyShopID']; ?>.<?php echo $result['Icon114x114']; ?>" />
		<link rel="apple-touch-icon" sizes="144x144" href="144x144/<?php echo $_SESSION['BodyShopID']; ?>.<?php echo $result['Icon144x144']; ?>" />
		<link rel="apple-touch-icon-precomposed" sizes="57x57" href="57x57/<?php echo $_SESSION['BodyShopID']; ?>.<?php echo $result['Icon57x57']; ?>" />
		<link rel="apple-touch-icon-precomposed" sizes="72x72" href="72x72/<?php echo $_SESSION['BodyShopID']; ?>.<?php echo $result['Icon72x72']; ?>" />
		<link rel="apple-touch-icon-precomposed" sizes="114x114" href="114x114/<?php echo $_SESSION['BodyShopID']; ?>.<?php echo $result['Icon114x114']; ?>" />
		<link rel="apple-touch-icon-precomposed" sizes="144x144" href="144x144/<?php echo $_SESSION['BodyShopID']; ?>.<?php echo $result['Icon144x144']; ?>" />
		<link rel="stylesheet" type="text/css" href="css/addtohomescreen.css">
		<link rel="stylesheet" type="text/css" href="css/appsite.css">
        <link rel="stylesheet" href="css/site.css" type="text/css" media="screen" />
		<script src="js/addtohomescreen.js"></script>
		<script>
		addToHomescreen();
		</script>
		<script>
			window.scrollTo(0, 0);
			if(! /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
				<?php 
				if ($_GET['Source'] != 'Desktop') {
					echo 'location.href="guidesktop.php?BodyShopID=' . $_GET['BodyShopID'] . '";';
					}
				?>
			}
		</script>
		<script type="text/javascript">
			function vehicleselection(vehicle) {
				if (vehicle == "car")
					{
					document.getElementById("car").checked = true;
					document.getElementById("truck").checked = false;
					document.getElementById("suv").checked = false;
					}
				if (vehicle == "truck")
					{
					document.getElementById("car").checked = false;
					document.getElementById("truck").checked = true;
					document.getElementById("suv").checked = false;
					}
				if (vehicle == "suv")
					{
					document.getElementById("car").checked = false;
					document.getElementById("truck").checked = false;
					document.getElementById("suv").checked = true;
					}
				}
		</script>
	</head>
	<body class="defaulttext" onload="this.requestFullscreen();hideAddressBar();">
		<div id="pagetitle" style="height: 75px;position:fixed;text-align:center;z-index:20;">
			<h4 class="title" style="">
				<div id="centerheader">
					<select class="roundeddropdown" name="sitelanguage" id="sitelanguage" style="float: left;-webkit-appearance: none;background: url(images/downArrow.png) no-repeat right #000;width:100px;">
						<option value="en">English</option>
					</select>
					<?php include('guicall.inc'); ?>
					<a id="questionmark" class="" style="float:right;margin-right: 20px;" href="guihelpapp.php?Source=Desktop&BodyShopID=<?php echo $_GET['BodyShopID']; ?>"><img src="images/questionmark.png" border="0" width="32" height="32"></a>
				</div>
			</h4>
			<div style="clear:both;"></div>
		</div>
		<div id="logo" style="text-align:center;padding-top:50px;">
			<img src="logos/<?php echo $_SESSION['TheLogoFile']; ?>">
		</div>
		<div id="bodytext" style="height: 400px;overflow:hidden;z-index:10;" >
			<form action="guipersonalvehicleinfosave.php" method="post" >
				<div style="width:310px;margin-right:auto;margin-left:auto">
					<div id="ck-button1" class="rounded">
					   <label>
						  <input type="radio" value="c" id="car" name="vehicle" onclick="vehicleselection('car')" checked /><span>Car</span>
					   </label>
					</div>
					<div id="ck-button2" class="rounded">
					   <label>
						  <input type="radio" value="t" id="truck" name="vehicle" onclick="vehicleselection('truck')" /><span>Truck</span>
					   </label>
					</div>
					<div id="ck-button3" class="rounded">
					   <label>
						  <input type="radio" value="s" id="suv" name="vehicle" onclick="vehicleselection('suv')" /><span>Suv/Van</span>
					   </label>
					</div>
					<br clear="all" />
					<p class="paragraphtitle">
					<br /><input class="rounded" style="width:4em;" type="number" name="year" min="1900" id="year" size="4" maxlength="4" placeholder="Year">
					<br clear="all"/>
					<br /><input class="rounded" style="width:14em;" type="text" name="make" id="make" size="15" maxlength="50" placeholder="Make">
					<br clear="all"/>
					<br /><input class="rounded" style="width:14em;" type="text" name="model" id="model" size="15" maxlength="50" placeholder="Model">
					</p>
				</div>
				<div style="text-align:center;">
					<input type="submit" class="roundedsubmit" value="NEXT">
					<?php include('alertmessage.inc'); ?>
				</div>
			</form>
		</div>
		<div style="width:120%;margin-left: -30px; bottom: 0;position: fixed;background-color: #BEBEBE;text-align:center;">
			<!--
			<a id="miscellaneousphotos" class="" style="margin-left: -300px;" href="guimiscellaneousuploaddamage.php?page=1&damage=Miscellaneous">
				<img src="images/camerarapid.png" border="0" width="48" height="39">
			</a>
			<div style="clear:both;"></div>
			-->
		</div>
	</body>
</html>
