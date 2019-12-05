<?php

/**
 * Get all stock
 *
 * @param $args array
 *
 * @return array
 */
function stock_get_all_stock( $args = array() ) {
    global $wpdb;

    $defaults = array(
        'number'     => 20,
        'offset'     => 0,
        'orderby'    => 'option_id',
        'order'      => 'ASC',
    );

    $args      = wp_parse_args( $args, $defaults );
    $cache_key = 'stock-all';
    $items     = wp_cache_get( $cache_key, '' );

    if ( false === $items ) {
        $items = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'options WHERE autoload="stock" ORDER BY ' . $args['orderby'] .' ' . $args['order'] .' LIMIT ' . $args['offset'] . ', ' . $args['number'] );

        wp_cache_set( $cache_key, $items, '' );
    }

    return $items;
}

/**
 * Fetch all stock from database
 *
 * @return array
 */
function stock_get_stock_count() {
    global $wpdb;

    return (int) $wpdb->get_var( 'SELECT COUNT(*) FROM ' . $wpdb->prefix . 'options WHERE autoload="stock"' );
}

/**
 * Fetch a single stock from database
 *
 * @param int   $id
 *
 * @return array
 */
function stock_get_stock( $id = 0 ) {
    global $wpdb;

    return $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'options WHERE option_id = %d', $id ) );
}

function stock_delete_stock( $id = 0 ) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'options';
    
    return $wpdb->delete( $table_name, array( 'option_id' => $id ) );

}

function stock_insert_stock( $args = array() ) {
    global $wpdb;

    $defaults = array(
        'option_id' => null,
        'option_name' => '',
        'option_value' => '',
        'autoload' => '',
        'status' => '',

    );

    $args       = wp_parse_args( $args, $defaults );
    $table_name = $wpdb->prefix . 'options';

    // some basic validation
    if ( empty( $args['option_name'] ) ) {
        return new WP_Error( 'no-option_name', __( 'No Symbol Key provided.', '' ) );
    }
    if ( empty( $args['option_value'] ) ) {
        return new WP_Error( 'no-option_value', __( 'No Exchange Name provided.', '' ) );
    }

    // remove row id to determine if new or update
    $row_id = (int) $args['option_id'];
    unset( $args['option_id'] );

    if ( ! $row_id ) {

        $args['date'] = current_time( 'mysql' );

        // insert a new
        if ( $wpdb->insert( $table_name, $args ) ) {
            return $wpdb->insert_id;
        }

    } else {

        // do update method here
        if ( $wpdb->update( $table_name, $args, array( 'option_id' => $row_id ) ) ) {
            return $row_id;
        }
    }

    return false;
}