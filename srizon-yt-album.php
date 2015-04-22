<?php
/*
Plugin Name: Srizon Responsive Youtube Album
Plugin URI: http://www.srizon.com/srizon-responsive-youtube-album
Description: Show your Youtube Channel on your WordPress Site
Version: 1.3
Author: Afzal
Author URI: http://www.srizon.com/contact
*/
require_once 'srizon-yt-ui.php';
require_once 'srizon-yt-db.php';
require_once 'srizon-yt-album-front.php';
require_once 'lib/srizon_functions.php';

register_activation_hook( __FILE__, 'srz_yt_install' );
register_uninstall_hook( __FILE__, 'srz_yt_uninstall' );
add_action( 'admin_menu', 'srz_yt_menu' );
add_action( 'wp_enqueue_scripts', 'srz_yt_enqueue_script' );
add_shortcode( 'srizonytalbum', 'srz_yt_album_shortcode' );

function srz_yt_install() {
	SrizonYTDB::CreateDBTables();
}

function srz_yt_uninstall() {
	//SrizonYTDB::DeleteDBTables();
}

function srz_yt_menu() {
	add_menu_page( 'YouTube Album', "YouTube Album", 'manage_options', 'SrzYt', 'srz_yt_options_page', srz_yt_get_resource_url( 'images/youtube-icon.png' ) );
	add_submenu_page( 'SrzYt', "YouTube Album", "Albums", 'manage_options', 'SrzYt-Albums', 'srz_yt_albums' );
}

function srz_yt_options_page() {
	SrizonYTUI::PageWrapStart();
	echo '<div class="icon32" id="icon-tools"><br /></div><h2>Srizon Youtube Album</h2>';

	SrizonYTUI::OptionWrapperStart();

	SrizonYTUI::RightColStart();
	SrizonYTUI::BoxHeader( 'box1', "About This Plugin" );
	echo '<p>This plugin uses the name or id of your youtube channel or playlist to grab the information of that channel or palylist including thumb, title, embed code, description and shows them using some beautiful layouts.</p>';
	SrizonYTUI::BoxFooter();
	SrizonYTUI::RightColEnd();

	SrizonYTUI::LeftColStart();

	// Begin: Pro version notice
	SrizonYTUI::BoxHeader('box2', "Get The pro version");
	echo '<div>For better functionality, layouts and options get the <a href="http://www.srizon.com/srizon-responsive-youtube-album">Pro version</a></div>
	<h5>Pro Features:</h5>
	<ol>
	<li>Support for Playlist</li>
	<li>Unlimited Video</li>
	<li>Responsive Slider Layout</li>
	<li>Priority support from the developer</li>
	</ol>
	';
	SrizonYTUI::BoxFooter();
	// End: Pro version notice

	SrizonYTUI::BoxHeader( 'box3', "What to do" );
	echo '<p><ol>
<li>Click on the Albums submenu</li>		
<li>Click "Add New" button to add a new album. (or click on an existing album title to edit that)</li>
<li>Fill-up or modify the form and save that</li>
<li>Your albums will be listed along with the shortcodes. Use the shortcodes into your page/post to show the video album</li>
<li>Try out different options to suit your need</li>
</ol></p>';
	SrizonYTUI::BoxFooter();
	SrizonYTUI::LeftColEnd();

	SrizonYTUI::OptionWrapperEnd();

	SrizonYTUI::PageWrapEnd();
}

function srz_yt_albums() {
	SrizonYTUI::PageWrapStart();
	if ( isset( $_REQUEST['srzf'] ) ) {
		switch ( $_REQUEST['srzf'] ) {
			case 'edit':
				srz_yt_albums_edit();
				break;
			case 'save';
				srz_yt_albums_save();
				break;
			case 'delete':
				srz_yt_albums_delete();
				break;
			case 'sync':
				srz_yt_albums_sync();
				break;
			default:
				break;
		}
	} else {
		echo '<h2>YouTube Albums<a href="admin.php?page=SrzYt-Albums&srzf=edit" class="add-new-h2">Add New</a></h2>';
		$albums = SrizonYTDB::GetAllAlbums();
		include( 'srizon-yt-album-table.php' );
	}
	SrizonYTUI::PageWrapEnd();
}

