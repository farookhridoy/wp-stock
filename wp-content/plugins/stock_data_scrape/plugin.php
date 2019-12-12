<?php
/*
   Plugin Name: Stock Data Scrape
   description: A plugin to create admin sideber
   Version: 1.0
   Author: GM Hridoy
   Author URI: https://github.com/gmfaruk
*/


//for stock file folder

 $upload = wp_upload_dir();
 $upload_dir = $upload['basedir'];
 $upload_dir = $upload_dir . '/stockfile';
 if (! is_dir($upload_dir)) {
    mkdir( $upload_dir, 0700 );
 }


//end

if(!defined('WP_STOCK_PATH')) 
define('WP_STOCK_PATH',"wp-content/uploads/stockfile");



add_action('init','ibenic_download_file'); // for download stock scrap list.
add_filter( 'cron_schedules', 'cron_add_per_minute' ); // for manual cron job link

//cron job function start
function cron_add_per_minute( $schedules ) {
  $schedules['minute'] = array(
    'interval' => 1440,
    'display' => __( 'Once A Daily' )
  );
  return $schedules;
}

if (!wp_next_scheduled('cron_schedules_action')) {
  wp_schedule_event( time(), 'minute', 'cron_schedules_action' );
}
add_action ( 'cron_schedules_action', 'scrap_stock' );
// end cron job funtion

// end
include dirname( __File__ ) . "/includes/class-stock.php";
include dirname( __File__ ) . "/includes/class-stock-list-table.php";
include dirname( __File__ ) . "/includes/class-form-handler.php";
include dirname( __File__ ) . "/includes/stock-functions.php";

new Stock();

