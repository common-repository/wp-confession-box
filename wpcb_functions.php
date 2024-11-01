<?php

function wpcb_messages($log){
	$list='';
	if(!empty($log)){
		
		if(!empty($log['success'])){
		
		foreach ($log['success'] as $msg) {
			$list.='<p class="notice notice-success" style="padding:4px;">'.$msg.'</p>';
		}
	}
	if(!empty($log['error'])){
		
		foreach ($log['success'] as $msg) {
			$list.='<p class="notice notice-error" style="padding:4px">'.$msg.'</p>';
		}
	}
	
}
return $list;
}


function wpcb_view($file,$attr_arr=array()){

$view='';

if(empty($file)){
return false;
}

if(!empty($attr_arr) && is_array($attr_arr)){
extract($attr_arr);
}

$extension='.php';
$twem_dirpath = plugin_dir_path(__FILE__).'view/';

if(file_exists($twem_dirpath.$file.$extension)){
ob_start();
include $twem_dirpath.$file.$extension;
$view = ob_get_clean();
}

return $view;
}

?>