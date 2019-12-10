<div class="wrap">
    <h2><?php _e( 'Stock List', '' ); ?> <a href="<?php echo admin_url( 'admin.php?page=stock&action=new' ); ?>" class="add-new-h2"><?php _e( 'Add New', '' ); ?></a></h2>

    <form method="post">
        <input type="hidden" name="page" value="ttest_list_table">
        
        <?php
            $list_table = new StockTable();
            $list_table->prepare_items();
            $list_table->search_box( 'search', 'search_id' );
            $list_table->display();

            //echo '<pre>'; print_r( _get_cron_array() ); echo '</pre>';
        ?>
    </form>
</div>