<?php

/**
 * Handle the form submissions
 *
 * @package Package
 * @subpackage Sub Package
 */
class Form_Handler {

    /**
     * Hook 'em all
     */
    public function __construct() {
        add_action( 'admin_init', array( $this, 'handle_form' ) );
    }

    /**
     * Handle the stock new and edit form
     *
     * @return void
     */
    public function handle_form() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'stock_scrap';

        if ( ! isset( $_POST['submit_stock'] ) ) {
            return;
        }

        if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'add_new_stock' ) ) {
            die( __( 'Are you cheating?', '' ) );
        }

        if ( ! current_user_can( 'read' ) ) {
            wp_die( __( 'Permission Denied!', '' ) );
        }

        $errors   = array();
        $page_url = admin_url( 'admin.php?page=stock' );
        $field_id = isset( $_POST['field_id'] ) ? intval( $_POST['field_id'] ) : 0;

        $company_symbol = isset( $_POST['company_symbol'] ) ? sanitize_text_field( $_POST['company_symbol'] ) : '';
        $market_symbol = isset( $_POST['market_symbol'] ) ? sanitize_text_field( $_POST['market_symbol'] ) : '';
        $status = isset( $_POST['status'] ) ? sanitize_text_field( $_POST['status'] ) : '';
        $created_at = isset( $_POST['created_at'] ) ? sanitize_text_field( $_POST['created_at'] ) : '';

        // some basic validation
        if ( ! $company_symbol ) {
            $errors[] = __( 'Error: Company Symbol Key is required', '' );
        }

        if ( ! $market_symbol ) {
            $errors[] = __( 'Error: Market_symbol Name is required', '' );
        }

        // bail out if error found
        if ( $errors ) {
            $first_error = reset( $errors );
            $redirect_to = add_query_arg( array( 'error' => $first_error ), $page_url );
            wp_safe_redirect( $redirect_to );
            exit;
        }

        $fields = array(
            'company_symbol' => $company_symbol,
            'market_symbol' => $market_symbol,
            'status' => $status,
            'created_at' => $created_at,
            
        );

        // New or edit?
        if ( ! $field_id ) {

            $insert_id = $wpdb->insert( $table_name, $fields );

        } else {

            $fields['id'] = $field_id;

            $insert_id = stock_insert_stock( $fields );
        }

        if ( is_wp_error( $insert_id ) ) {
            $redirect_to = add_query_arg( array( 'message' => 'error' ), $page_url );
        } else {
            $redirect_to = add_query_arg( array( 'message' => 'success' ), $page_url );
        }

        wp_safe_redirect( $redirect_to );
        exit;
    }
}

new Form_Handler();