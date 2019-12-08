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


// end
include dirname( __File__ ) . "/includes/class-stock.php";
include dirname( __File__ ) . "/includes/class-stock-list-table.php";
include dirname( __File__ ) . "/includes/class-form-handler.php";
include dirname( __File__ ) . "/includes/stock-functions.php";

new Stock();