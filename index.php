<!--
ColourPicker v1.0 by nadnerb - April 2013

Farbtastic: http://acko.net/blog/farbtastic-jquery-color-picker-plug-in/

More info: http://blog.nadnerb.co.uk
Twitter: @_nadnerb



-->

<?php
	session_start();

	$address = '127.0.0.1:19444';
	$priority = 50;

	if($_POST['change']) {
		// change colour of the selected priority channel
		shell_exec('/usr/bin/hyperion-remote --address '.$address.' --color '.$_POST['colour'].' --priority '.$priority);
	}

	if($_POST['turnOff']) {
		// switch of the current priority channel
		shell_exec('/usr/bin/hyperion-remote --address '.$address.' --clear --priority '.$priority);
	}

	// retrieve all effects
	$effectsString = exec('/usr/bin/hyperion-remote --list | grep \'"name" : \' | cut -d \'"\' -f4 | tr \'\\n\' \',\'');
	$effects = explode(',', $effectsString);
	if (count($effects) > 0) {
		array_splice($effects, -1); // remove last empty element
	}

	for ($i = 0; $i < count($effects); $i++) {
		if($_POST['effect-'.$i.'']) {
			// switch on the selected effect
			shell_exec('/usr/bin/hyperion-remote --effect "'.$effects[$i].'" --priority '.$priority);
		}	
	}

	//save variables
	$_SESSION['colour'] = $_POST['colour'];
?>

<html>
	<head>
		<title>Hyperion ColourPicker</title>
	
	<!--iphone meta info -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta name="apple-mobile-web-app-status-bar-style" content="white" />
		<link rel="apple-touch-icon" href="colourPicker/icon.png"/>
	
	<!-- get current colour settings. if there are none, apply defaults -->
		<script type="text/javascript">
			var selColour="<?php if(isset($_SESSION['colour'])) { echo $_SESSION['colour']; } else { echo '00FFFF'; } ?>";
		</script>
		
	<!-- farbtastic -->
		<script type="text/javascript" src="colourPicker/jquery.js"></script>
		<script type="text/javascript" src="colourPicker/farbtastic.js"></script>
		<script type="text/javascript">
			$(document).ready(function() {
			 $('#colorpicker').farbtastic('#color');
			});
		</script>
		<link rel="stylesheet" href="colourPicker/style.css" type="text/css" />
	</head>
	
<!-- HTML -->
	<body ontouchstart="">
		<div align="center" id="content">
			<div id="colorpicker"></div> <!-- farbtastic div -->
			<form id="form" name="input" action="<?php  $_SERVER['PHP_SELF'] ?>" method="post">
				<div><input name="colour" id="color" size="7" type="text" class="colourCode"  value=""/></div>
				<div><input name="change" type="submit" class="large button green" value="Change Colour"/></div>
				<div><input name="turnOff" type="submit" class="large button red mb" value="Turn off LEDs"/></div>
			</form>
			
			<div><button id="more" class="large button blue" onClick="toggle_extras('hiddenDiv')">Effects</button></div>
			<div><button id="less" class="large button blue" onClick="toggle_extras('hiddenDiv')" style="display:none;">Close effects</button></div>
			
			<!-- extra buttons for non LED functions -->
			<form id="form2" name="input" action="<?php  $_SERVER['PHP_SELF'] ?>" method="post">
				<div class="hidden" id="hiddenDiv" style="display:none;">
					<?php
						// add all effect buttons
						for ($i = 0; $i < count($effects); $i++) {
							echo '<div><input name="effect-'.$i.'" type="submit" class="large button green" value="'.$effects[$i].'"/></div>';
						}
					?>
				</div>
			</form>
			<a href="https://github.com/tvdzwan/hyperion/wiki">Hyperion</a><br>(original version by <a href="http://twitter.com/_nadnerb">@_nadnerb</a>)
		</div>

	</body>
	<script language="JavaScript">
	
<!-- apply variables to input elements-->	
	$(".colourCode").css("backgroundColor",selColour);
	document.getElementById('color').value = selColour.toUpperCase();
	document.getElementById('priority').value = selPriority;

<!-- reveal function -->
	function toggle_extras(div) {
		if(document.getElementById(div).style.display === 'none') {
			document.getElementById(div).style.display = 'block';
			document.getElementById('more').style.display = 'none';
			document.getElementById('less').style.display = 'block';
			window.scrollTo(0, document.body.scrollHeight);
		}else {
			document.getElementById(div).style.display = 'none'
			document.getElementById('more').style.display = 'block';
			document.getElementById('less').style.display = 'none';
		}
	}
	</script>
</html>
