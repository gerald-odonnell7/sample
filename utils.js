window.scrollTo(0, 1);
		
function rotateimage() {
	if (document.getElementById('rotate').value == 0) {
		document.getElementById('mainimage').className = 'rotateimage90';
		document.getElementById('rotate').value = "90";
		}
	else if (document.getElementById('rotate').value == 90) {
		document.getElementById('mainimage').className = 'rotateimage180';
		document.getElementById('rotate').value = "180";
		}
	else if (document.getElementById('rotate').value == 180) {
		document.getElementById('mainimage').className = 'rotateimage270';
		document.getElementById('rotate').value = "270";
		}
	else {
		document.getElementById('mainimage').className = 'rotateimage0';
		document.getElementById('rotate').value = "0";
		}
	}
	
function enlargeimage() {
	thiswidth = document.getElementById('mainimage').width;
	thisheight = document.getElementById('mainimage').height;
	//alert(document.getElementById('enlarged').value);
	if (document.getElementById('imagewidth').value == 0) {
		document.getElementById('imagewidth').value = thiswidth;
		document.getElementById('imageheight').value = thisheight;
		}
	if (document.getElementById('enlarged').value == 100) {
		document.getElementById('mainimage').width = 2 * thiswidth;
		document.getElementById('mainimage').height = 2 * thisheight;
		document.getElementById('enlarged').value = "200";
		return;
		}
	else if (document.getElementById('enlarged').value == 200) {
		document.getElementById('mainimage').width = 2 * thiswidth;
		document.getElementById('mainimage').height = 2 * thisheight;
		document.getElementById('enlarged').value = "400";
		return;
		}
	else if (document.getElementById('enlarged').value == 50) {
		document.getElementById('mainimage').width = 2 * thiswidth;
		document.getElementById('mainimage').height = 2 * thisheight;
		document.getElementById('enlarged').value = "100";
		return;
		}
	else if (document.getElementById('enlarged').value == 25) {
		document.getElementById('mainimage').width = 2 * thiswidth;
		document.getElementById('mainimage').height = 2 * thisheight;
		document.getElementById('enlarged').value = "50";
		return;
		}
/*
	else if (document.getElementById('enlarged').value == 400) {
		document.getElementById('mainimage').width = .05 * thiswidth;
		document.getElementById('mainimage').height = .05 * thisheight;
		document.getElementById('enlarged').value = "20";
		return;
		}
	else if (document.getElementById('enlarged').value == 20) {
		document.getElementById('mainimage').width = 2 * thiswidth;
		document.getElementById('mainimage').height = 2 * thisheight;
		document.getElementById('enlarged').value = "40";
		return;
		}
	else if (document.getElementById('enlarged').value == 40) {
		document.getElementById('mainimage').width = 2 * thiswidth;
		document.getElementById('mainimage').height = 2 * thisheight;
		document.getElementById('enlarged').value = "80";
		return;
		}
	else if (document.getElementById('enlarged').value == 999) {
		document.getElementById('mainimage').width = .40 * thiswidth;
		document.getElementById('mainimage').height = .40 * thisheight;
		document.getElementById('enlarged').value = "40";
		return;
		}
*/
	else {
		if (document.getElementById('enlarged').value == 999) 
			{
			document.getElementById('mainimage').width = document.getElementById('imagewidth').value;
			document.getElementById('mainimage').height = document.getElementById('imageheight').value;
			document.getElementById('enlarged').value = "100";
			}
		return;
		}
	}

function shrinkimage() {
	thiswidth = document.getElementById('mainimage').width;
	thisheight = document.getElementById('mainimage').height;
	//alert(document.getElementById('enlarged').value);
	if (document.getElementById('enlarged').value == 400) {
		document.getElementById('imagewidth').value = .5 * thiswidth;
		document.getElementById('imageheight').value = .5 * thisheight;
		document.getElementById('enlarged').value = "200";
		}
	if (document.getElementById('enlarged').value == 200) {
		document.getElementById('mainimage').width = .5 * thiswidth;
		document.getElementById('mainimage').height = .5 * thisheight;
		document.getElementById('enlarged').value = "100";
		return;
		}
	else if (document.getElementById('enlarged').value == 100) {
		document.getElementById('mainimage').width = .5 * thiswidth;
		document.getElementById('mainimage').height = .5 * thisheight;
		document.getElementById('enlarged').value = "50";
		return;
		}
	else if (document.getElementById('enlarged').value == 50) {
		document.getElementById('mainimage').width = .5 * thiswidth;
		document.getElementById('mainimage').height = .5 * thisheight;
		document.getElementById('enlarged').value = "25";
		return;
		}
	else {
		if (document.getElementById('enlarged').value == 999) {
			document.getElementById('mainimage').width = document.getElementById('imagewidth').value;
			document.getElementById('mainimage').height = document.getElementById('imageheight').value;
			document.getElementById('enlarged').value = "100";
			}
		return;
		}
	}

