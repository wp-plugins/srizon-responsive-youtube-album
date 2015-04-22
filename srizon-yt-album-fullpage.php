<?php
$paddingclass    = ' padding' . $album['thumbpadding'];
$roundingclass   = ' rounding' . $album['conrnerrounding'];
$shadowclass     = ' shadow' . $album['thumbshadow'];
$outerwidthclass = ' outerwidthlarge' . $album['largerow'] . ' outerwidthsmall' . $album['smallrow'];
if ( $album['iconoverlay'] == 'yes' ) {
	$vidicon = '<div class="vidicon"></div>';
} else {
	$vidicon = '';
}

// container
$output .= '<div class="srizon-yt-container" id="' . $scroller_id . '">';

$totvid       = count( $videos );
$srz_cur_page = isset( $_REQUEST[ $paging_id ] ) ? ( $_REQUEST[ $paging_id ] - 1 ) : 0;
$videos       = array_slice( $videos, $srz_cur_page * $album['paginatenum'], $album['paginatenum'] );

// process each video
foreach ( $videos as $video ) {
	// set up link
	if ( strpos( $video['link'], '&' ) ) {
		$minlink = substr( $video['link'], 0, strpos( $video['link'], '&' ) );
	} else {
		$minlink = $video['link'];
	}
	$link = '<a class="magpopif" href="' . $minlink . '">';

	// set up imgcode
	$imgcode = str_replace( "<img", "<img alt=\"" . $video['title'] . "\"", $video['img'] );
	$imgcode = str_replace( 'alt=""', '', $imgcode );

	// todo: vidio icon
	//if($vidicon == 'yes') $imgcode.='<div class="vid_icon"></div>';

	// show the thumb
	$output .= '<div class="yt-fp-outer' . $outerwidthclass . $roundingclass . '">';
	$output .= '<div class="yt-fp-padding' . $paddingclass . '">';
	$output .= '<div class="imgbox fpthumb' . $shadowclass . '" >' . $link . $imgcode . $vidicon . '</a></div>';
	if ( $album['showtitle'] == 'yes' ) {
		$output .= '<div class="titlebelowthumb" style="height:' . trim( $album['titleboxheight'] ) . 'px">' . $link . $video['title'] . '</a></div>';
	}
	$output .= '</div>';
	$output .= '</div>';
}
//pagination
$pagination_text = srizon_show_pagination( $album['paginatenum'], $totvid, $scroller_id, $paging_id );
$output .= $pagination_text;

$output .= '<div style="clear:both;"></div></div>';