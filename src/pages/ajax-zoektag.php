<?php 
	if (!isset($db))
		exit;
	
	if (preg_match("/^[a-z0-9.\\-]*$/i", $_GET["tag"]) != 1)
			die("Error 672142984");
	
	$result = $db->query("SELECT t.naam, COUNT(ut.id) AS 'theCount'
		FROM tags t
		LEFT OUTER JOIN users_tags ut ON ut.tagid = t.id
		WHERE t.naam LIKE '%" . $db->escape_string($_GET["tag"]) . "%'
		GROUP BY ut.tagid
		ORDER BY theCount")
		or die("Database error 2389124");
	
	$json = "[";
	$comma = "";
	while ($row = $result->fetch_row()) {
		$json .= $comma;
		$comma = ",";
		$json .= "{naam: '" . $row[0] . "', count: " . $row[1] . "}";
	}
	$json .= "]";
	
	echo $json;