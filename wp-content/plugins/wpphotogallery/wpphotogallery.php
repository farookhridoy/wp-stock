<?php
/*
Plugin Name: Wordpress Photo Gallery Plugin
Plugin URI: http://trenzasoft.com
Description: WP Plugin for managing photo gallery
Author URI: http://trenzasoft.com
*/
if(!defined('WPPHOTOGALLERY_RESOURCE_PATH')) 
define('WPPHOTOGALLERY_RESOURCE_PATH',"wp-content/uploads/gallery");
define('WPPHOTOGALLERY_RESOURCE_URL',get_settings('siteurl')."/wp-content/uploads/gallery");

add_action('admin_menu', 'wpphotogallery_add_adminpages');
add_action('activate_wpphotogallery/wpphotogallery.php', 'install_wpphotogallery');
add_action('admin_init','wpphotogallery_scripts');
add_filter('plugin_action_links', 'wpphotogallery_plugin_action_links', 10, 2);

function wpphotogallery_plugin_dir(){
 return get_settings('siteurl').'/wp-content/plugins/wpphotogallery'; 
}
function wpphotogallery_scripts(){
 wp_enqueue_script('interface');
}
function wpphotogallery_plugin_action_links($links, $file) {
    static $this_plugin;
 
    if (!$this_plugin) {
        $this_plugin = plugin_basename(__FILE__);
    }
 
    // check to make sure we are on the correct plugin
    if ($file == $this_plugin) {
        // the anchor tag and href to the URL we want. For a "Settings" link, this needs to be the url of your settings page
        $settings_link = '<a href="' . get_bloginfo('wpurl') . '/wp-admin/admin.php?page=wpphotogallery/wpphotogallery.php">Settings</a>';
        // add the link to the list
        array_unshift($links, $settings_link);
    }
 
    return $links;
}

function wpphotogallery_controller_generic($app, $method) {
 if (@$_POST) {
  $str = ""; 
  foreach($app as $a) {
   if ($str) $str.=",";
   $str.="'".$_POST[$a]."'";
  }  
  $res = null;
  if ($str) eval("\$res = $method($str);");
  return $res;  
 }
 return null;
}
function wpphotogallery_controller_appearance() {
  $app = array('color', 'colorswitcher', 'layout', 'contentlimit', 
		'limitposttitles', 'usepostthumbnails', 'authordetails',
		'useauthorgravatar', 'usehotconversation', 'readmoretext',
		'feedburnerid', 'feedburnerurl');
  return wpphotogallery_controller_generic($app, "wpphotogallery_add_appearance"); 
}
function wpphotogallery_add_option($k, $v) {
 if (get_option($k)) {
  update_option($k,$v);
 } else {
  add_option($k, $v);
 }
}
function wpphotogallery_insert_options($k, $v) {
  add_option($k, $v);
}
function wpphotogallery_get_option($k) {
  global $wpdb;
  $rows = $wpdb->get_results("SELECT option_id,option_value FROM $wpdb->options WHERE option_name = '$k'",'ARRAY_A'); 
  return $rows;
}
function wpphotogallery_get_featured() {
 return wpphotogallery_get_option("wpphotogallery_featured"); 
}
function wpphotogallery_add_adminpages() {
    add_submenu_page('options-general.php','Manage Gallery', 'Manage Gallery', 8, __FILE__,'wpphotogallery_manage_gallery');
}

function wpphotogallery_translate($str) {
 return $str;
}

function wpphotogallery_get_options($k,$value_only=false) {
 
  global $wpdb;
  
  $res = get_option($k);
  
  return $res;
}

function wpphotogallery_set_options($k,$v){
	
    $stats = update_option($k,$v);
   
   return $stats;
}

function wpphotogallery_get_photo_lists() {
  global $wpdb;
  $rows = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."photo_gallery ORDER BY sort_order ASC",'ARRAY_A'); 
  return $rows;
}

function wpphotogallery_get_active_photos() {
  global $wpdb;
  $rows = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."photo_gallery WHERE status='1' ORDER BY sort_order ASC",'ARRAY_A'); 
  return $rows;
}

function wpphotogallery_get_photo($photo_id) {
  global $wpdb;
  $res = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."photo_gallery WHERE id='$photo_id'",'ARRAY_A'); 
  return $res;
}

