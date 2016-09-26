<?php
require_once('../../../wp-load.php');
$btnid1 = $_POST['btnid1'];
switch ($btnid1) {
	case 'btn_upload_image':
		btn_upload_image();
		break;
	case 'btn_orderarray':
		btn_orderarray();
		break;
	case 'btn_deleteslide':
		btn_deleteslide();
		break;
}
function btn_deleteslide() {
	if(wp_delete_post($_POST['did1']))
	{
		echo $_POST['did1'];
	}
	else {
		echo "0";
	}
}
function btn_orderarray() {
	$pidarray1 = $_POST['pidarray1'];
	$one=explode(',',$pidarray1);
	print_r($one);
	$count = 1;
	foreach($one as $o)
	{
		update_post_meta($o, 'slideshow_order', $count);
		$count++;
	}
}
function btn_upload_image() {
	global $wpdb;
	//print_r($_FILES);
	require_once( ABSPATH . 'wp-admin/includes/image.php' );
	require_once( ABSPATH . 'wp-admin/includes/file.php' );
	require_once( ABSPATH . 'wp-admin/includes/media.php' );
	$attachment_id = media_handle_upload( 'file',0 );
	$attachment_url = wp_get_attachment_url($attachment_id);
	if($attachment_id)
	{
		update_post_meta($attachment_id, 'slideshow_slider', 'yes');
		global $wpdb;
		//$orderno = $wpdb->get_results("Select meta_value from wp_postmeta where meta_key LIKE 'slideshow_order'");
		$orderno = $wpdb->get_results("Select meta_value from ".$wpdb->prefix."postmeta where meta_key LIKE 'slideshow_order'");
		$array = array();
		foreach ($orderno as $value) 
		{
			$array[] = $value->meta_value;
		}
		sort($array);
		$lastid = end($array);
		$responsearray = array();
		if(!empty($lastid))
		{
			$latestid = $lastid+1;
			update_post_meta($attachment_id, 'slideshow_order', $latestid);
			array_push($responsearray,$latestid,$attachment_url,$attachment_id);
			echo json_encode($responsearray);
		}
		else {
			update_post_meta($attachment_id, 'slideshow_order', 1);
			array_push($responsearray,1,$attachment_url,$attachment_id);
			echo json_encode($responsearray);
		}
	}
}
?>