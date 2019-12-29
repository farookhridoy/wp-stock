<?php

if ( ! class_exists ( 'WP_List_Table' ) ) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * List table class
 */
class StockScrapTable extends \WP_List_Table {

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
            
            case 'company_name':
                return $item->company_name;

            case 'symbol':
                return $item->market_symbol.'/'.$item->company_symbol;

            case 'consensus_rating':
                return $item->consensus_rating;
            case 'consensus_rating_score':
                return $item->consensus_rating_score;

            case 'ratings_breakdown':
                return $item->ratings_breakdown;

            case 'consensus_price_target':
                return $item->consensus_price_target;

            case 'price_target_upside':
                return $item->price_target_upside;

            case 'updated_at':
                return $item->updated_at;

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
            'company_name'      => __( 'Company Name', '' ),
            'symbol'      => __( 'Market/Company Symbol', '' ),
            'consensus_rating'      => __( 'Consensus Rating', '' ),
            'consensus_rating_score'      => __( 'Consensus Rating Score', '' ),
            'ratings_breakdown'      => __( 'Ratings Breakdown', '' ),
            'consensus_price_target'      => __( 'Consensus Price Target', '' ),
            'price_target_upside'      => __( 'Price Target Upside', '' ),
            'updated_at'      => __( 'Last Updated', '' ),
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
            'company_name' => array( 'company_name', true ),
            'price_target_upside' => array( 'price_target_upside', true ),
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
            '<input type="checkbox" name="scrap_id[]" value="%d" />', $item->id
        );
    }



    /**
     * Set the views
     *
     * @return array
     */
    public function get_views_() {
        
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

        $total_items = $wpdb->get_var("SELECT COUNT(id) FROM $table_name WHERE updated_at !='null' ");
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

            $this->items= scrap_search_data($_POST['s']);

      } else {

              $this->items  = scrap_get_all_stock( $args );
        }

        $this->set_pagination_args( array(
            'total_items' => $total_items,
            'per_page'    => $per_page
        ) );
    }


}