<?php 
global $wpdb;

$tablename = $wpdb->prefix."options";
$page_url = admin_url( 'admin.php?page=stock' );

if(isset($_POST['upload_stock_file_list'])){

 $extension = pathinfo($_FILES['import_file']['name'], PATHINFO_EXTENSION);

	//echo $_FILES['import_file']['name'];
	 if(!empty($_FILES['import_file']['name']) && $extension == 'csv'){

	 	$totalInserted = 0;
	 	$totalIskip = 0;
	 	

	 	$csvFile = fopen($_FILES['import_file']['tmp_name'], 'r');

	   

	    // Read file
	    while(($csvData = fgetcsv($csvFile)) !== FALSE){
	    	$csvData = array_map("utf8_encode", $csvData);


	    	$dataLen = count($csvData);


	    	if( !($dataLen == 2) ) continue;


	    	$option_name = trim($csvData[0]);
	    	$option_value = trim($csvData[1]);
	    	


	    	


	    		if(!empty($option_name) && !empty($option_value) ) {

	    			$option_name = isset( $option_name ) ? sanitize_text_field( $option_name ) : '';
	    			$option_value = isset( $option_value ) ? sanitize_text_field( $option_value ) : '';
	    			$autoload = 'stock';
	    			$status = '1';

	    			$fields = array(
	    				'option_name' => '_stock_perse_dom_'.$option_name,
	    				'option_value' => json_encode(array('option_name'=>$option_name,'option_value'=>$option_value,'status' => $status)),
	    				'autoload' => $autoload,
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