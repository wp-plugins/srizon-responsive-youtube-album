<?php
$roundingclass = ' rounding'.$album['conrnerroundingd'];
$shadowclass = ' shadow'.$album['thumbshadowd'];
$ratioclass = ' ratio'.$album['tdratio'];
if($album['iconoverlayd'] == 'yes'){
	$vidicon = '<div class="vidicon"></div>';
}
else{
	$vidicon = '';
}

// container
$output.= '<div class="srizon-yt-container">';

// process each video
foreach($videos as $video){
	// set up link
	if(strpos($video['link'],'&')) $minlink = substr($video['link'],0,strpos($video['link'],'&'));
	else $minlink = $video['link'];
	$link = '<a class="magpopif" href="'.$minlink.'">';
	
	// set up imgcode
	$imgcode = str_replace("<img","<img alt=\"".$video['title']."\"",$video['img']);
    $imgcode = str_replace('alt=""','',$imgcode);
	if(function_exists('mb_substr')){
		if(mb_strlen($video['desc']) > $album['trimdesc']){
			$video['desc'] = mb_substr($video['desc'], 0, $album['trimdesc']);
			$video['desc'] .= '...';
		}
	}
	else if(strlen($video['desc']) > $album['trimdesc']){
		$video['desc'] = substr($video['desc'], 0, $album['trimdesc']);
		$video['desc'] .= '...';
	}
	// todo: change to description layout
	$output.= '<div class="descbox'.$ratioclass.'">';
	$output.= '	<div class="yt-twd-outer'.$roundingclass.'">';
	$output.= '		<div class="imgbox twdthumb'.$shadowclass.'" >'.$link.$imgcode.$vidicon.'</a></div>';
	$output.= '	</div>';
	$output.= ' <div class="titlendesc">';
	$output.= '		<h5 class="titledesc">'.$link.$video['title'].'</a></h5>';
	$output.= '		<div class="descdesc">'.$video['desc'].'</div>';
	$output.= '	</div>';
	$output.= '</div><div class="divider"></div>';
}

// processing all video done
$output.= '<div style="clear:both;"></div></div>';