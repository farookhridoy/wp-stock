<div class="wrap">
    <h2>Scrap Data List</h2>
    <form method="post">
    <?php 
        $upload = wp_upload_dir();
        $directory = $upload['basedir'];
        $directory = $directory . '/stockfile';
        if (is_dir($directory)) {

            echo '<table class="wp-list-table widefat fixed striped stocks">';
            echo'<thead><tr><th class="column-primary"><strong>File Name</strong></th><th class="column-primary"><strong>Action</strong></th></tr></thead>';

            echo' <tbody id="the-list">';

            $scan = scandir($directory);

            foreach($scan as $file)
            {
                if (!is_dir("$directory/$file"))
                {
                    echo'<tr>';
                    echo'<td><strong>'.$file. '</strong></td>';
                    echo'<td><a herf="'.$directory.'/'.$file.'">Download</a></td>';
                    /*echo'<td>'.sprintf( '<a href="%s" >%s</a>', admin_url( 'admin.php?page=stock&action=edit&id=' . $item->option_id ), $item->option_id,'Download').'</td>';*/
                    echo  '</tr>';
                }
            }

        echo' </tbody></table>';
    }
    

?>
</form>
</div>