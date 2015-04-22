<?php
function srz_yt_enqueue_script() {
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'srzmp', srz_yt_get_resource_url( 'js/mag-popup.js' ), array( 'jquery' ) );
	wp_enqueue_script( 'srzcustom', srz_yt_get_resource_url( 'js/srizon.custom.min.js' ), array( 'jquery' ) );
	wp_enqueue_style( 'srzmpcss', srz_yt_get_resource_url( 'css/mag-popup.min.css' ) );
	wp_enqueue_style( 'srzytstyles', srz_yt_get_resource_url( 'css/srzytstyles.css' ) );
	wp_enqueue_style( 'srzcustomcss', srz_yt_get_resource_url('css/srizon.custom.min.css'));
}

function srz_yt_album_shortcode( $atts ) {
	if ( ! isset( $atts['id'] ) ) {
		return 'Invalid shortcode... ID missing';
	}
	$albumid = $atts['id'];
	$album   = SrizonYTDB::GetAlbum( $albumid );
	if ( ! $album ) {
		return 'Album Not found. Check the shortcode';
	}
	if ( isset( $_GET['debugsrzyt'] ) ) {
		echo '<pre>';
		print_r( $album );
		echo '</pre>';
	}

	$videos = srz_yt_get_album_api( $album );
	$output = '';

	if ( isset( $GLOBALS['scroller_id'] ) ) {
		$GLOBALS['scroller_id'] ++;
	} else {
		$GLOBALS['scroller_id'] = 1;
	}
	$scroller_id = 'srizonytscroller' . $GLOBALS['scroller_id'];
	$paging_id   = 'ytpage' . $GLOBALS['scroller_id'];

	if ( is_array( $videos ) ) {
		if ( count( $videos ) > $album['totalvid'] ) {
			$videos = array_slice( $videos, 0, $album['totalvid'] );
		}

		if ( $album['liststyle'] == 'fullpage' ) {
			include 'srizon-yt-album-fullpage.php';
		} else if ( $album['liststyle'] == 'description' ) {
			include 'srizon-yt-album-description.php';
		} else if ( $album['liststyle'] == 'respslider' ) {
			$output .= 'This layout is available on pro version only';
		}
	}

	return $output;

	//else return 'will process album with id: '.$atts['id']. ' now!';
}

function srz_yt_extract_id_or_username($url, &$type){
	//first change https links to http
	$url = str_replace('https:', 'http:', $url);

	//handle format https://www.youtube.com/user/trailers
	if(strpos($url,'http://www.youtube.com/user/') !== false){
		$url = str_replace('http://www.youtube.com/user/','',$url);
		$type = 'user';
	}

	//handle format https://www.youtube.com/channel/UCK7eHebP6b5JbkpX6zvJkRQ

	else if(strpos($url,'http://www.youtube.com/channel/') !== false){
		$url = str_replace('http://www.youtube.com/channel/','',$url);
		$type = 'channel';
	}
	else{
		$type = '';
	}

	return $url;
}

function srz_yt_get_user_upload_playlist($userid,$key){
	$url = 'https://www.googleapis.com/youtube/v3/channels?forUsername='.$userid.'&key='.$key.'&part=contentDetails&fields=items/contentDetails/relatedPlaylists';
	srz_yt_debug_msg('Trying to get data from:','<a href="'.$url.'" target="_blank">'.$url.'</a>');
	$json_str = wp_remote_get($url,array( 'timeout' => 30));
	$json = json_decode($json_str['body']);
	if(isset($json->error)){
		srz_yt_debug_msg('Error:',$json->error->message);
		return false;
	}
	else if(isset($json->items[0]->contentDetails->relatedPlaylists->uploads)){
		$api_id = $json->items[0]->contentDetails->relatedPlaylists->uploads;
		srz_yt_debug_msg('Got the ID:',$api_id);
		return $api_id;
	}
	else{
		srz_yt_debug_msg('Error:','No Uploads Found');
		return false;
	}
}

function srz_yt_get_channel_upload_playlist($channel_id,$key){
	$url = 'https://www.googleapis.com/youtube/v3/channels?id='.$channel_id.'&key='.$key.'&part=contentDetails&fields=items/contentDetails/relatedPlaylists';
	srz_yt_debug_msg('Trying to get data from:','<a href="'.$url.'" target="_blank">'.$url.'</a>');
	$json_str = wp_remote_get($url,array( 'timeout' => 30));
	$json = json_decode($json_str['body']);
	if(isset($json->error)){
		srz_yt_debug_msg('Error:',$json->error->message);
		return false;
	}
	else if(isset($json->items[0]->contentDetails->relatedPlaylists->uploads)){
		$api_id = $json->items[0]->contentDetails->relatedPlaylists->uploads;
		srz_yt_debug_msg('Got the ID:',$api_id);
		return $api_id;
	}
	else{
		srz_yt_debug_msg('Error:','No Uploads Found');
		return false;
	}

}

