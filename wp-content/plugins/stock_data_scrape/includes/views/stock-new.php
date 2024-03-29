<div class="wrap">
    <h1><?php _e( 'Add New Data', '' ); ?> <a href="<?php echo admin_url( 'admin.php?page=stock' ); ?>" class="add-new-h2"><?php _e( 'Back To List', '' ); ?></a></h1>

    <form action="" method="post">

        <table class="form-table">
            <tbody>
                <tr class="row-option-name">
                    <th scope="row">
                        <label for="company_symbol">Company Symbol</label>
                    </th>
                    <td>
                        <input type="text" name="company_symbol" id="company_symbol" class="regular-text" placeholder="AAPL" value="" required="required" />
                        <span class="description">Company Symbol Name As Like "AAPL"</span>
                    </td>
                </tr>
                <tr class="row-option-value">
                    <th scope="row">
                        <label for="market_symbol">Market Symbol</label>
                    </th>
                    <td>
                        <input type="text" name="market_symbol" id="market_symbol" class="regular-text" placeholder="NASDQ" value="" required="required" />
                        <span class="description">Market Symbol Name as Like "NASDQ"</span>
                    </td>
                </tr>
                <input type="hidden" name="created_at" id="created_at" value="<?php echo date("Y-m-d") ?>">
                <tr class="row-status">
                    <th scope="row">
                        <label for="status">Status</label>
                    </th>
                    <td>
                        <select name="status" id="status">
                            <option value="1"><?php echo __( 'enable', '' ); ?></option>
                            <option value="0"><?php echo __( 'disable', '' ); ?></option>
                        </select>
                        <span class="description">For Disable and Enable Row</span>
                    </td>
                </tr>
             </tbody>
        </table>

        <input type="hidden" name="field_id" value="0">

        <?php wp_nonce_field( 'add_new_stock' ); ?>
        <?php submit_button( __( 'Add Stock Data', '' ), 'primary', 'submit_stock' ); ?>

    </form>
</div>