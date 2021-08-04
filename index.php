<?php
	
	/*
	$quotes_data = array();

	$xml_techs = simplexml_load_file('CIV4GameTextInfos_Objects.xml');
	$xml_quotes = simplexml_load_file('CIV4GameTextInfos.xml');

	$i = 0;

	foreach ($xml_techs as $tech):
		if (substr($tech->Tag, 0, 13) == "TXT_KEY_TECH_") {
			$str_audio = (string) strtolower(str_replace("TXT_KEY_TECH_", "", $tech->Tag));
			$quotes_data[$i]['title'] = (string) $tech->English;
			$quotes_data[$i]['audio'] = 'Tech_'.str_replace(' ', '', ucwords(str_replace('_', ' ', $str_audio))).'.mp3';
			$i++;
		}
	endforeach;

	$i = 0;

	foreach ($xml_quotes as $quote):
		if (substr($quote->Tag, 0, 13) == "TXT_KEY_TECH_") {
			$quotes_data[$i]['description'] = (string) $quote->English;
			$i++;
		}
	endforeach;

	$quotes = array();

	foreach ($quotes_data as $item) {
		$title_key = strtolower(str_replace(" ", "_", $item['title']));
		$quotes[$title_key] = $item;
	}

	ksort($quotes);

	echo json_encode($quotes);
	exit;
	
	*/

	$base_url = 'http://localhost/civquotes';

	$quotes_json = file_get_contents('civquotes.json');

	$quotes = json_decode($quotes_json, true);

	$query_parts = explode('/', $_SERVER['REQUEST_URI']);
	$q = array_pop($query_parts);

	if ($q != '' && array_key_exists($q, $quotes)) {
		$q = $q;
	} else {
		$q = array_rand($quotes, 1);
	}

	$title = $quotes[$q]['title'];
	$quote_full = $quotes[$q]['description'];
	$audio = $quotes[$q]['audio'];

	$blockquote = explode('" - ', $quote_full);
	$quote = '&#8220;'.substr($blockquote[0], 1).'&#8221';
	$cite = ' &#8212; '.htmlentities($blockquote[1], ENT_QUOTES);
?>
<!DOCTYPE html>
<html lang="en" prefix="og: http://ogp.me/ns#">
	<head>
	<meta charset="utf-8"/>
	<title>CivQuotes: <?= $title ?></title>
	<meta name="description" content="<?= $quote.$cite ?>">
	<meta property="og:title" content="<?= $title ?>">
	<meta property="og:type" content="website">
	<meta property="og:url" content="<?= $base_url ?>/index.php/<?= $q ?>">
	<meta property="og:image" content="<?= $base_url ?>/assets/civ4icon.png" />
	<meta property="og:site_name" content="CivQuotes">
	<meta property="og:description" content="<?= $quote.$cite ?>">
	<link rel="stylesheet" media="all" href="<?= $base_url ?>/assets/screen.css"/>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>
</head>
<body>
	<h4><?= $title ?></h4>
	<br>
	<blockquote>
	<?= $quote ?>
	<cite><?= $cite ?></cite>
	</blockquote>
	<br>
	<audio id="audioplayer" controls autoplay>
		<source id="quote_mp3" src="<?= $base_url ?>/assets/<?= $audio ?>" type="audio/mpeg">
		[Your browser does not support the audio element.]
	</audio>
	<br>
	<div class="links">
	<?php
	foreach ($quotes as $key=>$item) {
		echo '<a href="'.$base_url.'/index.php/'.$key.'">';
		echo $item['title'];
		echo '</a><br>';
	}
	?>
	</div>
	<br>
	<small>Audio files Copyright by Firaxis Games</small>
</body>
</html>