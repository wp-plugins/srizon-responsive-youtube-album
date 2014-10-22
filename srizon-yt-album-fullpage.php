<?php
$paddingclass = ' padding'.$album['thumbpadding'];
$roundingclass = ' rounding'.$album['conrnerrounding'];
$shadowclass = ' shadow'.$album['thumbshadow'];
$outerwidthclass = ' outerwidthlarge'.$album['largerow'].' outerwidthsmall'.$album['smallrow'];
if($album['iconoverlay'] == 'yes'){
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
	
	// todo: vidio icon
	//if($vidicon == 'yes') $imgcode.='<div class="vid_icon"></div>';
	
	// show the thumb
	$output.= '<div class="yt-fp-outer'.$outerwidthclass.$roundingclass.'">';
	$output.=   '<div class="yt-fp-padding'.$paddingclass.'">';
	$output.=     '<div class="imgbox fpthumb'.$shadowclass.'" >'.$link.$imgcode.$vidicon.'</a></div>';
	if($album['showtitle']=='yes'){
		$output.= '<div class="titlebelowthumb" style="height:'.trim($album['titleboxheight']).'px">'.$link.$video['title'].'</a></div>';
	}
	$output.=   '</div>';
	$output.= '</div>';
}
// processing all video done
$output.= '<div style="clear:both;"></div></div>';