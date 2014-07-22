<?php
$url = "http://www.dubstep.fm/archive.xml";
function file_get_contents_curl($url) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Set curl to return the data instead of printing it to the browser.
	curl_setopt($ch, CURLOPT_URL, $url);
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}
$tracks_results = file_get_contents_curl($url);
$podcast = simplexml_load_string($tracks_results);
$sImage = $podcast->channel->image->url;
$sHead = $podcast->channel->title;
$sType = $podcast->channel->item->enclosure['type'];
if (stripos ($sType, 'audio') !== false) { 
	$sIcon = "audio";
    	} else {
	$sIcon = "video";	
}
?>
<!DOCTYPE html>
<html>
<head>
<title>PHP JQuery Mobile Podcast Parser</title>
<link rel="shortcut icon" href="favicon.ico">
<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jquerymobile/1.4.2/jquery.mobile.css" />
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jquerymobile/1.4.2/jquery.mobile.min.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width-device,initial-scale=1">
</head>
<body>
<div data-role="page" id="podcast" data-theme="b" class="ui-nodisc-icon">
<div data-role="header" data-position="fixed">
<h3>PHP JQuery Mobile Podcast Parser</h3>
</div>
<div class="ui-content" role="main">
<ul data-role="listview" data-inset="true" data-split-icon="<?php echo $sIcon; ?>" data-filter="true">
<?php
$i = 0;
foreach($podcast->channel->item as $oItem) {
$iTunes = $oItem->children('http://www.itunes.com/dtds/podcast-1.0.dtd');
$sEnclosure = $oItem->enclosure['url'];
$spubDate = $oItem->pubDate;
$sTitle = $oItem->title;
$tmpBytes = $oItem->enclosure['length'];
$tmpMB = $tmpBytes / 1024 / 1024;    
preg_match('/^([0-9]*)/',$tmpMB,$aSize);
$sSize = $aSize[0];
$sTime = $iTunes->duration;
$sSubtitle = $iTunes->subtitle;
if (stripos ($sTime, ':') !== false) {
	print '<li data-role="list-divider"><strong>' . $spubDate . ' <span class="ui-li-count">' . $sTime . '</span></strong></li>' . "\n";
	} else {
	print '<li data-role="list-divider">' . $spubDate . '</li>' . "\n";
}
if (stripos ($sImage, 'http') !== false) {
	print '<li><a href="#' . $i . '"><img src="' . $sImage . '"><h2>' . $sTitle . '</h2><p>' . $sSubtitle . '</p><span class="ui-li-count">' . $sSize . ' MB</span></a><a href="' . $sEnclosure . '"></a></li>' . "\n";
	} else {
	print '<li><a href="#' . $i . '"><h2>' . $sTitle . '</h2><p>' . $sSubtitle . '</p><span class="ui-li-count">' . $sSize . ' MB</span></a><a href="' . $sEnclosure . '"></a></li>' . "\n";
}	
$i = $i + 1;
}?>
</ul></div>
</div>
</body>
</html>
