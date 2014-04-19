<?php

class SrizonYTDB{
	static function SaveCommonOpt(){
		$optvar = array();
		$optvar['loadlightbox'] = $_POST['loadlightbox'];
		$optvar['lightboxattrib'] = $_POST['lightboxattrib'];
		update_option('srzytcomm',$optvar);
		return $optvar;
	}
	
	static function GetCommonOpt(){
		$optvar = get_option('srzytcomm');
		if(!empty($optvar)) return $optvar;
		else{
			$optvardef = array();
			$optvardef['loadlightbox'] = 'yes';
			$optvardef['lightboxattrib'] = 'class="lightbox" rel="lightbox"';
			add_option('srzytcomm',$optvardef,'',true);
			return $optvardef;
		}
	}
	
	static function SaveAlbum($new=false){
		global $wpdb;
		$table = $wpdb->base_prefix.'srzyt_albums';
		$data['title'] = $_POST['title'];
		$data['api_id'] = trim($_POST['api_id']);
		$data['options'] = serialize($_POST['options']);
		if($new){	
			$wpdb->insert($table,$data);
			return $wpdb->insert_id;
		}
		else{
			$wpdb->update($table,$data,array('id'=>$_POST['id']));
			return $_POST['id'];
		}
	}
	
	static function GetAlbum($id){
		global $wpdb;
		$table = $wpdb->base_prefix.'srzyt_albums';
		$q = $wpdb->prepare("SELECT * FROM $table WHERE id = %d",$id);
		$album = $wpdb->get_row($q);
		if(!$album) return false;
		$ret = array();
		$ret['id'] = $id;
		$ret['title'] = $album->title;
		$ret['api_id'] = $album->api_id;
		$options = unserialize($album->options);
		foreach($options as $key=>$value){
			$ret[$key] = $value;
		}
		return $ret;
	}
	
	
	
	static function GetAllAlbums(){
		global $wpdb;
		$table = $wpdb->base_prefix.'srzyt_albums';
		$albums = $wpdb->get_results( "SELECT id, title FROM $table" );
		return $albums;
	}
	

	
	static function DeleteAlbum($id){
		SrizonYTDB::AlbumCacheClean($id);
		global $wpdb;
		$table = $wpdb->base_prefix.'srzyt_albums';
		$q = $wpdb->prepare("delete from $table where id = %d",$id);
		$wpdb->query($q);
	}
	
	static function AlbumCacheClean($id){
		$album = SrizonYTDB::GetAlbum($id);
		$ids = array('ytupload'.$album['api_id'],'ytuserfav'.$album['api_id'],'ytusersub'.$album['api_id']);
		foreach($ids as $albumid){
			delete_transient(md5($albumid));
			delete_transient(md5($albumid.'back'));
		}
	}
	
	static function SyncAlbum($id){
		$album = SrizonYTDB::GetAlbum($id);
		$ids = array('ytupload'.$album['api_id'],'ytuserfav'.$album['api_id'],'ytusersub'.$album['api_id']);
		foreach($ids as $albumid){
			delete_transient(md5($albumid));
		}
	}
	
	
	static function srz_yt_extract_ids($lines){
		$lines = str_replace(' ', "\n", $lines);
		$lines_arr = explode("\n",$lines);
		$id_arr = array();
		foreach($lines_arr as $line){
			if(strlen(trim($line))<5) continue;
			if(strpos($line, 'set=a.')){
				$line = substr($line, strpos($line, 'set=a.')+6);
				$line = substr($line, 0, strpos($line, '.'));
			}
			$id_arr[] = trim($line);
		}
		if(isset($_GET['debugjfb'])){
			echo 'Dumping IDs<pre>';
			print_r($id_arr);
			echo '</pre>';
		}
		return $id_arr;
	}
	
	
	static function CreateDBTables(){
		global $wpdb;
		$t_albums = $wpdb->base_prefix.'srzyt_albums';
		$sql = '
CREATE TABLE '.$t_albums.' (
  id int(11) NOT NULL AUTO_INCREMENT,
  title text CHARACTER SET utf8,
  api_id text CHARACTER SET utf8,
  options text CHARACTER SET utf8,
  PRIMARY KEY (id)
);
';
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}
	
	static function DeleteDBTables(){
		global $wpdb;
		$t_albums = $wpdb->base_prefix.'srzyt_albums';
		$sql = 'drop table '.$t_albums.';';
		$wpdb->query($sql);
	}
}
