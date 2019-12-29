<?php 

$absolute_path = __FILE__;
$path_to_file = explode( 'wp-content', $absolute_path );
$path_to_wp = $path_to_file[0];
// Access WordPress
require_once( $path_to_wp . '/wp-load.php' );
//for create a folder in wp-content/uploads/ dir
$data=stock_cron();

function stock_cron(){
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
                $ret=cron_scrapping_url('https://www.marketbeat.com/stocks/'.$item->market_symbol.'/'.$item->company_symbol.'/price-target/');

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

                    if ($insert_id) {
                         echo "Data Inserted Successfully";
                     } 

                }//end ret
            }//end status check

        }//end main foreach

        $offset=$offset+$limit;

    } //end for loop
      
}

    function cron_scrapping_url($url) {

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
                $fk=slugifyCron($k);

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
    
    function slugifyCron($text)
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