function srz_yt_find_type($id, $key){
	// see if it's a username
	$url = 'https://www.googleapis.com/youtube/v3/channels?forUsername='.$id.'&key='.$key.'&part=id';
	srz_yt_debug_msg('Trying to get data from:','<a href="'.$url.'" target="_blank">'.$url.'</a>');
	$json_str = wp_remote_get($url,array( 'timeout' => 30));
	$json = json_decode($json_str['body']);
	if(isset($json->items[0]->id)) return 'user';

	// see if it's a channel id
	$url = 'https://www.googleapis.com/youtube/v3/channels?id='.$id.'&key='.$key.'&part=id';
	srz_yt_debug_msg('Trying to get data from:','<a href="'.$url.'" target="_blank">'.$url.'</a>');
	$json_str = wp_remote_get($url,array( 'timeout' => 30));
	$json = json_decode($json_str['body']);
	if(isset($json->items[0]->id)) return 'channel';

	return '';
}

function srz_yt_get_pl_id( $album ){
	$api_id = trim($album['api_id']);
	$type = '';

	// url entered
	if((strpos($api_id,'http://') !== false) or (strpos($api_id,'https://') !== false)){
		$api_id = srz_yt_extract_id_or_username($api_id, $type);
	}
	// bare id or username entered - for legacy support
	else{
		$type = srz_yt_find_type($api_id,$album['api_key']);
	}

	if($type == 'user'){
		$pl_id = srz_yt_get_user_upload_playlist($api_id,$album['api_key']);
	}
	else if($type == 'channel'){
		$pl_id = srz_yt_get_channel_upload_playlist($api_id,$album['api_key']);
	}
	else{
		srz_yt_debug_msg('Error: ','Channel/Playlist URL seems incorrect - '. $album['api_id']);
		return false;
	}

	return $pl_id;
}

function srz_yt_get_videos_from_pl_id($pl_id,$key,&$videos){
	$url = 'https://www.googleapis.com/youtube/v3/playlistItems?part=snippet&key='.$key.'&playlistId='.$pl_id.'&fields=nextPageToken,pageInfo,items(snippet(title,description,resourceId/videoId,thumbnails/medium/url))&maxResults=25';
	srz_yt_debug_msg('Trying to get data from:','<a href="'.$url.'" target="_blank">'.$url.'</a>');
	$json_str = wp_remote_get($url,array( 'timeout' => 30));
	$json = json_decode($json_str['body']);
	$i = 0;
	foreach($json->items as $item){
		$videos[$i]['id'] = $item->snippet->resourceId->videoId;
		$videos[$i]['link'] = 'https://www.youtube.com/watch?v='.$videos[$i]['id'];
		$videos[$i]['title'] = $item->snippet->title;
		$videos[$i]['desc'] = $item->snippet->description;
		$videos[$i]['img'] = '<img src="' . $item->snippet->thumbnails->medium->url . '" />';
		$i++;
	}
	return $videos;
}

function srz_yt_get_album_api( $album ) {
	$cachetime = $album['updatefeed'] * 60;
	$unique_id  = $album['api_id'];
	$unique_id2 = $unique_id . 'back';
	$videos     = get_transient( md5( $unique_id ) );
	if(!$videos) $videos = array();
	if ( ! count($videos) or isset( $_GET['forcesyncyt'] ) ) {
		$pl_id = srz_yt_get_pl_id($album);
		if($pl_id !== false){
			$videos = srz_yt_get_videos_from_pl_id($pl_id,$album['api_key'],$videos);
		}
		if ( ! count($videos) ) {
			$videos = get_transient( md5( $unique_id2 ) );
		}
		if ( ! count($videos) and isset( $_GET['debugsrzyt'] ) ) {
			echo 'Looks like your server cannot connect with youtube. Ask your hosting provider to enable remote connection or try it on another server.';
		}
		if ( count($videos)) {
			set_transient( md5( $unique_id ), $videos, $cachetime );
			set_transient( md5( $unique_id2 ), $videos, 1000000 );
		}
	}
	return $videos;
}

function srz_yt_debug_msg($title, $message){
	if ( isset( $_GET['debugsrzyt'] )) {
		echo '<p>'.'<strong>' . $title . ' </strong>'. $message . '</p>';
	}
}
