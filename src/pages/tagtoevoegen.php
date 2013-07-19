<?php 
	if (!isset($db))
		exit;
	
	checkIngelogd();
	
	if (isset($_GET["tag"])) {
		if (preg_match("/^[a-z\\-]*$/i", $_GET["tag"]) != 1)
			die("Error 672142983");
		
		$result = $db->query("SELECT id FROM tags WHERE naam = '" . $db->escape_string($_GET["tag"]) . "'")
			or die("Database error 889879");
		
		$ok = true;
		
		if ($result->num_rows == 0) {
			$result = $db->query("SELECT id FROM tags WHERE door_userid = " . intval($_SESSION["profielid"]) . " AND tijd_toegevoegd > " . (time() - (3600 * 24 * 60)))
				or die("Database error 8392242");
			
			if ($result->num_rows > 15) { // Dit limiet moet later misschien worden verlaagd. Het is nu redelijk ruim omdat de meeste tags nog niet bestaan in het begin.
				echo "Je hebt al een hoop nieuwe tags toegevoegd! Misschien even buiten gaan spelen?";
				$ok = false;
			}
			else {
				$db->query("INSERT INTO tags (naam, door_userid, tijd_toegevoegd)
					VALUES('" . $db->escape_string($_GET["tag"]) . "', " . intval($_SESSION["profielid"]) . ", " . time() . ")")
					or die("Datbase error 61427953");
				
				$db->query("INSERT INTO users_tags (userid, tagid, opmerking)
					VALUES (" . intval($_SESSION["profielid"]) . ", " . $db->insert_id . ", '" . $db->escape_string($_GET["opmerking"]) . "')")
					or die("Database error 1902573");
				
				header("HTTP/1.1 302 Moved Temporarily");
				header("Location: ./?page=mijnprofiel");
				exit;
			}
		}
		else {
			$result = $result->fetch_row();
			$db->query("INSERT INTO users_tags (userid, tagid, opmerking)
				VALUES (" . intval($_SESSION["profielid"]) . ", " . $result[0] . ", '" . $db->escape_string($_GET["opmerking"]) . "')")
				or die("Database error 20473841");
			
			header("HTTP/1.1 302 Moved Temporarily");
			header("Location: ./?page=mijnprofiel");
			exit;
		}
	}
	
	require("header.php");
?>
<style>
	.tag {
		background: #eee;
		border: 1px solid #ddd;
	}
</style>

<h3>Tag toevoegen aan jouw profiel</h3>

<noscript>Deze pagina heeft Javascript nodig. Zet NoScript eens uit.</noscript>

Tag naam:<br/>
<input id=tagnaam />
<div id="gevonden_tags"></div>
<br/>
Eventuele opmerking toevoegen:<br/>
<input id=opmerking /><br/>
<br/>
<input type=button value=Toevoegen onclick='toevoegen();'/>

<script>
	var naam_input = document.getElementById("tagnaam");
	var gevonden_tags = document.getElementById("gevonden_tags");
	var opmerking = document.getElementById("opmerking");
	var seqnum = 0;
	var laatst_voltooide_seqnum = -1;
	
	function aGET(uri, callback, seqnum) {
		var req = new XMLHttpRequest();
		req.open("GET", uri, true);
		req.send(null);
		req.onreadystatechange = function() {
			if (req.readyState == 4)
				callback(req.responseText, seqnum);
		}
	}
	
	function kiesTag(naam) {
		naam_input.value = naam;
	}
	
	function toevoegen() {
		if (!testGeldigeNaam())
			return;
		
		location = "./?page=tagtoevoegen&csrf=<?php echo $_SESSION["csrf"];?>&tag=" + naam_input.value + "&opmerking=" + escape(opmerking.value);
	}
	
	function testGeldigeNaam() {
		if (!(/^[a-z\-]*$/i.test(naam_input.value))) {
			gevonden_tags.innerHTML = "Tags mogen alleen letters en koppeltekens (-) bevatten!";
			return false;
		}
		return true;
	}
	
	naam_input.onkeyup = function() {
		if (!testGeldigeNaam())
			return;
		
		if (naam_input.value.length < 2)
			return;
		
		aGET("./?page=ajax-zoektag&tag=" + escape(naam_input.value), function(data, seqnum) {
			// Requests hoeven niet op volgorde binnen te komen. Dit fixt dat.
			if (seqnum <= laatst_voltooide_seqnum)
				return;
			
			laatst_voltooide_seqnum = seqnum;
			
			var tags = eval(data);
			var html = "";
			for (var i in tags) {
				html += "<a href='javascript: kiesTag(\"" + tags[i]["naam"] + "\");'>" + tags[i]["naam"] + " (" + tags[i]["count"] + "x)</a></div>";
			}
			gevonden_tags.innerHTML = html;
		}, seqnum++);
	};
</script>

<?php
	require("footer.php");