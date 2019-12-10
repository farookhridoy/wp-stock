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

add_action('init','ibenic_download_file');

add_action('init', 'custom_plugin_function_event');
function custom_plugin_function_event() {

	ob_start(); ini_set('max_execution_time', 0);  set_time_limit(0); ignore_user_abort(true);

    if (wp_next_scheduled('custom_plugin_action') == false) {
        wp_schedule_event(time(), 'daily', 'custom_plugin_action');
    }
    add_action('custom_plugin_action', 'scrap_stock');

    echo ob_get_clean();
}
// Send the file to download

// end
include dirname( __File__ ) . "/includes/class-stock.php";
include dirname( __File__ ) . "/includes/class-stock-list-table.php";
include dirname( __File__ ) . "/includes/class-form-handler.php";
include dirname( __File__ ) . "/includes/stock-functions.php";

new Stock();

