<div class="wrap">
    <h1>View Stock <a href="<?php echo admin_url( 'admin.php?page=stock' ); ?>" class="add-new-h2"><?php _e( 'Back To List', '' ); ?></a></h1>

    <?php $item = stock_get_stock( $id ); ?>

    <form action="" method="post">

        <table class="form-table">
            <tbody>
                <tr class="row-option-name">
                    <th scope="row">
                        <label for="option_name">Symbol Key</label>
                    </th>
                    <td>
                       <?php echo esc_attr( $item->option_name ); ?>
                    </td>
                </tr>
                <tr class="row-option-value">
                    <th scope="row">
                        <label for="option_value"><?php _e( 'Exchange Name', '' ); ?></label>
                    </th>
                    <td>
                        <?php echo esc_attr( $item->option_value ); ?>
                    </td>
                </tr>
                
                <tr class="row-status">
                    <th scope="row">
                        <label for="status">Status</label>
                    </th>
                    <td>

                        <?php if ($item->status=='1') {
                           echo 'enable';
                        }else{
                            echo 'disable';
                        } ?>
                        
                    </td>
                </tr>
             </tbody>
        </table>
    </form>
</div>