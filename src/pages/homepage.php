<?php 
	if (!isset($db))
		exit; // direct object reference
	
	include("header.php");
?>
<h3>Hallo!</h3>

<p>Deze website is bedoeld voor leden van <a href="http://www.game-maker.nl">www.game-maker.nl</a>.
Je kunt hier profielen bekijken van mensen om teamgenoten te zoeken, en natuurlijk je eigen profiel maken. Wat wil je doen?</p>
<p><ul class="no-listing">
	<li><a href="./?page=profielen">Profielen bekijken</a></li>
<?php
	if(!isIngelogd()) { ?>
<li><a href="./?page=inloggen">Inloggen op jouw profiel</a></li>
<li><a href="./?page=nieuwprofiel">Een nieuw profiel aanmaken</a></li><?php } else { ?>
<li><a href="./?page=mijnprofiel">Mijn profiel</a></li><?php } ?>
</ul></p>
<?php
	include("footer.php");