

<?php
if(isset($_REQUEST['entryid']) && $_REQUEST['entryid']!='') {
  global $wpdb;
  $data = $wpdb->get_row( "SELECT * FROM `wp_crud` WHERE id = '".$_REQUEST['entryid']."'" );
?>
  

  <div id="wpbody" role="main">

<div id="wpbody-content">
       
                
    <div class="wrap">
        <h1 id="add-new-user">Update Company Key</h1>
         

        <form method="post" name="update_form" id="update_form">
            <input type="hidden" name="wqentryid" id="wqentryid" value="<?=$_REQUEST['entryid']?>" />
            <table class="form-table" role="presentation">
                <tbody>
                    <tr class="form-field form-required">
                        <th scope="row">
                            <label for="wqtitle">Key Name <span class="description">(required)</span></label>
                        </th>
                        <td>
                            <input name="wqtitle" type="text" id="wqtitle" value="<?=$data->title?>" style="width: 300px">
                             <label for="send_user_notification">Enter Key As Like "NASDAQ/AAPL".</label>
                        </td>
                    </tr>
                
                </tbody>
            </table>
            <div id="wqtitle_message" class="wqmessage"></div>
           
            <p class="submit"><input type="submit"  id="wqedit" class="button button-info wqsubmit_button" value="Update"></p>
             <div class="wqsubmit_message"></div>
        </form>
    </div>

<div class="clear"></div>
</div><!-- wpbody-content -->
<div class="clear"></div>
</div>
<?php
} else {
?>
<div id="wpbody" role="main">

<div id="wpbody-content">
       
                
    <div class="wrap">
        <h1 id="add-new-user">Add New Company Key</h1>
         

        <form method="post" name="entry_form" id="entry_form" class="validate" novalidate="novalidate">
            
            <table class="form-table" role="presentation">
                <tbody>
                    <tr class="form-field form-required">
                        <th scope="row">
                            <label for="wqtitle">Key Name <span class="description">(required)</span></label>
                        </th>
                        <td>
                            <input name="wqtitle" type="text" id="wqtitle" value="" style="width: 300px" placeholder="NASDAQ/AAPL">
                            <label for="send_user_notification">Enter Key As Like "NASDAQ/AAPL".</label>
                        </td>

                    </tr>
                
                </tbody>
            </table>
            <div id="wqtitle_message" class="wqmessage"></div>
           
            <p class="submit"><input type="submit"  id="wqedit" class="button button-success wqsubmit_button" value="Add New Key"></p>
             <div class="wqsubmit_message"></div>
        </form>
    </div>

<div class="clear"></div>
</div><!-- wpbody-content -->
<div class="clear"></div>
</div>
<?php } ?>




