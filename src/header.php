<?php 
	if (!isset($db))
		exit;
	
	$menu = "<a href='?page=profielen'>Profielen</a> - ";
	if (isIngelogd())
		$menu .= "<a href='?page=mijnprofiel'>Mijn Profiel</a> - "
			. "<a href='?page=uitloggen&csrf=" . $_SESSION["csrf"] . "'>Uitloggen</a>";
	else 
		$menu .= "<a href='?page=inloggen'>Inloggen</a> - "
			. "<a href='?page=nieuwprofiel'>Registreren</a>";
	if(isIngelogd()) {
		$current_user = $db->query("SELECT gebruikersnaam FROM users WHERE userid = " . intval($_SESSION["profielid"]))
			or die("Database error 6510924");
		$current_user = $current_user->fetch_array();
		$current_user = $current_user[0];
	}
?><!DOCTYPE html>
<html>
	<head>
		<title>NGMC profielen :: Lucb1e.com</title>
		<link rel="stylesheet" type="text/css" href="res/css/reset.css">
		<link rel="stylesheet" type="text/css" href="res/css/style.css">
	</head>
	<body>
		<div id="wrapper">
			<div id="header">
				<div id="logo">
					<a href='./' style='color: #000; text-decoration: none;'><img src="res/images/logo.png" alt="NGMC Profielen"></a>
				</div>
				<div id="menu">
					<?php
						if(isset($current_user)) {
							echo '<p>Ingelogd als <b>' . htmlspecialchars($current_user) . '</b>.</p>';
						} else {
							echo '<p>Niet ingelogd!</p>';
						}
					?>
					<?=$menu?>
				</div>
				<div class="clear"></div>
			</div>