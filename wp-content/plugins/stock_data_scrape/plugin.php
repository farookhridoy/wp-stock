<?php
/*
   Plugin Name: Stock Data Scrape
   description: A plugin to create admin sideber
   Version: 1.0
   Author: GM Hridoy
   Author URI: https://github.com/gmfaruk
*/

//for create a folder in wp-content/uploads/ dir


 $upload = wp_upload_dir();
 $upload_dir = $upload['basedir'];
 $upload_dir = $upload_dir . '/stockfile';
 if (! is_dir($upload_dir)) {
    mkdir( $upload_dir, 0700 );
 }

if(!defined('WP_STOCK_PATH')) 
define('WP_STOCK_PATH',"wp-content/uploads/stockfile");

// Start the download if there is a request for that
function ibenic_download_file(){
   
  if( isset( $_GET["file"] ) && isset( $_GET['download_file'] ) ) {
		ibenic_send_file();
	}
}
add_action('init','ibenic_download_file');

// Send the file to download
function ibenic_send_file(){
	//get filedata
	$file = $_GET['file'];
	$uploads = wp_get_upload_dir();
	$theFile = $uploads['baseurl'] . "/stockfile/".$file;

	if( ! $theFile ) {
		return;
	}
  //clean the fileurl
	$file_url  = stripslashes( trim( $theFile ) );
  //get filename
	$file_name = basename( $theFile );
  //get fileextension

	$file_extension = pathinfo($file_name);
  //security check
	$fileName = strtolower($file_url);

	$whitelist = apply_filters( "ibenic_allowed_file_types", array('csv','xlsx') );

	if(!in_array(end(explode('.', $fileName)), $whitelist))
	{
		exit('Invalid file!');
	}
	if(strpos( $file_url , '.php' ) == true)
	{
		die("Invalid file!");
	}

	$file_new_name = $file_name;
	$content_type = "";
  //check filetype
	switch( $file_extension['extension'] ) {
		case "csv": 
		$content_type="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"; 
		break;
		case "xlsx": 
		$content_type="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"; 
		break;
		
		default: 
		$content_type="application/force-download";
	}

	$content_type = apply_filters( "ibenic_content_type", $content_type, $file_extension['extension'] );

	header("Expires: 0");
	header("Cache-Control: no-cache, no-store, must-revalidate"); 
	header('Content-Transfer-Encoding: binary');
	header('Cache-Control: pre-check=0, post-check=0, max-age=0', false); 
	header("Pragma: no-cache");	
	header("Content-type: {$content_type}");
	header("Content-Disposition:attachment; filename={$file_new_name}");
	header("Content-Type: application/force-download");

	readfile(ABSPATH.WP_STOCK_PATH."/".$file);
	exit();
}

// end
include dirname( __File__ ) . "/includes/class-stock.php";
include dirname( __File__ ) . "/includes/class-stock-list-table.php";
include dirname( __File__ ) . "/includes/class-form-handler.php";
include dirname( __File__ ) . "/includes/stock-functions.php";

new Stock();