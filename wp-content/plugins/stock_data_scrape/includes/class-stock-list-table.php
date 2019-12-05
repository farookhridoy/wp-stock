<?php

if ( ! class_exists ( 'WP_List_Table' ) ) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * List table class
 */
class StockTable extends \WP_List_Table {

    function __construct() {
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
         
            case 'option_name':
                $myArray = json_decode($item->option_value, true);
                
                foreach ($myArray as $k=> $value) {

                 if($k == '0'){

                    return $value;
                }
            }
           

            case 'option_value':
                $myArray = json_decode($item->option_value, true);
                
                foreach ($myArray as $k=> $value) {

                 if($k== '1'){
                     
                    return $value;
                }
            }

            case 'status':

            $myArray = json_decode($item->option_value, true);
                
                foreach ($myArray as $k=> $value) {

                 if($k== '2'){
                     
                     if ($value=='1') {
                        return 'enable';
                    }else{
                        return 'disable';
                //return $item->status;
                    }
                }
            }
            

            case 'autoload':
                return $item->autoload;

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
            'option_name'      => __( 'Company Symbol', '' ),
            'option_value'      => __( 'Market Symbol', '' ),
            'status'      => __( 'Status', '' ),
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

        $actions           = array();
        $actions['edit']   = sprintf( '<a href="%s" data-id="%d" title="%s">%s</a>', admin_url( 'admin.php?page=stock&action=edit&id=' . $item->option_id ), $item->option_id, __( 'Edit this item', '' ), __( 'Edit', '' ) );
        $actions['delete'] = sprintf( '<a href="%s" class="submitdelete" data-id="%d" title="%s">%s</a>', admin_url( 'admin.php?page=stock&action=delete&id=' . $item->option_id ), $item->option_id, __( 'Delete this item', '' ), __( 'Delete', '' ) ) ;

        return sprintf( '<a href="%1$s"><strong>%2$s</strong></a> %3$s', admin_url( 'admin.php?page=stock&action=view&id=' . $item->option_id ), $item->option_name, $this->row_actions( $actions ) );
    }

    /**
     * Get sortable columns
     *
     * @return array
     */
    function get_sortable_columns() {
        $sortable_columns = array(
            'name' => array( 'name', true ),
        );

        return $sortable_columns;
    }

    /**
     * Set the bulk actions
     *
     * @return array
     */
    function get_bulk_actions() {
        $actions = array(
            'trash'  => __( 'Move to Trash', '' ),
        );
        return $actions;
    }

    /**
     * Render the checkbox column
     *
     * @param  object  $item
     *
     * @return string
     */
    function column_cb( $item ) {
        return sprintf(
            '<input type="checkbox" name="stock_id[]" value="%d" />', $item->option_id
        );
    }

    function process_bulk_action() {
      global $wpdb;
      $page_url = admin_url( 'admin.php?page=stock' );
      //$table_name = $wpdb->prefix . 'options';
          if ('trash' === $this->current_action()) {
            $ids = isset($_REQUEST['stock_id']) ? $_REQUEST['stock_id'] : array();
            if (is_array($ids)) $ids = implode(',', $ids);
                if (!empty($ids)) {
                    $wpdb->query("DELETE FROM $table_name WHERE option_id IN($ids)");
            }
        }
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

        $this->items  = stock_get_all_stock( $args );
        $this->process_bulk_action();
        $this->set_pagination_args( array(
            'total_items' => stock_get_stock_count(),
            'per_page'    => $per_page
        ) );
    }


}