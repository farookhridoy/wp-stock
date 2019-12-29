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
        'orderby'    => 'id',
        'order'      => 'ASC',
    );

    $args      = wp_parse_args( $args, $defaults );
    $cache_key = 'stock-all';
    $items     = wp_cache_get( $cache_key, '' );

    if ( false === $items ) {
        $items = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'stock_scrap  ORDER BY ' . $args['orderby'] .' ' . $args['order'] .' LIMIT ' . $args['offset'] . ', ' . $args['number'] );

        wp_cache_set( $cache_key, $items, '' );
    }

    return $items;
}


function stock_search_data($data){
    global $wpdb;
    

    $args = array(
        'number'     => 20,
        'offset'     => 0,
        'orderby'    => 'id',
        'order'      => 'ASC',
    );

    $cache_key = 'stock-all';
    $items     = wp_cache_get( $cache_key, '' );

    if ( false === $items ) {
        $items = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'stock_scrap WHERE status="1" AND `company_symbol` LIKE "%'.$data.'%"  ORDER BY ' . $args['orderby'] .' ' . $args['order'] .' LIMIT ' . $args['offset'] . ', ' . $args['number'] );

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

    return (int) $wpdb->get_var( 'SELECT COUNT(*) FROM ' . $wpdb->prefix . 'stock_scrap' );
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

    return $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'stock_scrap WHERE id = %d', $id ) );
}

function stock_delete_stock( $id = 0 ) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'stock_scrap';
    
    return $wpdb->delete( $table_name, array( 'id' => $id ) );

}

function stock_insert_stock( $args = array() ) {
    global $wpdb;

    $defaults = array(
        'id' => null,
        'company_symbol' => '',
        'market_symbol' => '',
        'status' => '',
        'created_at' => '',
        

    );

    $args       = wp_parse_args( $args, $defaults );
    $table_name = $wpdb->prefix . 'stock_scrap';

    // some basic validation
    if ( empty( $args['company_symbol'] ) ) {
        return new WP_Error( 'no-company_symbol', __( 'No Company Symbol provided.', '' ) );
    }
    if ( empty( $args['market_symbol'] ) ) {
        return new WP_Error( 'no-market_symbol', __( 'No Market Symbol provided.', '' ) );
    }

    // remove row id to determine if new or update
    $row_id = (int) $args['id'];
    unset( $args['id'] );

    if ( $row_id ) {
        // do update method here
        if ( $wpdb->update( $table_name, $args, array( 'id' => $row_id ) ) ) {
            return $row_id;
            
        }
    }

    return false;
}

// Start the download if there is a request for that
function ibenic_download_file(){
   //ob_start(); ini_set('max_execution_time', 0);  set_time_limit(0); ignore_user_abort(true);
    ini_set('max_execution_time', 0); ignore_user_abort(true);
  if( isset( $_GET["file"] ) && isset( $_GET['download_file'] ) ) {
        ibenic_send_file();
    }

    echo ob_get_clean();
}
function ibenic_send_file(){
    global $wpdb;
    $file = $_GET['file'];
    $content_type="application/force-download";
    $file_new_name= $file.'.csv';
    header("Expires: 0");
    header("Cache-Control: no-cache, no-store, must-revalidate"); 
    header('Content-Transfer-Encoding: binary');
    header('Cache-Control: pre-check=0, post-check=0, max-age=0', false); 
    header("Pragma: no-cache"); 
    header("Content-type: {$content_type}");
    header("Content-Disposition:attachment; filename={$file_new_name}");
    header("Content-Type: application/force-download");
    

    $delimiter = ",";
    //create a file pointer
    $fp = fopen('php://output', 'w');
    //set column headers

    $header_row = array(
        'Company Name',
        'MarketSymbol',
        'Company Symbol',
        'Consensus Rating',
        'Consensus Rating Score',
        'Ratings Breakdown',
        'Consensus Price Target',
        'Price Target Upside',
        'Last Updated',
   );

    fputcsv($fp, $header_row, $delimiter);
        $Table_Name   = $wpdb->prefix . 'stock_scrap'; 
        $sql_query    = $wpdb->prepare("SELECT * FROM $Table_Name", 1) ;
        $rows         = $wpdb->get_results($sql_query, ARRAY_A);


    if(!empty($rows)) 
    {
        foreach($rows as $Record)
        {  
          $OutputRecord = array($Record['company_name'],
              $Record['market_symbol'],
              $Record['company_symbol'],
              $Record['consensus_rating'],
              $Record['consensus_rating_score'],
              $Record['ratings_breakdown'],
              $Record['consensus_price_target'],
              $Record['price_target_upside'],
              $Record['updated_at']
          );  
          fputcsv($fp, $OutputRecord,$delimiter);       
      }

      
  }


     fpassthru($fp);
    exit;         
}