function wpphotogallery_delete_photo($photo_id){
	global $wpdb;
	$photo_info = wpphotogallery_get_photo($photo_id);
	$del_stats = $wpdb->query("DELETE FROM ".$wpdb->prefix."photo_gallery WHERE  id = '$photo_id'");
	if($del_stats){				
		@unlink(ABSPATH.WPPHOTOGALLERY_RESOURCE_PATH."/".$photo_info['photo']);
		return true;
	}
}
function wpphotogallery_inactivate_photo($photo_id){
	global $wpdb;
	if($photo_id){
		$upd_stats = $wpdb->query("UPDATE ".$wpdb->prefix."photo_gallery SET status='0' WHERE  id = '$photo_id'");
	}
    return  $upd_stats;
	
}
function wpphotogallery_activate_photo($photo_id){
	global $wpdb;
	if($photo_id){
		$upd_stats = $wpdb->query("UPDATE ".$wpdb->prefix."photo_gallery SET status='1' WHERE  id = '$photo_id'");
	}
    return  $upd_stats;
}
function wpphotogallery_move_up($photo_id,$curr_pos){
	global $wpdb;
	if($photo_id){
		$upd_stats = $wpdb->query("UPDATE ".$wpdb->prefix."photo_gallery SET sort_order='".($curr_pos-1)."' WHERE  id = '$photo_id'");
		if($upd_stats)
		$wpdb->query("UPDATE ".$wpdb->prefix."photo_gallery SET sort_order='".$curr_pos."' WHERE  sort_order = '".($curr_pos-1)."' AND id !='$photo_id'");
	}
    return  $upd_stats;	
}
function wpphotogallery_move_down($photo_id,$curr_pos){
	global $wpdb;
	if($photo_id){
		$upd_stats = $wpdb->query("UPDATE ".$wpdb->prefix."photo_gallery SET sort_order='".($curr_pos+1)."' WHERE  id = '$photo_id'");
		if($upd_stats)
		$wpdb->query("UPDATE ".$wpdb->prefix."photo_gallery SET sort_order='".$curr_pos."' WHERE  sort_order = '".($curr_pos+1)."' AND id !='$photo_id'");
	}
    return  $upd_stats;	
}

function wpphotogallery_display_gallery_slides($content){
 	global $post;
    //$content = $post->post_content;
   
  	$reg_exp = "/\[.*#WPPHOTO-SLIDES#.*]/";
    preg_match($reg_exp, $content,$match);
  
  
  //print_r($match);
  if($match){
  	//$content = str_replace($match[0],'',$content);
  	$gallery_text = wpphotogallery_get_gallery_codes();
    $content = str_replace($match[0],$gallery_text,$content);
  	
  }
  return $content;	
}

function wpphotogallery_get_gallery_codes(){
 global $wpdb;	
 
 $photo_lists = wpphotogallery_get_active_photos();
 
 if($photo_lists){
 	$photo_galley_code = '<script type="text/javascript" src="'.wpphotogallery_plugin_dir().'/js/jquery.js"></script>';
	$photo_galley_code .= '<script type="text/javascript" src="'.wpphotogallery_plugin_dir().'/js/jquery_002.js"></script>';
	$photo_galley_code .= '<style>
			.slideshow1{
				
			}
			</style>';
		$photo_galley_code .= '<script type="text/javascript">
				$(function() {
				    $(\'#slideshow1\').cycle({ 
				    	speed:  2000	
					});
				});
			</script>';
		$photo_galley_code .= '<div id="slideshow1" style="height:330px;">';
		foreach($photo_lists as $img)
		$photo_galley_code .= '<a href=""><img src="'.WPPHOTOGALLERY_RESOURCE_URL."/".$img['photo'].'"></a>';
		$photo_galley_code .= '</div>';
		
 }
 return $photo_galley_code;
}

function wpphotogallery_check_errors_upload(){
 	global $_POST,$_FILES;
 	
 	$title = trim($_POST['title']);
 	
 	if(empty($title)){
 		$errors[] = "Please enter a title";
 	}
 	if(empty($_FILES['photo']['name'])){
 	 $errors[] = "Upload an Image to Continue";
 	}elseif(!($_FILES['photo']['type'] == "image/png" || $_FILES['photo']['type'] == "image/jpeg" || $_FILES['photo']['type'] == "image/gif")){
 	 $errors[] = "Only PNG/JPEG/GIF allowed";	
 	}
 	
 	return $errors;
 }
 
 function wpphotogallery_upload_file($files,$path,$form_input_name='photo',$rename=false){
	

	if($files[$form_input_name]['name']){
       $file_name = $rename ? $rename : basename($files[$form_input_name]['name']);
       
	   $file_destination_name = wpphotogallery_get_destinaion_file_name($file_name);
	   $uploadfile = $path . $file_destination_name;

       if(move_uploaded_file($files[$form_input_name]['tmp_name'], $uploadfile)){
       	 return $file_destination_name;
       }
       else
        return false;
    }
}

function wpphotogallery_get_destinaion_file_name($file_name,$path=''){
	//$path = $path ? $path : wponlinestore_resource_main_path()."/";
	$des_file = $path . $file_name;
	
	while(file_exists($des_file)){
		$file_name = substr($file_name,0,strrpos($file_name,'.')-1).rand().substr($file_name,strrpos($file_name,'.'),strlen($file_name));
		$des_file = $path . $file_name;
	}
	return $file_name;
}

