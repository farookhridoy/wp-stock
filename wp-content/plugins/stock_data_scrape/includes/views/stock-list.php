<div class="wrap">
    <h2><?php _e( 'Stock List', '' ); ?> <a href="<?php echo admin_url( 'admin.php?page=stock&action=new' ); ?>" class="add-new-h2"><?php _e( 'Add New', '' ); ?></a></h2>

        <?php
            $list_table = new StockTable();
            $list_table->prepare_items();
        ?>
        <form id="entry-table" method="post">
            <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>"/>
            <?php $list_table->search_box( 'search', 'search_id' ); $list_table->display() ?>
        </form>
</div>