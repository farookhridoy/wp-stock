<?php
/**
 * WordPress User Page
 *
 * Handles authentication, registering, resetting passwords, forgot password,
 * and other user handling.
 *
 * @package WordPress
 */

/** Make sure that the WordPress bootstrap has run before continuing. */
require_once( dirname( __FILE__ ) . '/wp-load.php' );
// Load the theme template.


      $upload = wp_upload_dir();
      $directory = $upload['basedir'];
      $directory = $directory . '/stockfile';
      if (is_dir($directory)) {
        $scan = scandir($directory);
    }


?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Stock List</title>
	<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<style>
		table {
			font-family: arial, sans-serif;
			border-collapse: collapse;
			width: 100%;
		}

		td, th {
			border: 1px solid #dddddd;
			text-align: left;
			padding: 8px;
		}

		tr:nth-child(even) {
			background-color: #dddddd;
		}
	</style>
</head>
<body>

    <h2>Stock List</h2>


         <table class="table table-responsive table-hover" style="width: 100%">
            <thead>
            	<tr>
            		<th><strong>File Name</strong></th>
            		<th><strong>Action</strong></th></tr>

            </thead>
            <tbody>
         <?php   
         foreach($scan as $file)
            {
                if (!is_dir("$directory/$file"))
                {
            ?>
                   <tr>
                    <td><strong><?php echo $file; ?> </strong></td>
                    <td>
                    <?php echo sprintf( '<a href="%s" >%s</a>', get_permalink( $file ) . '?file='. $file.'&download_file=1','Download') ?>
                    </td>
                   </tr>
            <?php       
                }
            }
            ?>

        </tbody>
    </table>


</body>
</html>