function wpphotogallery_birth_date_select(){
	$month = array (1 => 'January',2 => 'February',3 => 'March',4 => 'April',5 => 'May',6 => 'June',7 => 'July',8 => 'August',9 => 'September',10 => 'October',11 => 'November',12 => 'December');
	$year = date("Y");
	
	$year_select = "<select name='birth_year' class='birth_day_select' style='margin-left: 60px;'>";
	$year_select .= "<option value='0'>Birth Year</option>";
	for($i=0;$i<100;$i++)
	$year_select .="<option value='".($year-$i)."'>".($year -$i)."</option>";
	
	$year_select .="</select>";
	
	$month_select = "<select name='b_month' class='birth_day_select'>";
	$month_select .= "<option value='0'>Birth Month</option>";
	foreach($month as $key=>$value)
	$month_select .="<option value='".$key."'>".$value."</option>";
	$month_select .="</select>";
	
	$day_select = "<select name='b_day' class='birth_day_select'>";
	$day_select .= "<option value='0'>Birth Day</option>";
	for($i=1;$i<32;$i++)
	$day_select .="<option value='".$i."'>".$i."</option>";
	$day_select .="</select>";
	return $year_select."&nbsp;&nbsp;".$month_select."&nbsp;&nbsp;".$day_select;
}
/********************END handling functions #CST****************************/

 function wpphotogallery_add_photo(){
  global $wpdb;
  if($_POST['upload_photo']){
  	$errors = wpphotogallery_check_errors_upload();
    if(empty($errors)){
   	 $file_name = wpphotogallery_upload_file($_FILES,ABSPATH.WPPHOTOGALLERY_RESOURCE_PATH."/");
 	  if($file_name){
 	  	$sort_order_val = $wpdb->get_var("SELECT MAX(`sort_order`) FROM ".$wpdb->prefix."photo_gallery");
 	  	$sort_order_val = $sort_order_val + 1;
   	 	$sql ="INSERT INTO ".$wpdb->prefix."photo_gallery SET title ='".trim($_POST['title'])."',photo='$file_name', status='".$_POST['status']."',  sort_order='".$sort_order_val."'";
   	     if($wpdb->query($sql))
          $message = "New Image Successfully added in gallery";
         else
          $message = "Failed to add galley image";
		}
    }else{
     $message = implode("<br/>",$errors);
    }
   }
  
  if(strlen($message)>0){
	echo "<div class='updated fade' style='padding: 5px;'>".$message."</div>";
 }
  
  echo "<div class=\"add-form\"><form method='post' enctype='multipart/form-data'>
  ";
  echo "<div style='margin: 25px 15px;'><table class='widefat post' cellspacing='0' border='1' style='width: 60%;margin-top: 5px;'>
	<thead>
	<tr>
	<th colspan='2' align='center' style='text-align: center;'>UPLOAD NEW GALLERY IMAGE</th>
	</tr>
	</thead>
	<tfoot>
	<tr>
	<th colspan ='2' scope='col'  class='manage-column column-title'>&nbsp;</th>
	</tr>
	</tfoot><tbody>";
	echo "<tr>
	 <td align='right' style='width: 20%;border-right: 1px solid #E7E7E7;'>Title</td>
	 <td align='left' style='width: 60%;'><input type='text' name='title' value='' size='78'/></td>
	</tr>";
	
	
	echo "<tr>
	 <td align='right' style='width: 20%;border-right: 1px solid #E7E7E7;'>Upload File</td>
	 <td align='left' style='width: 60%;'><input type='file' name='photo' /></td>
	</tr>";
	
	
	echo "<tr>
	 <td align='right' style='width: 20%;border-right: 1px solid #E7E7E7;'>Status</td>
	 <td align='left' style='width: 60%;'>
	 <select name='status'><option value='1'>Active</option><option value='0'>Inactive</option></select>
	 </td>
	</tr>";
	echo "<tr>
	 <td>&nbsp;</td>
	 <td><input type='submit' name='upload_photo' value='UPLOAD' /></td>
	</tr
	";
	echo "</tbody></table></div>";
	echo "</form></div>";
 }