function changetheimage() {
	document.getElementById('brightnessvaluenumber').value = document.getElementById('brightnessvalue').value;
	document.getElementById('contrastvaluenumber').value = document.getElementById('contrastvalue').value;
	document.getElementById('saturationvaluenumber').value = document.getElementById('saturationvalue').value;
	document.getElementById('opacityvaluenumber').value = document.getElementById('opacityvalue').value;
	document.getElementById('huerotatevaluenumber').value = document.getElementById('huerotatevalue').value;
	document.getElementById('invertvaluenumber').value = document.getElementById('invertvalue').value;
	document.getElementById('blurvaluenumber').value = document.getElementById('blurvalue').value;
	document.getElementById('grayvaluenumber').value = document.getElementById('grayvalue').value;
	document.getElementById('sepiavaluenumber').value = document.getElementById('sepiavalue').value;

	thisbrightness = (document.getElementById('brightnessvalue').value / 10);
	thiscontrast = (document.getElementById('contrastvalue').value / 10);
	thissaturation = (document.getElementById('saturationvalue').value);
	thisopacity = (document.getElementById('opacityvalue').value / 10);
	thishuerotate = (document.getElementById('huerotatevalue').value + 'deg');
	thisinvert = (document.getElementById('invertvalue').value / 10);
	thisblur = (document.getElementById('blurvalue').value  + 'px');
	thisgray = (document.getElementById('grayvalue').value / 10);
	thissepia = (document.getElementById('sepiavalue').value / 10);
	displaynewimage();
	}

function xrayimage() {
	thisbrightness = (document.getElementById('brightnessvalue').value / 10);
	thiscontrast = (document.getElementById('contrastvalue').value / 10);
	thissaturation = (document.getElementById('saturationvalue').value);
	thisopacity = (document.getElementById('opacityvalue').value / 10);
	thishuerotate = (document.getElementById('huerotatevalue').value + 'deg');
	thisinvert = 1;
		document.getElementById('invertvalue').value = "10";
	thisblur = (document.getElementById('blurvalue').value  + 'px');
	thisgray = (document.getElementById('grayvalue').value / 10);
	thissepia = (document.getElementById('sepiavalue').value / 10);
	displaynewimage();
	}

function originalimage() {
	thisbrightness = 1;
		document.getElementById('brightnessvalue').value = "10";
	thiscontrast = 1;
		document.getElementById('contrastvalue').value = "10";
	thissaturation = 1;
		document.getElementById('saturationvalue').value = "1";
	thisopacity = 1;
		document.getElementById('opacityvalue').value = "10";
	thishuerotate = '0deg';
		document.getElementById('huerotatevalue').value = '0';
	thisinvert = 0;
		document.getElementById('invertvalue').value = "0";
	thisblur = '0px';
		document.getElementById('blurvalue').value  = '0';
	thisgray = 0;
		document.getElementById('grayvalue').value = "0";
	thissepia = 0;
		document.getElementById('sepiavalue').value = "0";
	//alert("opacity(" + thisopacity + ") " + "brightness(" + thisbrightness + ") " + "contrast(" + thiscontrast + ") " + "saturate(" + thissaturation + ") " + "hue-rotate(" + thishuerotate + ") " + "invert(" + thisinvert + ") " + "blur(" + thisblur + ") " + "grayscale(" + thisgray + ") " + "sepia(" + thissepia + ") ");
	displaynewimage();
	}

function displaynewimage() {
	document.getElementById('mainimage').style.webkitFilter = "opacity(" + thisopacity + ") " + "brightness(" + thisbrightness + ") " + "contrast(" + thiscontrast + ") " + "saturate(" + thissaturation + ") " + "hue-rotate(" + thishuerotate + ") " + "invert(" + thisinvert + ") " + "blur(" + thisblur + ") " + "grayscale(" + thisgray + ") " + "sepia(" + thissepia + ") ";
	//document.getElementById('mainimage').style.webkitFilter = "opacity(" + thisopacity + ") " + "brightness(" + thisbrightness + ") " + "contrast(" + thiscontrast + ") " + "saturate(" + thissaturation + ") " + "hue-rotate(" + thishuerotate + ") " + "invert(" + thisinvert + ") " + "blur(" + thisblur + ") " + "grayscale(" + thisgray + ") " + "sepia(" + thissepia + ") ";
	//theSettings = "opacity(" + thisopacity + ") " + "brightness(" + thisbrightness + ") " + "contrast(" + thiscontrast + ") " + "saturate(" + thissaturation + ") " + "hue-rotate(" + thishuerotate + ") " + "invert(" + thisinvert + ") " + "blur(" + thisblur + ") " + "grayscale(" + thisgray + ") " + "sepia(" + thissepia + ") ";
	//document.getElementById('mainimage').style.cssText = "padding:20px;float:left;webkit-filter:" + theSettings + "; -moz-filter:" + theSettings + "; -ms-filter:" + theSettings + "; -o-filter:" + theSettings;
	//alert("padding:20px;float:left;webkit-filter:" + theSettings + "; -moz-filter:" + theSettings + "; -ms-filter:" + theSettings + "; -o-filter:" + theSettings);
	//document.getElementById('mainimage').style.cssText = "filter: brightness(" + thisbrightness + "); -moz-filter: brightness(" + thisbrightness + "); -o-filter: brightness(" + thisbrightness + "); -ms-filter: brightness(" + thisbrightness + "); -webkit-filter: brightness(" + thisbrightness + ");";
	}

