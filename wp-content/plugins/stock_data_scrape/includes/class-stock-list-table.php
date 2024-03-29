<?php

if ( ! class_exists ( 'WP_List_Table' ) ) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * List table class
 */
class StockTable extends \WP_List_Table {

    function __construct( ) {
       
        parent::__construct( array(
            'singular' => 'stock',
            'plural'   => 'stocks',
            'ajax'     => false
        ) );
    }

    function get_table_classes() {
        return array( 'widefat', 'fixed', 'striped', $this->_args['plural'] );
    }

    /**
     * Message to show if no designation found
     *
     * @return void
     */
    function no_items() {
        _e( 'no item found', '' );
    }

    /**
     * Default column values if no callback found
     *
     * @param  object  $item
     * @param  string  $column_name
     *
     * @return string
     */
    function column_default( $item, $column_name ) {
       

        switch ( $column_name ) {
            
             case 'market_symbol':
                return $item->market_symbol;

            case 'company_symbol':
                return $item->company_symbol;

            case 'created_at':

                return $item->created_at;

            case 'status':
                     
            if ($item->status=='1') {
                return 'enable';
            }else{
                return 'disable';
            } 

            case 'action': 

                echo ' <a href="'.admin_url( 'admin.php?page=stock&action=view&id=' . $item->id ).'">View</a> ||';
                echo ' <a href="'.admin_url( 'admin.php?page=stock&action=edit&id=' . $item->id ).'">Edit</a> ||';
                echo ' <a href="'.admin_url( 'admin.php?page=stock&action=delete&id=' . $item->id ).'">Delete</a>';
                

            default:
                return isset( $item->$column_name ) ? $item->$column_name : '';
        }

    }

    /**
     * Get the column names
     *
     * @return array
     */
    function get_columns() {
        $columns = array(
            'cb'           => '<input type="checkbox" />',
            'company_symbol'      => __( 'Company Symbol', '' ),
            'market_symbol'      => __( 'Market Symbol', '' ),
            'status'      => __( 'Status', '' ),
            'action'      => __( 'Action', '' ),
        );

        return $columns;
    }

    /**
     * Render the designation name column
     *
     * @param  object  $item
     *
     * @return string
     */
    function column_option_name( $item ) {

        
    }

    /**
     * Get sortable columns
     *
     * @return array
     */
    function get_sortable_columns() {
        $sortable_columns = array(
            'company_symbol' => array( 'company_symbol', true )
           
        );

        return $sortable_columns;
    }

    /**
     * Set the bulk actions
     *
     * @return array
     */
  

    /**
     * Render the checkbox column
     *
     * @param  object  $item
     *
     * @return string
     */
    function column_cb( $item ) {
        return sprintf(
            '<input type="checkbox" name="stock_id[]" value="%d" />', $item->id
        );
    }



    /**
     * Set the views
     *
     * @return array
     */
    public function get_views_() {
        $status_links   = array();
        $base_link      = admin_url( 'admin.php?page=sample-page' );

        foreach ($this->counts as $key => $value) {
            $class = ( $key == $this->page_status ) ? 'current' : 'status-' . $key;
            $status_links[ $key ] = sprintf( '<a href="%s" class="%s">%s <span class="count">(%s)</span></a>', add_query_arg( array( 'status' => $key ), $base_link ), $class, $value['label'], $value['count'] );
        }

        return $status_links;
    }

    /**
     * Prepare the class items
     *
     * @return void
     */
    function prepare_items() {
        global $wpdb;

        $table_name = $wpdb->prefix . 'stock_scrap';

        $columns               = $this->get_columns();
        $hidden                = array( );
        $sortable              = $this->get_sortable_columns();
        $this->_column_headers = array( $columns, $hidden, $sortable );

        $per_page              = 20;
        $current_page          = $this->get_pagenum();
        $offset                = ( $current_page -1 ) * $per_page;
        $this->page_status     = isset( $_GET['status'] ) ? sanitize_text_field( $_GET['status'] ) : '2';

        // only ncessary because we have sample data
        $args = array(
            'offset' => $offset,
            'number' => $per_page,
        );

        if ( isset( $_REQUEST['orderby'] ) && isset( $_REQUEST['order'] ) ) {
            $args['orderby'] = $_REQUEST['orderby'];
            $args['order']   = $_REQUEST['order'] ;
        }

       
        if(isset($_POST['s']) && $_POST['s']!='') {
            $this->items= stock_search_data($_POST['s']);    
      } else {

              $this->items  = stock_get_all_stock( $args );
        }

        $this->set_pagination_args( array(
            'total_items' => stock_get_stock_count(),
            'per_page'    => $per_page
        ) );
    }


}
