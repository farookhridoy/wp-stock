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

function stock_scrap_delete($file){
    
    if($file){             
        @unlink(ABSPATH.WP_STOCK_PATH."/".$file);
        return true;
    }
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

    if ( $row_id ) {
        // do update method here
        if ( $wpdb->update( $table_name, $args, array( 'option_id' => $row_id ) ) ) {
            return $row_id;
            
        }
    }

    return false;
}

// Start the download if there is a request for that
function ibenic_download_file(){
   ob_start(); ini_set('max_execution_time', 0);  set_time_limit(0); ignore_user_abort(true);

  if( isset( $_GET["file"] ) && isset( $_GET['download_file'] ) ) {
        ibenic_send_file();
    }

    echo ob_get_clean();
}
function ibenic_send_file(){
    //get filedata
    $file = $_GET['file'];
    $uploads = wp_get_upload_dir();
    $theFile = $uploads['baseurl'] . "/stockfile/".$file;

    if( ! $theFile ) {
        return;
    }
  //clean the fileurl
    $file_url  = stripslashes( trim( $theFile ) );
  //get filename
    $file_name = basename( $theFile );
  //get fileextension

    $file_extension = pathinfo($file_name);
  //security check
    $fileName = strtolower($file_url);

    $whitelist = apply_filters( "ibenic_allowed_file_types", array('csv','xlsx') );

    if(!in_array(end(explode('.', $fileName)), $whitelist))
    {
        exit('Invalid file!');
    }
    if(strpos( $file_url , '.php' ) == true)
    {
        die("Invalid file!");
    }

    $file_new_name = $file_name;
    $content_type = "";
  //check filetype
    switch( $file_extension['extension'] ) {
        case "csv": 
        $content_type="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"; 
        break;
        case "xlsx": 
        $content_type="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"; 
        break;
        
        default: 
        $content_type="application/force-download";
    }

    $content_type = apply_filters( "ibenic_content_type", $content_type, $file_extension['extension'] );

    header("Expires: 0");
    header("Cache-Control: no-cache, no-store, must-revalidate"); 
    header('Content-Transfer-Encoding: binary');
    header('Cache-Control: pre-check=0, post-check=0, max-age=0', false); 
    header("Pragma: no-cache"); 
    header("Content-type: {$content_type}");
    header("Content-Disposition:attachment; filename={$file_new_name}");
    header("Content-Type: application/force-download");

    readfile(ABSPATH.WP_STOCK_PATH."/".$file);
    exit();
}

function scrap_stock(){
        global $wpdb;

        $upload = wp_upload_dir();
        $upload_dir = $upload['basedir'];
        $upload_dir = $upload_dir . '/stockfile';
        if (is_dir($upload_dir)) {

        $logfile = $upload_dir . '/'.date("Y-m-d").'.csv';
        $fp = fopen($logfile, 'w');
        

        //$csv = "Company Symbol,Market Symbol,Key,Value \n";//Column headers
        $alldata = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'options WHERE autoload="stock"');
            $counter=0;
            $csv=[];
            $parentcsv=[];
            foreach ($alldata as $item) {

                $myArray = json_decode($item->option_value, true);
                $option_name=null; $option_value=null; $status=null;
                foreach ($myArray as $k=> $value) {
                    if($k == 'option_name'){
                        $option_name = $value;
                    }
                    if($k == 'option_value'){
                        $option_value = $value;
                    }
                    if($k == 'status'){
                        $status = $value;
                    }
                }
                if ($status=='1') {
                    
                        $ret=scrapping_url('https://www.marketbeat.com/stocks/'.$option_value.'/'.$option_name.'/price-target/');

                        if ($ret) {
                            
                        echo '<table class="wp-list-table widefat fixed striped stocks">';
                        echo'<thead><tr>';
                        if ($counter<1) {


                            foreach($ret as $k=>$v){

                                echo'<th class="column-primary"><strong>'. strip_tags($k) .'</strong></th>';
                            }

                            echo '</tr></thead>';
                        }

                        echo' <tbody id="the-list">';
                        echo'<tr>';
                            foreach($ret as $k=>$v){
                                echo'<td>'. strval($v) . '</td>';
                                $Key=strip_tags($k);
                                $Val=strip_tags($v);
                                $csv[$Key] = $Val;

                                //$csv.= $option_name.','.$option_value.','.strip_tags($k).','.strip_tags($v)."\n";
                            }

                           echo  '</tr>';
                        echo' </tbody></table>';
                        }

                    }
                        $parentcsv[]=$csv;
                    $counter++;
                }

                foreach ($parentcsv as $record)
                {
                    $record_arr = array();

                    foreach ($record as $value)
                    {
                        $record_arr[] = $value;
                    }

                    if($i == 0)
                    {
                      fputcsv($fp, array_keys((array)$record));
                  }
                  fputcsv($fp, array_values($record_arr));

                  $i++;
              }



                //fwrite($fp, $ret);

                fclose($fp);
            }

    }    

    function scrapping_url($url) {

        require_once(Stock_PLUGIN_PATH.'/scrapingfile/simple_html_dom.php');
        //make dir and make csv file

        $html = file_get_html($url);

        $title =$html->find('h3[class="d-inline-block m-0"]',0);
        //for company and market symbole//
        preg_match('#\((.*?)\)#', $title, $match);
        $match[1];
        $exploded = explode(':', $match[1]);
        $CompanySymbol=$exploded[1];
        $MarketSymbol= $exploded[0];
        //for company and market symbole//
        $ret['Symbol']=$MarketSymbol.'/'.$CompanySymbol;
        $key = '';
        $val = '';
        $flag=0;
        foreach($html->find('table[class="bluetable"] tr') as $row) {
            $key = $row->find('td', 0);
            $k=strip_tags($key);

            if ($flag>0) {
                $val=$row->find('td', 1);

                 $val = strip_tags($val);
                $ret[$k] = $val;
            }
            $flag++;
        }

        return $ret;
        
        // clean up memory

        $html->clear();
        unset($html);

    }

    function scrap_stock_fun(){

            $upload = wp_upload_dir();
            $directory = $upload['basedir'];
            $directory = $directory . '/stockfile';
            $path = $directory . '/'.date("Y-m-d").'.csv';
            $handle = fopen($path, "r");

            echo '<table class="wp-list-table widefat fixed striped stocks">';

            if ($header) {
                $csvcontents = fgetcsv($handle);
                echo'<thead><tr>';
                foreach ($csvcontents as $headercolumn) {

                    echo'<th class="column-primary"><strong>$headercolumn</strong></th>';
                }
                echo '</tr></thead>';
            }

            while ($csvcontents = fgetcsv($handle)) {
             echo' <tbody id="the-list">';
             echo'<tr>';
             foreach ($csvcontents as $column) {
               echo "<td>$column</td>";
           }
           echo  '</tr>';

       }
       echo' </tbody></table>';

       fclose($handle);
   } 