function ReplaceContentInContainer(id,content) {
var container = document.getElementById(id);
//container.innerHTML = content;
container.innerHTML = '<img src="images/loader.gif" style="margin-top:100px;">';
}

$(document).ready(function() {
    $('mainimage').mousewheel(function(e, delta) {
        this.scrollLeft -= (delta * 40);
        e.preventDefault();
    });
});

function iecamera() {
	var ua = window.navigator.userAgent;
	var msie = navigator.userAgent.indexOf("MSIE")
	if(msie>0){
		document.getElementById('camerapic').style.display='none';
		document.getElementById('imgfile').style.display='block';
		}
	var msie = parseInt(navigator.userAgent.indexOf("rv:11"));
	if(msie > 0){
		document.getElementById('camerapic').style.display='none';
		document.getElementById('imgfile').style.display='block';
		}
}

function checkpassword() {
	password1 = document.getElementById('Password1').value;
	password2 = document.getElementById('Password2').value;
	if (password1.length > 0 && password2.length > 0)
		{
		if (password1 != password2)
			{
			alert("Passwords do not match!");
			return false
			}
		}
	}

$("#UserName").blur(function (e) { //user types username on inputfiled
   var UserName = $(this).val(); //get the string typed by user
   $.post('check_username.php', {'username':UserName}, function(data) { //make ajax call to check_username.php
   $("#user-result").html(data); //dump the data received from PHP page
	if(data == '1'){
		$("#save").attr("disabled", false);
		$("#user-result").html('<span style="color:green">Available</span>');
		$('#UserName').css('border', '3px #2ecc71 solid');
		}
	else{
		$("#save").attr("disabled", true);
		$("#user-result").html('<span style="color:red">Unavailable</span>');
		$('#UserName').css('border', '3px #c0392b solid');
	}
   
   });
});

function getZipCode() {
	request = createRequest();
	if (request == null) {
		alert("Unable to create request");
	} else {
		var theName = document.getElementById("ZipCode").value;
		var ZipCode = escape(theName);
		var url = "zipcodelookup.php?zipcode=" + ZipCode;
		request.onreadystatechange = showCityState;
		request.open("GET", url, true);
		request.send(null);
	}
}

function showCityState() {
	if (request.readyState == 4) {
		if (request.status == 200) {
			var contentPane = document.getElementById("citystate");
			contentPane.innerHTML = request.responseText;
		} else {
			var contentPane = document.getElementById("citystate");
			contentPane.innerHTML = "<p></p>";
		}
	}
}

function getTerritories() {
	request = createRequest();
	if (request == null) {
		alert("Unable to create request");
	} else {
		var theName = document.getElementById("State").value;
		var State = escape(theName);
		var url = "territorylookup.php?state=" + State;
		request.onreadystatechange = showTerritories;
		request.open("GET", url, true);
		request.send(null);
	}
}

function showTerritories() {
	if (request.readyState == 4) {
		if (request.status == 200) {
			var contentPane = document.getElementById("territories");
			contentPane.innerHTML = request.responseText;
		} else {
			var contentPane = document.getElementById("territories");
			contentPane.innerHTML = "<p></p>";
		}
	}
}

$(document).ready(function() {
	$("#ZipCode").keyup(function() {
		var el = $(this);
		if (el.val().length === 5) {
			$.ajax({
				url: "http://bsmbrand.com/c/1MNP/zipcodelookup.php",
				cache: false,
				dataType: "json",
				type: "GET",
				data: "zipcode=" + el.val(),
				success: function(result, success) {
				  $("#city").val(result.city);
				  $("#state").val(result.state);			
				}
			});
		}
	});
});

function createRequest() {
	try {
		request = new XMLHttpRequest();
	} catch (tryMS) {
		try {
			request = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (otherMS) {
		try {
			request = new ActiveXObject("Microsoft.XMLHTTP");
		} catch (failed) {
			request = null;
			}
		}
	}
	return request;
}

function iedetection() {
	var ua = window.navigator.userAgent;
	var msie = ua.indexOf("MSIE ");
	//if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) {
		document.getElementById('iepresent').innerHTML = 'Camera access issues: Try using a different browser, Chrome, Safari or FireFox support cloud Apps, or try the Choose File Button below.';
		//}
	}
