<div class="wrap">
    <h1><?php _e( 'Edit Current Data', '' ); ?> <a href="<?php echo admin_url( 'admin.php?page=stock' ); ?>" class="add-new-h2"><?php _e( 'Back To List', '' ); ?></a></h1>

    <?php
         $item = stock_get_stock( $id ); 
             $myArray = json_decode($item->option_value, true);
             $option_name=null;
             $option_value=null;
             $status=null;
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

    ?>

    <form action="" method="post">

        <table class="form-table">
            <tbody>
                <tr class="row-option-name">
                    <th scope="row">
                        <label for="option_name">Company Symbol</label>
                    </th>
                    <td>
                        <input type="text" name="option_name" id="option_name" class="regular-text" placeholder="<?php echo esc_attr( '', '' ); ?>" value="<?php echo esc_attr( $option_name ); ?>" required="required" />
                        <span class="description">Company Symbol Name As Like "AAPL"</span>
                    </td>
                </tr>
                <tr class="row-option-value">
                    <th scope="row">
                        <label for="option_value">Market Symbol</label>
                    </th>
                    <td>
                        <input type="text" name="option_value" id="option_value" class="regular-text" placeholder="<?php echo esc_attr( '', '' ); ?>" value="<?php echo esc_attr( $option_value ); ?>" required="required" />
                        <span class="description">Market Symbol Name as Like "NASDQ"</span>
                    </td>
                </tr>
                <input type="hidden" name="autoload" id="autoload" class="regular-text" placeholder="<?php echo esc_attr( '', '' ); ?>" value="<?php echo esc_attr( $item->autoload ); ?>" />
                <tr class="row-status">
                    <th scope="row">
                        <label for="status">Status</label>
                    </th>
                    <td>
                        <select name="status" id="status">
                            <option value="1" <?php selected( $status, '1' ); ?>><?php echo __( 'enable', '' ); ?></option>
                            <option value="0" <?php selected( $status, '0' ); ?>><?php echo __( 'disable', '' ); ?></option>
                        </select>
                        <span class="description">For Disable and Enable Row</span>
                    </td>
                </tr>
             </tbody>
        </table>

        <input type="hidden" name="field_id" value="<?php echo $item->option_id; ?>">

        <?php wp_nonce_field( 'add_new_stock' ); ?>
        <?php submit_button( __( 'Update Stock Data', '' ), 'primary', 'submit_stock' ); ?>

    </form>
</div>