<div class="wrap">
    <h1>View Stock <a href="<?php echo admin_url( 'admin.php?page=stock' ); ?>" class="add-new-h2"><?php _e( 'Back To List', '' ); ?></a></h1>

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
                        <label for="option_name">Symbol Key</label>
                    </th>
                    <td>
                      <input type="text" name="option_name" id="option_name" class="regular-text" placeholder="<?php echo esc_attr( '', '' ); ?>" value="<?php echo esc_attr( $option_name ); ?>" readonly />
                    </td>
                </tr>
                <tr class="row-option-value">
                    <th scope="row">
                        <label for="option_value"><?php _e( 'Exchange Name', '' ); ?></label>
                    </th>
                    <td>
                       <input type="text" name="option_name" id="option_name" class="regular-text" placeholder="<?php echo esc_attr( '', '' ); ?>" value="<?php echo esc_attr( $option_value ); ?>" readonly />
                    </td>
                </tr>
                
                <tr class="row-status">
                    <th scope="row">
                        <label for="status">Status</label>
                    </th>
                    <td>

                        <?php if ($status =='1') {
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