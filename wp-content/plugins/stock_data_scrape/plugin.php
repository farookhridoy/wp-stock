<?php
/*
   Plugin Name: Stock Data Scrape
   description: A plugin to create admin sideber
   Version: 1.0
   Author: GM Hridoy
   Author URI: https://github.com/gmfaruk
*/



 $upload = wp_upload_dir();
 $upload_dir = $upload['basedir'];
 $upload_dir = $upload_dir . '/stockfile';
 if (! is_dir($upload_dir)) {
    mkdir( $upload_dir, 0700 );
 }

if(!defined('WP_STOCK_PATH')) 
define('WP_STOCK_PATH',"wp-content/uploads/stockfile");

add_action('init','ibenic_download_file');

  
add_filter( 'cron_schedules', 'cron_add_per_minute' );

function cron_add_per_minute( $schedules ) {
// Adds once weekly to the existing schedules.
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

/*function update_reservation_function() {
  ob_start(); ini_set('max_execution_time', 0);  set_time_limit(0); ignore_user_abort(true);
  include  dirname( __FILE__ ) . '/views/scrap-csv-file-list.php';
  echo ob_get_clean();
}*/
// Send the file to download

// end
include dirname( __File__ ) . "/includes/class-stock.php";
include dirname( __File__ ) . "/includes/class-stock-list-table.php";
include dirname( __File__ ) . "/includes/class-form-handler.php";
include dirname( __File__ ) . "/includes/stock-functions.php";

new Stock();

