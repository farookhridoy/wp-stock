  <?php 
      $upload = wp_upload_dir();
      $directory = $upload['basedir'];
      $directory = $directory . '/stockfile';
      if (is_dir($directory)) {
        $scan = scandir($directory);
    }


?>

<div class="wrap">
    <h2>Scrap Data List</h2>
    <form method="post">

         <table class="wp-list-table widefat fixed striped stocks">
            <thead><tr><th class="column-primary"><strong>File Name</strong></th><th class="column-primary"><strong>Action</strong></th></tr></thead>
            <tbody id="the-list">
         <?php   
         foreach($scan as $file)
            {
                if (!is_dir("$directory/$file"))
                {
            ?>
                   <tr>
                    <td><strong><?php echo $file; ?> </strong></td>
                    <td>
                    <?php echo sprintf( '<a href="%s" >%s</a>', get_permalink( $file ) . '?file='. $file.'&download_file=1','Download') ?> ||
                    <?php echo sprintf( '<a href="%s" >%s</a>', admin_url( 'admin.php?page=stock&action=filedelete&file='.$file),'Delete') ?>
                        
                    </td>
                   </tr>
            <?php       
                }
            }
            ?>

        </tbody>
    </table>
</form>
</div>