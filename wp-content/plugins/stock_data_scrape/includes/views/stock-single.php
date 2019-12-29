<div class="wrap">
    <h1>View Stock <a href="<?php echo admin_url( 'admin.php?page=stock' ); ?>" class="add-new-h2"><?php _e( 'Back To List', '' ); ?></a></h1>

    <?php
         $item = stock_get_stock( $id ); 
             

    ?>

    <form action="" method="post">

        <table class="form-table">
            <tbody>
                <tr class="row-option-name">
                    <th scope="row">
                        <label for="company_symbol">Company Symbol</label>
                    </th>
                    <td>
                      <input type="text" name="company_symbol" id="company_symbol" class="regular-text" placeholder="<?php echo esc_attr( '', '' ); ?>" value="<?php echo esc_attr( $item->company_symbol ); ?>" readonly />
                    </td>
                </tr>
                <tr class="row-option-value">
                    <th scope="row">
                        <label for="market_symbol">Market Symbol</label>
                    </th>
                    <td>
                       <input type="text" name="option_name" id="option_name" class="regular-text" placeholder="<?php echo esc_attr( '', '' ); ?>" value="<?php echo esc_attr( $item->market_symbol ); ?>" readonly />
                    </td>
                </tr>
                
                <tr class="row-status">
                    <th scope="row">
                        <label for="status">Status</label>
                    </th>
                    <td>

                        <?php if ($item->status =='1') {
                           echo 'enable';
                        }else{
                            echo 'disable';
                        } 
                        ?>
                        
                    </td>
                </tr>
             </tbody>
        </table>
    </form>
</div>