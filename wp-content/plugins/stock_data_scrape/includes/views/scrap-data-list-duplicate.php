
<?php 
if(isset($_POST['add_scrap_data'])){
	$data = scrap_stock();

	if ($data) {
		echo "<h3 style='color: green;'>Scrap data Inserted</h3>";
	}else{
		echo "<h3 style='color: green;'>Scrap data Updated</h3>";

	}
}

?>

<div class="wrap">

	<form action='<?=$_SERVER['REQUEST_URI']; ?>' method="post">
		<input type="submit" name="add_scrap_data" class="button button-primary" value="Add New Scrap Data"> 
		<?php 

		$file=date('Y-m-d');

		echo sprintf( '<a class="button button-info" href="%s" >%s</a>', get_permalink( $file ) . '?file='. $file.'&download_file=1','Download') 
		?>
	</form>

        <?php
            $list_table = new StockScrapTable();
            $list_table->prepare_items();
        ?>

        <form id="entry-table" method="post" action='<?=$_SERVER['REQUEST_URI']; ?>' style="margin-top: 30px;">
			
            <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>"/>
            <?php $list_table->search_box( 'search', 'search_id' ); $list_table->display() ?>

        </form>
</div>