<?php

/**
 * Admin Menu
 */
class Stock {

    /**
     * Kick-in the class
     */
    public function __construct() {
        add_action( 'admin_menu', array( $this, 'admin_menu' ) );

        define('Stock_PLUGIN_PATH', plugin_dir_path( __FILE__ ));
    }

    /**
     * Add menu items
     *
     * @return void
     */
    public function admin_menu() {

        /** Top Menu **/
        add_menu_page( 'Stock', 'Stock', 'manage_options', 'stock', array( $this, 'plugin_page' ), 'dashicons-groups', null );

        add_submenu_page( 'stock', 'Stock', 'Stock', 'manage_options', 'stock', array( $this, 'plugin_page' ) );
        add_submenu_page( 'stock', 'Stock', 'Scraping', 'manage_options', 'stock-scraping', array( $this, 'scrping_page' ) );
       
        add_submenu_page( 'stock', 'Stock', 'Upload stock list', 'manage_options', 'upload-stock-file-list', array( $this, 'upload_stock_file_list' ) );
    }

    /**
     * Handles the plugin page
     *
     * @return void
     */
    public function plugin_page() {
        global $wpdb;
        
        $action = isset( $_GET['action'] ) ? $_GET['action'] : 'list';
        $id     = isset( $_GET['id'] ) ? intval( $_GET['id'] ) : 0;

        $file     = isset( $_GET['file'] ) ? $_GET['file'] : null;

       

        switch ($action) {
          

           

            case 'delete':

                $delete_return = stock_delete_stock($id);
                
                $template = dirname( __FILE__ ) . '/views/stock-list.php';
                break;

            case 'view':

                $template = dirname( __FILE__ ) . '/views/stock-single.php';
                break;

            case 'edit':
                $template = dirname( __FILE__ ) . '/views/stock-edit.php';
                break;

            case 'new':
                $template = dirname( __FILE__ ) . '/views/stock-new.php';
                break;

            default:
                $template = dirname( __FILE__ ) . '/views/stock-list.php';
                break;
        }

        if ( file_exists( $template ) ) {
            include $template;
        }

    }

    function scrping_page() {
        //ob_start(); ini_set('max_execution_time', 0);  set_time_limit(0); ignore_user_abort(true);
         ini_set('max_execution_time', 0);  ignore_user_abort(true);

         include dirname( __FILE__ ) . '/views/scrap-data-list-duplicate.php';

        echo ob_get_clean();
    }

   
    function upload_stock_file_list(){
      //ob_start(); ini_set('max_execution_time', 0);  set_time_limit(0); ignore_user_abort(true);
       ini_set('max_execution_time', 0); ignore_user_abort(true);

      include dirname( __FILE__ ) . '/views/upload-stock-file-list.php';

      echo ob_get_clean();
    }
}