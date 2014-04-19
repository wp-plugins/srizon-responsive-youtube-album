<?php
function srz_yt_enqueue_script(){
	wp_enqueue_script('jquery');
	wp_enqueue_script('srzmp',  srz_yt_get_resource_url('js/mag-popup.js'),array('jquery'));
	wp_enqueue_style('srzmpcss',  srz_yt_get_resource_url('css/mag-popup.css'));
	wp_enqueue_style('srzytstyles',  srz_yt_get_resource_url('css/srzytstyles.css'));
}

function srz_yt_album_shortcode($atts){
	if(!isset($atts['id'])) return 'Invalid shortcode... ID missing';
	$albumid = $atts['id'];
	$album = SrizonYTDB::GetAlbum($albumid);
	if(!$album) return 'Album Not found. Check the shortcode';
	if(isset($_GET['debugsrzyt'])){
		echo '<pre>';
		print_r($album);
		echo '</pre>';
	}
	$videos = srz_yt_get_album_api($album);
	$output = '';
	
	if(is_array($videos)){
		if(count($videos) > $album['totalvid']) $videos = array_slice ($videos, 0, $album['totalvid']);
		
		if($album['liststyle'] == 'fullpage'){
			include 'srizon-yt-album-fullpage.php';
		}
		else if($album['liststyle'] == 'description'){
			include 'srizon-yt-album-description.php';
		}
	}
	
	return $output;
	
	//else return 'will process album with id: '.$atts['id']. ' now!';
}

function srz_yt_get_album_api($album){
	$cachetime = $album['updatefeed']*60;
	if($album['source'] == 'userupload'){
		$idprefix = 'ytupload';
		$urlprefix = "http://gdata.youtube.com/feeds/api/users/".$album['api_id']."/uploads?alt=rss";
	}
	else if($album['source'] == 'userfav'){
		$idprefix = 'ytuserfav';
		$urlprefix = "http://gdata.youtube.com/feeds/api/users/".$album['api_id']."/favorites?alt=rss";
	}
	else if($album['source'] == 'usersub'){
		$idprefix = 'ytusersub';
		$urlprefix = "http://gdata.youtube.com/feeds/api/users/".$album['api_id']."/newsubscriptionvideos?alt=rss";
	}

	$unique_id = $idprefix.$album['api_id'];
	$unique_id2 = $unique_id.'back';
	$videos = get_transient(md5($unique_id));
	if(!$videos or isset($_GET['forcesyncyt'])){
		$contents = srz_yt_remote_to_data($urlprefix);
		if(!$contents){
			$contents = get_transient(md5($unique_id2));
		}

		if(!$contents and isset($_GET['debugsrzyt'])){
			echo 'Looks like your server cannot connect with youtube. Ask your hosting provider to enable remote connection or try it on another server.';
		}
		if($contents){
			$videos = srz_yt_xml_to_array($contents);
			set_transient(md5($unique_id), $videos, $cachetime);
			set_transient(md5($unique_id2), $videos, 1000000);
		}
	}
	return $videos;
}

function srz_yt_remote_to_data($url){
	if(isset($_GET['debugsrzyt'])){
		echo 'getting remote data from:'.$url;
	}
	$data = @file_get_contents($url);

	if( strlen($data) < 500 and function_exists('curl_exec') ){
		require_once (dirname(__FILE__) . '/srizon-yt-mycurl.php');
		if(isset($_GET['debugsrzyt'])){
			echo "\n".'file_get_contents failed... trying curl';
		}
		$ytcurl = new SrzYTMycurl($url);
		$ytcurl->createCurl();
		$ytcurl->setUserAgent('');
		$ytcurl->setCookiFileLocation('');
		$ytcurl->setReferer('');
		$data = $ytcurl->tostring();
		if(isset($_GET['debugsrzyt'])){
			echo "\n".'curl failed to get the api response. either the pageid or albumid is wrong or your server is blocking all remote connection functions!';
		}
		
	}

	$data = str_replace('media:','media',$data);
	$data = str_replace('app:','app',$data);
	$data = str_replace('georss:','georss',$data);
	$data = str_replace('gml:','gml',$data);
	$data = str_replace('gd:','gd',$data);
	return $data;
}

function srz_yt_xml_to_array($contents){
	if(!extension_loaded('simplexml')){
		echo 'SimpleXML library is not loaded on this host. Ask your provider to load SimpleXML or switch to a different hosting.';
		return false;
	}
	
	$rss = new SimpleXMLElement($contents);
	global $wpdb;
	$i = 0;
	$videos = array();
	foreach($rss->channel->item as $item){
		$guid_split = parse_url($item->link);
		parse_str($guid_split['query'],$temp_v);
		$videos[$i]['id'] = $temp_v['v'];
		$videos[$i]['title'] = (string) $item->title;
		$videos[$i]['title'] = htmlspecialchars($videos[$i]['title']);
		$videos[$i]['link'] = (string) $item->link;
		$videos[$i]['desc'] = (string) $item->description;
		$videos[$i]['desc'] = htmlspecialchars($videos[$i]['desc']);
		$videos[$i]['img'] = '<img src="'.$item->mediagroup->mediathumbnail['url'].'" />';
		if(0){ // turn it on if https is required
			$videos[$i]['img'] = str_replace('http:','https:',$videos[$i]['img']);
			$videos[$i]['link'] = str_replace('http:','https:',$videos[$i]['link']);
		}
		$i++;
	}
	return $videos;
}