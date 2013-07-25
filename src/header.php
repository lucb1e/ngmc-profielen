<?php 
	if (!isset($db))
		exit;
	
	$ingelogd = !empty($_SESSION["profielid"]);
	
	$menu = "<a href='?page=profielen'>Profielen</a> - ";
	if ($ingelogd)
		$menu .= "<a href='?page=mijnprofiel'>Mijn Profiel</a> - "
			. "<a href='?page=uitloggen&csrf=" . $_SESSION["csrf"] . "'>Uitloggen</a>";
	else 
		$menu .= "<a href='?page=inloggen'>Inloggen</a> - "
			. "<a href='?page=nieuwprofiel'>Registreren</a>";
	
?><!DOCTYPE html>
<html>
	<head>
		<title>NGMC profielen :: Lucb1e.com</title>
	</head>
	<body>
		<div style='width: 650px; margin: 0 auto 0 auto; padding-top: 5px;'>
			<div style="float: right; margin-top: 28px;">
				<?php echo $menu; ?>
			</div>
			<div style="width: 350px;">
				<h2><a href='./' style='color: #000; text-decoration: none;'>NGMC profielen</a></h2>
			</div>