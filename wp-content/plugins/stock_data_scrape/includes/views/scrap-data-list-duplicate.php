<div class="wrap">
    <h2>Scrap Data List</h2>
    <form method="post">
    <?php 
    $data = scrap_stock();

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
                                echo'<td>'. strip_tags($v) . '</td>';
                                $csv[$k] = $v;

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

?>
</form>
</div>