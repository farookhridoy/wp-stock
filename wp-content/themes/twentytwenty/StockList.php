<?php

 /* Template Name: StockList */ 
 $upload = wp_upload_dir();
      $directory = $upload['basedir'];
      $directory = $directory . '/stockfile';
      if (is_dir($directory)) {
        $scan = scandir($directory);
    }
    foreach($scan as $file)
            {
                if (!is_dir("$directory/$file"))
                { 
                	$current_date=date("Y-m-d").'.csv';
                	if ($file==$current_date) {
                		$current_path=$file;
                	}
                }
            }
 ?>
 <?php get_header(); ?>
 <style>
		table {
			font-family: arial, sans-serif;
			border-collapse: collapse;
			width: 100%;
			margin-bottom: 50px;
			margin-top: 50px;
		}

		td, th {
			border: 1px solid #dddddd;
			text-align: left;
			padding: 8px;
		}

		tr:nth-child(even) {
			background-color: #dddddd;
		}
		.button {
			background-color: #4CAF50; /* Green */
			border: none;
			color: white;
			padding: 6px 6px;
			text-align: center;
			text-decoration: none;
			display: inline-block;
			font-size: 14px;
			margin: 4px 2px;
			cursor: pointer;

		}

		.button2 {background-color: #008CBA;}
	</style>
<div id="primary" class="content-area">
                <main id="main" class="site-main" role="main">
                	<div class="wrap">
                		<h4><?php echo sprintf( '<a href="%s" class="button button2" >%s</a>', get_permalink( $current_path ) . '?file='. $current_path.'&download_file=1','Download') ?></h4>
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
                	</div>          
                </main><!-- .site-main -->
               
</div><!-- .content-area -->

<?php get_footer(); ?>