<?php 
	if (!isset($db))
		exit; // direct object reference
	
	include("header.php");
?>
<h3>Hallo!</h3>

Deze website is bedoeld voor leden van <a href="http://www.game-maker.nl">www.game-maker.nl</a>.
Je kunt hier profielen bekijken van mensen om teamgenoten te zoeken, en natuurlijk je eigen profiel maken. Wat wil je doen?<br/>
<br/>
<a href="./?page=profielen">Profielen bekijken</a><br/>
<?php
	if(!isIngelogd()) { ?>
<br/>
<a href="./?page=inloggen">Inloggen op jouw profiel</a><br/>
<br/>
<a href="./?page=nieuwprofiel">Een nieuw profiel aanmaken</a><?php } else { ?>
<br />
<a href="./?page=mijnprofiel">Mijn profiel</a><?php } ?>

<?php
	include("footer.php");