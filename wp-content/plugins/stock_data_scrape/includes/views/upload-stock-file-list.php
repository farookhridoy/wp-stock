<?php 
global $wpdb;

$tablename = $wpdb->prefix."stock_scrap";
$page_url = admin_url( 'admin.php?page=stock' );

if(isset($_POST['upload_stock_file_list'])){

 $extension = pathinfo($_FILES['import_file']['name'], PATHINFO_EXTENSION);

	 if(!empty($_FILES['import_file']['name']) && $extension == 'csv'){

	 	$totalInserted = 0;
	 	$totalIskip = 0;
	 	$csvFile = fopen($_FILES['import_file']['tmp_name'], 'r');
	 	// Read file
	    while(($csvData = fgetcsv($csvFile)) !== FALSE){
	    	$csvData = array_map("utf8_encode", $csvData);


	    	$dataLen = count($csvData);


	    	if( !($dataLen == 2) ) continue;


	    	$company_symbol = trim($csvData[0]);
	    	$market_symbol = trim($csvData[1]);

	    	if(!empty($company_symbol) && !empty($market_symbol) ) {

	    		$company_symbol = isset( $company_symbol ) ? sanitize_text_field( $company_symbol ) : '';
	    		$market_symbol = isset( $market_symbol ) ? sanitize_text_field( $market_symbol ) : '';
	    		$status = '1';
	    		$created_at = date("Y-m-d");

	    		$fields = array(
	    			'company_symbol' => $company_symbol,
	    			'market_symbol' => $market_symbol,
	    			'status' => $status,
	    			'created_at' => $created_at,

	    		);

	    		$insert_id=$wpdb->insert($tablename, $fields);

	    		if($wpdb->insert_id > 0){
	    			$totalInserted++;
	    		}
	    		if($wpdb->insert_id == 0){
	    			$totalIskip++;
	    		}

	    			
	    	}
	    }

	    if ($totalInserted >0) {

	    echo "<h3 style='color: green;'>Total record Inserted : ".$totalInserted."</h3>";
	    	
	    }

	    if ($totalIskip > 0) {
	    	
	    echo "<h3 style='color: RED;'>Total record skipped for duplication : ".$totalIskip."</h3>";
	    }
	}else{
		echo "<h3 style='color: red;'>Invalid Extension</h3>";
	}
}
?>
<div class="wrap">
	<h1><?php _e( 'Back To List', '' ); ?> <a href="<?php echo admin_url( 'admin.php?page=stock' ); ?>" class="add-new-h2"><?php _e( 'Back To List', '' ); ?></a></h1>

	<form method='post' action='<?=$_SERVER['REQUEST_URI']; ?>' enctype='multipart/form-data'>

		<table class="form-table">
			<tbody>
				<tr class="row-option-name">
					<th scope="row">
						<label for="stock_csv_file">Select File</label>
					</th>
					<td>
						<input type="file" name="import_file" id="import_file" class="add-new-h2"   required="required" />

						<span class="description">Upload stock list file (CSV format).</span>
					</td>
				</tr>
			</tbody>
		</table>
		<input type="submit" name="upload_stock_file_list" class="button button-primary" value="Import Stock List">
		

	</form>
</div>