function wpphotogallery_manage_gallery() {
	
 $del = $_GET['del'];
 $pid = $_GET['pid'];
 $viewimage = $_GET['viewimage'];
 $change_pos = $_GET['change_pos'];
 $cur_pos = $_GET['curr'];
 
 switch($change_pos){
 	case 'up':
 	         wpphotogallery_move_up($pid,$cur_pos);
 	         break;
    case 'down':
 	         wpphotogallery_move_down($pid,$cur_pos);
 	         break;
 	         
 }
 
 if($viewimage){
 	$photo_info = wpphotogallery_get_photo($viewimage);
 	echo "<img src='../".$photo_info['photo']."'/>";
     return 1;
 }
 
 if($_GET['action']=='inactivate'){                      ///////calling preview page
 	wpphotogallery_inactivate_photo($pid);
 }elseif($_GET['action']=='activate'){
 	wpphotogallery_activate_photo($pid);
 }elseif($_GET['action']=='add_new'){
 	wpphotogallery_add_photo();
 	return 1;
 }
 

 
 if($del){
 	$dlt_stats = wpphotogallery_delete_photo($del);
 	if($dlt_stats)
 	$msg = "Photo Deleted Succesfully...";
 }
 
 $photo_lists = wpphotogallery_get_photo_lists();
 
  
 echo "<h4>PHOTO GALLERY WITH SORT ORDER</h4>";
 
 
 if(strlen($msg)>0){
	echo "<div class='updated fade'>".$msg."</div>";
	echo"<br/><br/>";
	}
echo "
	<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
<!-- ;
var newwindow = ''
function popitup(url) {
if (newwindow.location && !newwindow.closed) {
    newwindow.location.href = url;
    newwindow.focus(); }
else {
    newwindow=window.open(url,'htmlname','width=404,height=316,resizable=1');}
}

function tidy() {
if (newwindow.location && !newwindow.closed) {
   newwindow.close(); }
}
// Based on JavaScript provided by Peter Curtis at www.pcurtis.com -->
</SCRIPT>
	";
 echo "<div style='float: right;margin: 0 30px 10px 0;'><a href='?page=wpphotogallery/wpphotogallery.php&action=add_new'>Upload New</a></div>";	
 echo "<table class='widefat post fixed' cellspacing='0' style='width: 98%'>
	<thead>
	<tr>
	<th scope='col'  class='manage-column' style='' width='6%'>Serial#</th>
	<th scope='col'  class='manage-column' style='' width='10%'>Title</th>
	<th scope='col'  class='manage-column' style='' width='10%'>Photo</th>
	<th scope='col'  class='manage-column' style='' width='10%'>Sort Order</th>
	<th scope='col'  class='manage-column' style='' width='10%'>Status</th>
	<th scope='col' id='categories' class='manage-column column-categories' style=''>Action</th>
	</tr>
	</thead>
	<tfoot>
	<tr>
	<th colspan ='7' scope='col'  class='manage-column column-title'>&nbsp;</th>
	</tr>
	</tfoot><tbody>";
	if($photo_lists){
		$i=1;
		foreach($photo_lists as $p_list){
			
			$photo_status = ($p_list['status']==1) ? "<a href='?page=wpphotogallery/wpphotogallery.php&action=inactivate&pid=".$p_list['id']."' style='color: green'>Active</a>": "<a href='?page=wpphotogallery/wpphotogallery.php&action=activate&pid=".$p_list['id']."' style='color: red'>Inactive</a>";
			
			if($i>1)
			$position_urls = "<a href='?page=wpphotogallery/wpphotogallery.php&change_pos=up&curr=".$p_list['sort_order']."&pid=".$p_list['id']."'><img src='".wpphotogallery_plugin_dir()."/icons/up-arraw.jpg' alt='UP'/></a>&nbsp;";
			if($i<count($photo_lists))
			$position_urls .= "<a href='?page=wpphotogallery/wpphotogallery.php&change_pos=down&curr=".$p_list['sort_order']."&pid=".$p_list['id']."'><img src='".wpphotogallery_plugin_dir()."/icons/down-arraw.jpg' alt='DOWN'/></a>";
			
			echo "<tr class='alternate author-self status-publish iedit' valign='top'>";
			echo "<td>".$i."</td>";
			echo "<td>".$p_list['title']."</td>";
		    echo "<td><img src='".WPPHOTOGALLERY_RESOURCE_URL."/".$p_list['photo']."' width='100px' height='100px'/></td>";
		    echo "<td>".$position_urls."</td>";
		    echo "<td>".$photo_status."</td>";
			echo "<td><a href='?page=wpphotogallery/wpphotogallery.php&del=".$p_list['id']."'>Delete</a></td>";
			echo "</tr>";
			$i++;
		}
	}else{
			echo "<tr class='alternate author-self status-publish iedit' valign='top'>";
		
			echo "<td  colspan ='7' style=\"color:red;\" align='center'>NO PHOTO AVAILABLE YET</td>";
		
			echo "</tr>";
	}
	
	echo "</tbody></table>";
}
function install_wpphotogallery(){
	global $wpdb;
	$table_name = $wpdb->prefix.'photo_gallery';
	$sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  
  `photo` varchar(255) NOT NULL,
  `sort_order` tinyint(4) NOT NULL,
  `status` tinyint(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";
  $rs = $wpdb->query($sql);
}
?>