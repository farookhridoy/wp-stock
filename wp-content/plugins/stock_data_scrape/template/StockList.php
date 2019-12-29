<?php
/* Template Name: StockList */ 
?>
<?php get_header(); 
//ob_start(); ini_set('max_execution_time', 0);  set_time_limit(0); ignore_user_abort(true); 
ini_set('max_execution_time', 0); ignore_user_abort(true);
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
    <?php

    global $wpdb;
      $table_name= $wpdb->prefix . 'stock_scrap';
      if (isset($_GET['pageno'])) {
        $pageno = $_GET['pageno'];
      } else {
        $pageno = 1;
      }
      $no_of_records_per_page = 30;
      $offset = ($pageno-1) * $no_of_records_per_page;

      $total_items = $wpdb->get_var("SELECT COUNT(id) FROM $table_name WHERE updated_at !='null' ");
      $totalPages = ceil($total_items / $no_of_records_per_page);

      $links = "";
        for ($i = 1; $i <= $totalPages; $i++) {
          $links .= ($i != $pageno ) 
          ? "<td class='next-page button'><a href='?pageno=".$i."'>".$i."</a></td> "
          : "$pageno";
        }


      $alldata = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'stock_scrap WHERE updated_at !="null"  LIMIT '.$offset .','.$no_of_records_per_page.' ');

    ?>
    <div>
      <?php 
      $file=date('Y-m-d');

      echo sprintf( '<a class="button button-info" href="%s" >%s</a>', get_permalink( $file ) . '?file='. $file.'&download_file=1','Download') 
      ?>
    </div>
    <form id="entry-table" method="post" action='<?=$_SERVER['REQUEST_URI']; ?>' style="margin-top: 30px;">
      
      <table class="wp-list-table widefat fixed striped stocks">
        <thead>
          <tr>
          <th>Company Name</th>
          <th>Company Symbol</th>
          <th>Market Symbol</th>
          <th>Consensus Rating</th>
          <th>Consensus Rating Score</th>
          <th>Ratings Breakdown</th>
          <th>Consensus Price Target</th>
          <th>Last Updated</th>
        </tr>
        </thead>
      
      <tbody id="the-list">
      <?php 

        foreach ($alldata as $item) {
      ?>
        <tr>
          <td><?=$item->company_name ?></td>
          <td><?=$item->company_symbol ?></td>
          <td><?=$item->market_symbol ?></td>
          <td><?=$item->consensus_rating ?></td>
          <td><?=$item->consensus_rating_score ?></td>
          <td><?=$item->ratings_breakdown ?></td>
          <td><?=$item->consensus_price_target ?></td>
          <td><?=$item->updated_at ?></td>
        </tr>
     <?php 
      }
      ?>
      </tbody>
</table>

      
      <div class="tablenav-pages">
       
        
        <tr class="pagination">
          <td><a class="next-page button" href="?pageno=1">First</a></td>
          <td class="<?php if($pageno <= 1){ echo 'disabled'; } ?>">
            <a class="next-page button" href="<?php if($pageno <= 1){ echo '#'; } else { echo "?pageno=".($pageno - 1); } ?>">Prev</a>
          </td>
          <td><?php echo $links ?></td>
          <td class="<?php if($pageno >= $totalPages){ echo 'disabled'; } ?>">
            <a class="next-page button" href="<?php if($pageno >= $totalPages){ echo '#'; } else { echo "?pageno=".($pageno + 1); } ?>">Next</a>
          </td>
          <td><a class="next-page button" href="?pageno=<?php echo $totalPages; ?>">Last</a></td>
        </tr>

          
        </div>
     
    </form>
  </div>         
</main><!-- .site-main -->

</div><!-- .content-area -->
<br>
<br>
<?php 

echo ob_get_clean();
get_footer();
?>