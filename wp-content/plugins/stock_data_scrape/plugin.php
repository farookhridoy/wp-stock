<?php
/*
   Plugin Name: Stock Data Scrape
   description: A plugin to create admin sideber
   Version: 1.0
   Author: Michal
   Author URI: kontakt@uxdev.pl
*/

//create data-table for scrapping data
   register_activation_hook( __FILE__, 'activate_scrap_plugin_function' );
   register_deactivation_hook( __FILE__, 'deactivate_scrap_plugin_function' );

   function activate_scrap_plugin_function() {
      global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $table_name = 'wp_stock_scrap';

        $sql = "CREATE TABLE $table_name (
        `id` bigint(11) unsigned NOT NULL AUTO_INCREMENT,
        `company_name` varchar(255) UNIQUE,
        `market_symbol` varchar(255),
        `company_symbol` varchar(255) UNIQUE,
        `consensus_rating` varchar(255),
        `consensus_rating_score` varchar(255),
        `ratings_breakdown` varchar(255),
        `consensus_price_target` varchar(255),
        `price_target_upside` varchar(255),
        `status` varchar(255),
        `created_at` varchar(255),
        `updated_at` varchar(255),
        PRIMARY KEY  (id)
      ) $charset_collate;";

      require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
      dbDelta( $sql );
  }

  function deactivate_scrap_plugin_function() {
    global $wpdb;
    $table_name = 'wp_stock_scrap';
    $sql = "DROP TABLE IF EXISTS $table_name";
    $wpdb->query($sql);
  }

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


$limit=100; //set global limit

include dirname( __File__ ) . "/includes/class-stock.php";
include dirname( __File__ ) . "/includes/class-stock-list-table.php";
include dirname( __File__ ) . "/includes/class-scrap-list-table.php";
include dirname( __File__ ) . "/includes/class-form-handler.php";
include dirname( __File__ ) . "/includes/stock-functions.php";

new Stock();