function scrap_stock(){
    global $wpdb,$limit;
    $table_name = $wpdb->prefix .'stock_scrap';

    $offset=0;
    $total_items = $wpdb->get_var("SELECT COUNT(id) FROM $table_name");
    $totalPages = ceil($total_items / $limit);

    for ($i = 0; $i <= $totalPages; $i++)
    {
        $alldata = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'stock_scrap ORDER BY id ASC LIMIT ' . $offset . ', ' . $limit );

         foreach ($alldata as $item) {

            if ($item->status=='1') {
                $ret=scrapping_url('https://www.marketbeat.com/stocks/'.$item->market_symbol.'/'.$item->company_symbol.'/price-target/');

                if ($ret) {
                    $company_symbol=''; $updated_at='';

                    foreach($ret as $k=>$v){ 

                        if ($k=='company_symbol') {
                            $company_symbol= $v;
                        }
                        if ($k=='updated_at') {
                            $updated_at= $v;
                        }
                    }
                    // New or edit?
                    if ( $item->company_symbol==$company_symbol && $item->updated_at==$updated_at ) {

                    }else{
                        $ret['id'] = $item->id;

                        $insert_id = $wpdb->update( $table_name, $ret, array( 'id' => $item->id ) );
                    } 

                }//end ret
            }//end status check

        }//end main foreach

        $offset=$offset+$limit;

    } //end for loop
      
}

    function scrapping_url($url) {

        require_once(Stock_PLUGIN_PATH.'/scrapingfile/simple_html_dom.php');
        $html_content = wp_remote_get($url);

        $body = $html_content['body'];
        $html = str_get_html($body);

        if (!empty($html)){

            $title =$html->find('h3[class="d-inline-block m-0"]',0);
            preg_match('#\((.*?)\)#', $title, $match);
            $match[1];
            $exploded = explode(':', $match[1]);
            $CompanySymbol=$exploded[1];
            $MarketSymbol= $exploded[0];
            $companyName=current(explode(' ', strip_tags($title)));
            //for company and market symbole//
            $ret['company_name']=$companyName;
            $ret['market_symbol']=$MarketSymbol;
            $ret['company_symbol']=$CompanySymbol;

            $key = ''; $val = ''; $flag=0;

            foreach($html->find('table[class="scroll-table"] tr') as $row) {
                $key = $row->find('td', 0); 
                $k=strip_tags($key); 
                $fk=slugify($k);

                if ($flag>0) {
                    $val=$row->find('td', 1);
                    $val = strip_tags($val);
                    $ret[$fk] = $val;
                }
                $flag++;
            }
            
            $ret['updated_at']=date("Y-m-d");
            return $ret;
            $html->clear();
        }
        unset($html);
    }
    
    function slugify($text)
    {
        
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, '-');
        $text = preg_replace('~-+~', '_', $text);
        $text = strtolower($text);
        if (empty($text)) {
            return 'n-a';
        }
        return $text;
    }


   // for scrapping data

   function scrap_get_all_stock( $args = array() ) {
    global $wpdb;

    $defaults = array(
        'number'     => 20,
        'offset'     => 0,
        'orderby'    => 'id',
        'order'      => 'ASC',
    );

    $args      = wp_parse_args( $args, $defaults );
    $cache_key = 'scrapp-all';
    $items     = wp_cache_get( $cache_key, '' );

    if ( false === $items ) {
        $items = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'stock_scrap WHERE updated_at !="null" AND company_name !="'.null.'" ORDER BY ' . $args['orderby'] .' ' . $args['order'] .' LIMIT ' . $args['offset'] . ', ' . $args['number'] );

        wp_cache_set( $cache_key, $items, '' );
    }

    return $items;
}


function scrap_search_data($data){
    global $wpdb;
    

    $args = array(
        'number'     => 20,
        'offset'     => 0,
        'orderby'    => 'id',
        'order'      => 'ASC',
    );

    $cache_key = 'scrapp-all';
    $items     = wp_cache_get( $cache_key, '' );

    if ( false === $items ) {
        $items = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'stock_scrap WHERE updated_at !="null" AND company_name LIKE "%'.$_POST['s'].'%" ' );

        wp_cache_set( $cache_key, $items, '' );
    }

    return $items;
}
