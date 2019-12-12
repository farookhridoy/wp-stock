<?php

/* Template Name: StockList */ 
$upload = wp_upload_dir();
$directory = $upload['basedir'];
$directory = $directory . '/stockfile';
if (is_dir($directory)) {
    $scan = scandir($directory);
}
foreach($scan as $file)
{
    if (!is_dir("$directory/$file"))
    { 
       $current_date=date("Y-m-d").'.csv';
       if ($file==$current_date) {
          $current_path=$file;
      }
  }
}
?>
<?php get_header(); 

ob_start(); ini_set('max_execution_time', 0);  set_time_limit(0); ignore_user_abort(true); 

?>
<style>
    #wpwrap {
        height: auto;
        min-height: 100%;
        width: 100%;
        position: relative;
        -webkit-font-smoothing: subpixel-antialiased;
    }
    table {
     font-family: arial, sans-serif;

     width: 100%;
     display: table;
     border-collapse: separate;


 }

 td, th {
     border: 1px solid #dddddd;
     text-align: left;
     padding: 8px;
 }

 tr:nth-child(even) {
     background-color: #dddddd;
 }
 .button {
     background-color: #4CAF50; /* Green */
     border: none;
     color: white;
     padding: 6px 6px;
     text-align: center;
     text-decoration: none;
     display: inline-block;
     font-size: 14px;
     margin: 4px 2px;
     cursor: pointer;

 }

 .button2 {background-color: #008CBA;}
 table.fixed {
    table-layout: fixed;
}
.comment-ays, .feature-filter, .imgedit-group, .popular-tags, .stuffbox, .widgets-holder-wrap, .wp-editor-container, p.popular-tags, table.widefat {
    background: #fff;
}
table.widefat {
    border: 1px solid #ccd0d4;
    box-shadow: 0 1px 1px rgba(0,0,0,.04);
}
.widefat {
    border-spacing: 0;
    width: 100%;
    clear: both;
    margin: 0;
}
</style>
<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
       <div class="wrap">
        <h4>Date: <?php echo date("Y-m-d"); ?><?php  echo sprintf( '<a href="%s" class="button button2" >%s</a>', get_permalink( $current_path ) . '?file='. $current_path.'&download_file=1','Download') ?></h4>
          <?php

          
          $file_is = $directory . '/'.date("Y-m-d").'.csv';

          if (isset($current_path)) {
              
              $data = scrap_stock_fun();

            //echo "true";

          }else{

            $data= scrap_stock();
          }
?>
</div>          
</main><!-- .site-main -->

</div><!-- .content-area -->
<br>
<br>
<?php 

echo ob_get_clean();
get_footer();
 ?>