function srz_yt_albums_delete() {
	if ( isset( $_GET['id'] ) ) {
		SrizonYTDB::DeleteAlbum( $_GET['id'] );
	}
	echo '<h2>Youtube Album deleted</h2>';
	echo '<a href="admin.php?page=SrzYt-Albums">Go Back to Youtube Albums</a>';
}

function srz_yt_albums_edit() {
	if ( isset( $_GET['id'] ) ) {
		echo '<div id="icon-edit-pages" class="icon32 icon32-posts-page"><br></div><h2>Edit Album</h2>';
		$value_arr = SrizonYTDB::GetAlbum( $_GET['id'] );
	} else {
		echo '<div id="icon-edit-pages" class="icon32 icon32-posts-page"><br></div><h2>Add New Album</h2>';
		$value_arr = array(
			'title'            => '',
			'username'         => '',
			'playlist'         => '',
			'source'           => 'userupload',
			'updatefeed'       => '600',
			'totalvid'         => '20',
			'liststyle'        => 'fullpage',
			'tpltheme'         => 'white',
			'paginatenum'      => '18',
			'largerow'         => '3',
			'smallrow'         => '1',
			'thumbpadding'     => '5',
			'showtitle'        => 'yes',
			'titleboxheight'   => '30',
			'conrnerrounding'  => '7',
			'thumbshadow'      => '5l',
			'iconoverlay'      => 'yes',
			'tdratio'          => '2575',
			'conrnerroundingd' => '7',
			'thumbshadowd'     => '5l',
			'iconoverlayd'     => 'yes',
			'trimdesc'         => '200',
			'respslidespeed'   => '500',
			'respslidestart'   => '0',
			'targetheight'     => '200',
			'api_key'          => 'AIzaSyBbRXlCqwT1TmkWZoh_OyuzgJHoTdoZffY'
		);
	}
	SrizonYTUI::OptionWrapperStart();

	SrizonYTUI::RightColStart();
	SrizonYTUI::BoxHeader( 'box1', "Youtube Album" );
	echo '<div>Fill up the video source and other parameters properly. Save to get the shortcode. Use the shortcode on your post or page</div>';
	SrizonYTUI::BoxFooter();
	SrizonYTUI::RightColEnd();

	SrizonYTUI::LeftColStart();
	include 'srizon-yt-album-option-form.php';
	SrizonYTUI::LeftColEnd();

	SrizonYTUI::OptionWrapperEnd();
}

function srz_yt_albums_save() {
	if ( wp_verify_nonce( $_POST['srjyt_submit'], 'srz_yt_albums' ) == false ) {
		die( 'Nice Try!' );
	}
	if ( ! isset( $_POST['id'] ) ) {
		SrizonYTDB::SaveAlbum( true );
		echo '<h2>New Album Created</h2>';
	} else {
		SrizonYTDB::SaveAlbum( false );
		echo '<h2>Album Updated</h2>';
	}

	echo '<a href="admin.php?page=SrzYt-Albums">Go Back to Albums</a>';
}

function srz_yt_albums_sync() {
	if ( isset( $_GET['id'] ) ) {
		SrizonYTDB::SyncAlbum( $_GET['id'] );
	}
	echo '<h2>Cached Data Deleted! Album will be synced on next load. There\'s a backup cache. In case the sync fails data will be loaded from backup cache.</h2>';
	echo '<a href="admin.php?page=SrzYt-Albums">Go Back to Albums</a>';
}

function srz_yt_get_resource_url( $relativePath ) {
	return plugins_url( $relativePath, plugin_basename( __FILE__ ) );
}

