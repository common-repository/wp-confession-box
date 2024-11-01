<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if (!class_exists('WPCB_Controller')) {

class WPCB_Controller {

	public $DB,$wpcb_manager,$wpcb_likes_manager,$wpcb_comments_manager,$actions;



function __construct(){

global $wpdb;	

$this->DB=$wpdb;

$this->wpcb_manager=$this->DB->prefix.'wpcb_manager';

$this->wpcb_likes_manager=$this->DB->prefix.'wpcb_likes_manager';

$this->wpcb_comments_manager=$this->DB->prefix.'wpcb_comments_manager';



// Admin Section

add_action('wpcb_admin_section',array($this,'wpcb_admin_section'));

add_action('wpcb_confession_manager',array($this,'wpcb_confession_manager'));

add_action( 'admin_notices', array($this,'wpcb_admin_notice__error'));

//Overwrite Default CSS

add_action('wp_head',array($this,'cb_custom_css'));



// Available Shortcodes

add_shortcode('wp-confession-form',array($this,'wpcb_form'));

add_shortcode('wp-confession-box',array($this,'wpcb_box'));



// Handle AJAX 

$this->actions=array(

   'cf_save_confession' => 'wpcb_manage_confession',

   'fetch_old_confession' => 'wpcb_fetch_confession',

   'wpcb_sync_confessions' => 'wpcb_fetch_confession',

   'manage_confession_likes' => 'manage_confession_likes',

   'manage_confession_actions'=>'manage_confession_actions',

   'manage_confession_comments'=>'manage_confession_comments',

   'wpcb_get_confession' => 'wpcb_get_confession',

   'wpcb_delete_confession'=>'manage_confession_actions',

   'wpcb_delete_category' => 'wpcb_delete_category'

   );

foreach ($this->actions as $action => $function) {

   add_action('wp_ajax_'.$action,array($this,$function));

   add_action('wp_ajax_nopriv_'.$action,array($this,$function));

}

add_action('admin_head', array($this,'wpcb_add_mce_shortcodes_option'));


} //construct


//Admin Notices

function wpcb_admin_notice__error(){
   $cb_page = get_option('wpcb_confession_page',false);
   if($cb_page === false){
   $class = 'notice notice-error';
   $message = 'WP Confession Box pages are not setup yet, Please create pages and link in ';
   $link = '<a href="admin.php?page=wp-confession-settings">Settings</a>';

   printf( '<div class="%1$s"><p>%2$s %3$s</p></div>', esc_attr( $class ), esc_html( $message ) , $link ); 
   }
   
}
//Shortcodes Option

function wpcb_add_mce_shortcodes_option(){
   // check user permissions
            if ( !current_user_can( 'edit_posts' ) &&  !current_user_can( 'edit_pages' ) ) {
                       return;
               }
           // check if WYSIWYG is enabled
           if ( 'true' == get_user_option( 'rich_editing' ) ) {
               add_filter( 'mce_external_plugins', array($this,'wpcb_add_tinymce_plugin'));
               add_filter( 'mce_buttons', array($this,'wpcb_register_mce_button'));
               }
}

function wpcb_register_mce_button( $buttons ) {
            array_push( $buttons, 'wpcb_mce_button' );
            return $buttons;
}

function wpcb_add_tinymce_plugin( $plugin_array ) {
         global $wp_confession_box;
          $plugin_array['wpcb_mce_button'] = $wp_confession_box->wpcb_urlpath .'assets/js/wpcb-mce-button.js';
          return $plugin_array;
}
//Admin section

function wpcb_delete_category(){

   $category_id = (int)$_POST['category_id'];

   $cats  = get_option('wpcb_categories');

   unset($cats[$category_id]);

   echo update_option('wpcb_categories',$cats);

   wp_die();

}

function wpcb_get_confession(){

   global $wpdb,$wp_confession_box;

   wp_enqueue_style('wp-confessionbox-css');

   wp_enqueue_script('wp-confessionbox-js');

   $data=array();



   $confession_id = (int)$_POST['confession_id'];

   $confession=$this->DB->get_results("select * from $this->wpcb_manager where id='$confession_id'");

   if(!empty($confession)){

   $data['confession']=$confession[0];

   $data['confession']->likes= $this->DB->get_results("select * from $this->wpcb_likes_manager where confession_id='$confession_id' ");

   $data['confession']->comments = $this->DB->get_results("select * from $this->wpcb_comments_manager where confession_id='$confession_id' ");

   }else{

      $data['error'] = 'No Result Found';

   }

   //ob_start();

   echo wpcb_view('admin/confession-single',array('wpcb'=>$wp_confession_box,'confession'=>$data['confession']));

   //$view = ob_get_clean();

   //ob_end_clean();

   //echo json_encode(array('confession'=>$view));

   //echo $view;

   wp_die();

}

function wpcb_confession_manager(){

   global $wpdb,$wp_confession_box;

   $log=array();

   wp_enqueue_style('wpcb_datatable_css');

   wp_enqueue_script('wpcb_datatable_js');



   wp_enqueue_style('wp-confessionbox-css');

   wp_enqueue_script('wp-confessionbox-js');



   $confessions=$this->DB->get_results("select * from $this->wpcb_manager  order by id DESC");



   if(!empty($confessions)){

      foreach ($confessions as $key => $confession) {

         

         $likes = $this->DB->get_results("select * from $this->wpcb_likes_manager where confession_id='$confession->id' ");

         $comments = $this->DB->get_results("select * from $this->wpcb_comments_manager where confession_id='$confession->id' ");

         $confessions[$key]->likes=$likes;

         $confessions[$key]->comments=$comments;



      }

   }

   echo wpcb_view('admin/confession-manager',array('wpcb'=>$wp_confession_box,'confessions'=>$confessions,'log'=>$log));

}



function wpcb_admin_section(){

global $wpdb,$wp_confession_box;



wp_enqueue_style('wp-confessionbox-css');

wp_enqueue_script('wp-confessionbox-js');



$log=array();


if(isset($_POST['wpcb_setting'])){

if(isset($_POST['wpcb_cb_background_color_1']) && update_option('wpcb_cb_background_color_1',$_POST['wpcb_cb_background_color_1'])){
$log['success'][]='Background Color 1 Updated Successfully';
}
if(isset($_POST['wpcb_cb_background_color_2']) && update_option('wpcb_cb_background_color_2',$_POST['wpcb_cb_background_color_2'])){
$log['success'][]='Background Color 2 Updated Successfully';
}
if(isset($_POST['wpcb_cb_background_color_3']) && update_option('wpcb_cb_background_color_3',$_POST['wpcb_cb_background_color_3'])){
$log['success'][]='Background Color 3 Updated Successfully';
}
if(isset($_POST['wpcb_cb_background_color_4']) && update_option('wpcb_cb_background_color_4',$_POST['wpcb_cb_background_color_4'])){
$log['success'][]='Background Color 4 Updated Successfully';
}
if(isset($_POST['wpcb_cb_background_color_btn']) && update_option('wpcb_cb_background_color_btn',$_POST['wpcb_cb_background_color_btn'])){
$log['success'][]='Button Background Color Updated Successfully';
}

if(isset($_POST['wpcb_cb_text_color_btn']) && update_option('wpcb_cb_text_color_btn',$_POST['wpcb_cb_text_color_btn'])){
$log['success'][]='Button Text Color Updated Successfully';
}

if(isset($_POST['wpcb_cb_date_color']) && update_option('wpcb_cb_date_color',$_POST['wpcb_cb_date_color'])){
$log['success'][]='Date Text Color Updated Successfully';
}

if(isset($_POST['wpcb_cb_text_color']) && update_option('wpcb_cb_text_color',$_POST['wpcb_cb_text_color'])){
$log['success'][]='Confession Text Color Updated Successfully';
}

if(!empty($_POST['wpcb_confession_page']) && (int)$_POST['wpcb_confession_page'] > 0){
   update_option('wpcb_confession_page',(int)$_POST['wpcb_confession_page']);
   $log['success'][]='Confession Box Page Selected Successfully';
}

if(!empty($_POST['wpcb_likes_manage_by'])){
   update_option('wpcb_likes_manage_by',$_POST['wpcb_likes_manage_by']);
   $log['success'][]='Likes Method Updated Successfully';
}

if(update_option('wpcb_toggle_title',(!empty($_POST['wpcb_toggle_title']) ? 1:0))){

$log['success'][]='Title Option Updated Successfully';

}

if(update_option('wpcb_toggle_category',(!empty($_POST['wpcb_toggle_category']) ? 1:0))){

$log['success'][]='Categories Option Updated Successfully';

}



if(update_option('wpcb_toggle_author',(!empty($_POST['wpcb_toggle_author']) ? 1:0))){

$log['success'][]='Author Option Updated Successfully';

}
if(update_option('wpcb_toggle_date',(!empty($_POST['wpcb_toggle_date']) ? 1:0))){

$log['success'][]='Date Option Updated Successfully';

}

if(isset($_POST['wpcb_desc_length']) && update_option('wpcb_desc_length',$_POST['wpcb_desc_length'])){
$log['success'][]='Confession Length Updated Successfully';
}

if(!empty($_POST['wpcb_add_category'])){

$cats=get_option('wpcb_categories');

$cats[0]='';

$cats[]=$_POST['wpcb_add_category'];

update_option('wpcb_categories',$cats);

$wp_confession_box->wpcb_category=$cats;

$log['success'][]='Category Added Successfully';

}



if(update_option('wpcb_custom_css',sanitize_text_field(stripslashes_deep($_POST['wpcb_custom_css'])))){

$log['success'][]='CSS Updated Successfully';

}

}

$custom_css = (!empty(get_option('wpcb_custom_css')) ? get_option('wpcb_custom_css') : "" ) ;

echo wpcb_view('admin/confession-settings',array('wpcb_cutom_css'=>$custom_css,'log'=>$log,'wpcb' => $wp_confession_box));

}



// Comments feature

function manage_confession_comments(){

global $wpdb;



$image='';

$user_id=0;

$name='';

$action='';

//$image='<img src="'.get_avatar_url().'" style="width:20px;height:20x"> ';

if(is_user_logged_in()){

$user = wp_get_current_user();

$user_id=$user->ID;

$image = '<img src="'.get_avatar_url($user_id).'" style="width:20px;height:20x">';

$name= $user->display_name.' - ';

if(current_user_can('delete_wpcb_confession')){

$action = '<span class="dashicons dashicons-no-alt cb_delete_comment" ></span>';

}

}



$log=array();

$pci=0;

$comment='';

$confession_id=0;

$check=false;

$comment=sanitize_text_field(stripslashes_deep($_POST['comment']));

$confession_id=intval($_POST['confession_id']);

if($confession_id==0){

$log['error'] = __('Invalid Confession ID','');

}

elseif(empty($comment)){

$log['error'] = __('Comment could not be empty','');

}else{



$params=array(

      'comment' => $comment,

      'parent_comment_id' => $pci,

      'confession_id' => $confession_id,

      'user' => $user_id,

      'created_at' => date('Y-m-d H:m:s'),

      'blocked' => 0,

      'ip_address' => $_SERVER['REMOTE_ADDR'],

      );



   $check=$this->DB->insert($this->wpcb_comments_manager,$params);



   if($check!==false){

      $cmt=$this->DB->get_results("select * from $this->wpcb_comments_manager where id='$wpdb->insert_id'");

      if(empty($name)){

         //$name=' #'.$wpdb->insert_id.' - ';

         $name='Anonymous - ';

      }

      $log['success']='<li style="list-style-type:none" id="'.$wpdb->insert_id.'">'.$action.$image.' <b>'.$name.'</b>'.$cmt[0]->comment.'</li>';

   }

}



echo json_encode($log);

   wp_die();

}





function manage_confession_actions(){

$log=array();

$action=sanitize_text_field(stripslashes_deep($_POST['apply']));

$confession_id=intval($_POST['confession_id']);

$extra_param=intval($_POST['extra_param']);



if($confession_id==0){

$log['error'] = __('Invalid Confession ID','');

}

elseif(empty($action)){

$log['error'] = __('Invalid Action','');

}

elseif ($action=='delete_comment' && $extra_param=='0') {

$log['error'] = __('Invalid Comment ID','');

}

else{



   if($action=='delete'){

      $ck1=$this->DB->delete( $this->wpcb_manager, array('id'=>$confession_id));

      if( $ck1!==false){

         $this->DB->delete( $this->wpcb_likes_manager, array('confession_id'=>$confession_id));

         $this->DB->delete( $this->wpcb_comments_manager, array('confession_id'=>$confession_id));

         $log['success'] = __("Confession has been deleted successfully!",'');

         $log['action'] = 'deleted';

      }/*else{

         $log['error']= __('Could not apply this action!');

      }*/

   }elseif($action=='block'){

      $exist=$this->DB->get_results("select id from $this->wpcb_manager where id='$confession_id' AND blocked='1'");



      if(!empty($exist)){

         $block_action=0;

      }else{

         $block_action=1;

      }

      $ck1=$this->DB->update($this->wpcb_manager,array('blocked'=>$block_action),array('id'=>$confession_id));



      if($ck1!==false){

         if($block_action==1){

         $log['success'] = __("This confession has been blocked successfully !","");

         $log['action'] = 'blocked';

         }else{

         $log['success'] = __("This confession has been unblocked successfully !","");

         $log['action'] = 'unblocked';

         }

         

      }



   }

   elseif ($action=='delete_comment') {

      $this->DB->delete( $this->wpcb_comments_manager, array('id'=>$extra_param));

      $log['success'] = __("Comment has been deleted successfully !","");

      $log['action'] = 'comment_deleted';



   }

   else{

      $log['error'] = __('Invalid Action !','');

   }



}



echo json_encode($log);

   wp_die();

}



function manage_confession_likes(){

global $wpdb;



$user_id=get_current_user_id();

$likes_manage_by=get_option("wpcb_likes_manage_by");

$ip=$_SERVER['REMOTE_ADDR'];

$conf_id=intval($_POST['confession_id']);

$apply=sanitize_text_field(stripslashes_deep($_POST['apply']));

$log=array();

if($conf_id==0){

$log['error'] = __('Invalid Confession ID','');

}

elseif(empty($apply)){

$log['error'] = __('Invalid action','');

}else{



$applied=(int)(($apply=='like') ? '1' : '0');



if ($likes_manage_by=='uid') {



   if(is_user_logged_in() && $user_id > 0){

      $if_exist=$this->DB->get_results("select id from $this->wpcb_likes_manager where confession_id='$conf_id' AND user_id='$user_id' ");



      if(empty($if_exist)){

      $params=array(

      'confession_id' => $conf_id,

      'ip_address' => $ip,

      'user_id' => $user_id,

      'applied' => $applied,

         );

      $check=$this->DB->insert($this->wpcb_likes_manager,$params);

      }else{

      $check=$this->DB->update($this->wpcb_likes_manager,array('applied' => $applied),array('id'=>$if_exist[0]->id));

      }



      if($check!=false){

      $status=$this->wpcb_get_confession_popularity($conf_id);

      $log['success']= $status;

      //$log['action']=$applied;

      }/*else{

      $log['error']= __('Could not apply this action!');

      }*/



   }else{

      $log['error'] = __("Please sign-in to like this confession",'');

   }

   }

   elseif($likes_manage_by=='cookie'){
      $check = false;
      if(isset($_COOKIE['wpcb_like_action_'.$conf_id])) {

     // $check=$this->DB->update($this->wpcb_likes_manager,array('applied' => $applied),array('id'=>$conf_id));

      $log['error'] = __("You have already voted!",'');

      }else{

      setcookie('wpcb_like_action_'.$conf_id,'applied', time() + (86400 * 1), "/"); //set cookie for 30 days   

      $params=array(

      'confession_id' => $conf_id,

      'ip_address' => $ip,

      'user_id' => 0,

      'applied' => $applied,

         );

      $check=$this->DB->insert($this->wpcb_likes_manager,$params);



      }

      

      if($check!=false){

      $status=$this->wpcb_get_confession_popularity($conf_id);

      $log['success']= $status;

      //$log['action']=$applied;

      }/*else{

      $log['error']= __('Could not apply this action!');

      }*/

   }

// elseif($likes_manage_by=='ip'){

//    $if_exist=$this->DB->get_results("select id from $this->wpcb_likes_manager where confession_id='$conf_id' AND ip_address='$ip' ");



//       if(empty($if_exist)){

//       $params=array(

//       'confession_id' => $conf_id,

//       'ip_address' => $ip,

//       'user_id' => $user_id,

//       'applied' => $applied,

//          );

//       $check=$this->DB->insert($this->wpcb_likes_manager,$params);

//       }else{

//       $check=$this->DB->update($this->wpcb_likes_manager,array('applied' => $applied),array('id'=>$if_exist[0]->id));

//       }



//       if($check!=false){

//       $status=$this->wpcb_get_confession_popularity($conf_id);

//       $log['success']= $status;

//       }else{

//       $log['error']= __('Could not apply this action!','');

//       }

// }



}

echo json_encode($log);

wp_die();

}



function wpcb_get_confession_popularity($conf_id){

$likes=0;

$dislikes=0;

$ip_arr=array();

$uid_arr=array();

$likes_manage_by=get_option('wpcb_likes_manage_by');

$user_id=get_current_user_id();

$ip=$_SERVER['REMOTE_ADDR'];

$current_user_action='na';

$who_liked='';

$who_disliked='';

$popularity=$this->DB->get_results("select * from $this->wpcb_likes_manager where confession_id='$conf_id'");

$comments=$this->DB->get_results("select * from $this->wpcb_comments_manager where confession_id='$conf_id'");


if(!empty($popularity)){

   foreach ($popularity as $action) {

      if($likes_manage_by=='uid' && is_user_logged_in()){ // if only logged in users can like or dislike 

      if($user_id == $action->user_id){
      $current_user_action=(($action->applied==1) ? 'liked' : 'disliked');
      }


      $applier=get_user_by('id',$action->user_id);
      $u='<em id="'.esc_attr($action->user_id).'">'.esc_html($applier->display_name).'</em>';

      if($action->applied==1){

         $likes++;

         $who_liked.=$u;

      }elseif ($action->applied==0) {

         $dislikes++;

         $who_disliked.=$u;

      }

      }

      elseif($likes_manage_by=='cookie'){ // By Cookie

         if(isset($_COOKIE['wpcb_like_action_'.$conf_id])){
            $current_user_action=(($action->applied==1) ? 'liked' : 'disliked');   
         }
         
/*         $applier=get_user_by('id',$action->user_id);
         $u='<em id="'.esc_attr($action->user_id).'">'.esc_html($applier->display_name).'</em>';
*/
         if($action->applied==1){

            $likes++;

            $who_liked.='';

         }elseif ($action->applied==0) {

            $dislikes++;

            $who_disliked.='';

         }  
      }
      // elseif($likes_manage_by=='ip' && $action->ip_address==$ip){

      // $current_user_action=(($action->applied==1) ? 'liked' : 'disliked');

      // }

      

   }

}


$comment_list='';

if(!empty($comments)){

   

   

   $action='';



   if( is_user_logged_in() && current_user_can('delete_wpcb_confession')){

      $action = '<span class="dashicons dashicons-no-alt cb_delete_comment"></span>';

   }

   

   if(!empty($comments)){

      foreach ($comments as $comment) {

      if($comment->user!=0){

         $user=get_user_by('id',$comment->user);

         $comment_list.='<li id="'.$comment->id.'">'.$action.'<img src="'.get_avatar_url($comment->user).'" style="width:20px;height:20x">  <b>'.$user->display_name.'</b> - '.$comment->comment.'</li>';

      }

      else{

         $comment_list.='<li id="'.$comment->id.'">'.$action.'  <b>Anonymous - </b>'.$comment->comment.'</li>';

      }

      }

   }

   

}

return array('likes' => $likes ,'dislikes' => $dislikes,'current_user_action'=>$current_user_action,'who_liked'=>$who_liked,'who_disliked'=>$who_disliked,'who_commented' => $comment_list);



}





function wpcb_fetch_confession(){

global $current_user,$wp_confession_box;
$last_con_id=0;
$first_con_id=0;
$category_id=0;
$confession_id=0;
$settings = $wp_confession_box->wpcb_settings;
if(isset($_POST[ 'last_con_id' ])){
$last_con_id=intval($_POST[ 'last_con_id' ]);
}
if(isset($_POST[ 'first_con_id' ])){
$first_con_id=intval($_POST[ 'first_con_id' ]);
}
if(isset($_POST[ 'category' ])){
$category_id=intval($_POST[ 'category' ]);
}
if(isset($_POST[ 'confession' ])){
$confession_id=intval($_POST[ 'confession' ]);
}

//echo $category_id.'='.$first_con_id.'='.$last_con_id.'='.$confession_id; die;

if($category_id==0 && $first_con_id==0 && $last_con_id!==0 && $confession_id==0){

$where ="id > $last_con_id";

$class="hide_new";

$new=true;

}elseif ($category_id==0 && $first_con_id!==0 && $last_con_id==0 && $confession_id==0) {

$where ="id < $first_con_id";

$class="";

$new=false;   

}elseif ($category_id!==0 && $first_con_id==0 && $last_con_id==0 && $confession_id==0) {

$where ="category = $category_id";

$class="";

$new=false;

}

elseif ($category_id!==0 && $first_con_id==0 && $last_con_id!==0 && $confession_id==0) {

$where ="id > $last_con_id && category = $category_id";

$class="hide_new";

$new=true;

}

elseif ($category_id!==0 && $first_con_id!==0 && $last_con_id==0 && $confession_id==0) {

$where ="id < $first_con_id && category = $category_id";

$class="";

$new=false; 

}

elseif($category_id==0 && $first_con_id==0 && $last_con_id==0 && $confession_id!==0 ){

$where ="id = $confession_id";

$class="";

$new=false;

}

else{

$where ="1";

$class="";

$new=false;

}

$query="select * from $this->wpcb_manager WHERE $where order by id DESC limit 10";



$all_old_confession=$this->DB->get_results($query);

/*$odd='';

$even='';*/

$confessions='';

$loop_last_conf_id=0;

if(!empty($all_old_confession) && count($all_old_confession)>0 ){


   $bg2=(!empty($settings['background_2']) ? $settings['background_2'] : 'skyblue');
   $bg2_border=(!empty($settings['background_2']) ? '3px solid '.$settings['background_2'] : '3px solid skyblue');
   $bg3=(!empty($settings['background_3']) ? $settings['background_3'] : '#fff');
   $bg4=(!empty($settings['background_4']) ? $settings['background_4'] : '#fff');
   $text_color=(!empty($settings['text_color']) ? $settings['text_color'] : '#000');
   $date_color=(!empty($settings['date_color']) ? $settings['date_color'] : '#fff');
   $button_bg=(!empty($settings['button_background']) ? $settings['button_background'] : 'skyblue');
   $button_text=(!empty($settings['button_text']) ? $settings['button_text'] : '#fff');

   $css='';
   /*$css.='<style id="wpcb_hover_likes">
   .like-cf:hover, .dashicons:hover, .liked, .disliked, .blocked {
    color: '.$bg2.' !important;
   }
   .wpcb_clear{
      border-color: '.$bg2.' !important;
   }
   #wpcb_confessions div p,#wpcb_confessions div h5,#wpcb_confessions div li,#wpcb_confessions div li b,#wpcb_confessions div textarea {
    color: '.$text_color.' !important;
   }
   #wpcb_confessions div textarea{
   background : '.$bg3.' !important;
   }
   #wpcb_confessions span.conf_comments_container input[type="button"]{
   background : '.$button_bg.' !important;
   color : '.$button_text.' !important;
   }
   </style>';
   $confessions.=$css;*/

foreach ($all_old_confession as $each_confession) {

   if($each_confession->blocked==1 && !in_array('administrator', $current_user->roles)){

      continue;

   }

   $loop_last_conf_id=$each_confession->id;

   $popularity=$this->wpcb_get_confession_popularity($each_confession->id);

   

   if($popularity['current_user_action']=='liked'){

      $c1='liked';

   }else{

      $c1='';

   }

   

   if($popularity['current_user_action']=='disliked'){

      $c2='disliked';

   }else{

      $c2='';

   }

   $str='';
   $str.='<div style="background:'.$bg4.'" id="'.esc_attr($each_confession->id).'" class="'.esc_attr($class).'">

      <div style="background:'.$bg2.';padding:5px;font-size:12px;color:#fff;width:100%;float:left;">';

      if((int)$each_confession->category > 0 && $settings['category'] != false){

         $str.='<em style="">'.$wp_confession_box->wpcb_category[$each_confession->category].'</em>';

      }
      
      if($settings['date'] != false){
      $str.='<em style="float:right;color:'.$date_color.'">'.date_i18n( get_option( 'date_format' ), strtotime( $each_confession->created_at ) ).'</em>';
      }

      $str.='</div>

         <p  style="border:'.$bg2_border.';background:'.$bg3.'" id="'.esc_attr($each_confession->id).'">
      ';

      if($settings['title'] != false){
      $str.='<strong>'.(!empty($each_confession->title) ? esc_html($each_confession->title).' - ' : '').'</strong><br />';   
      }
      
      $str.= esc_html($each_confession->confession).'<br /><br />';

      
      if($settings['author'] != false){
      $str.='<em style="text-align:right">'.(!empty($each_confession->author_name) ? 'Author - '.$each_confession->author_name.'<br>' : '').'</em>';
      }

      $str.='&nbsp;&nbsp;&nbsp;&nbsp;<br />

      <span style="" class="dashicons dashicons-thumbs-up like-cf '.$c1.'"></span>

      <span class="hide" id="cb_who_liked">'.$popularity['who_liked'].'</span>

      <span class="like_counts">('.$popularity['likes'].')</span> &nbsp;&nbsp;&nbsp;&nbsp;

      <span class="dashicons dashicons-thumbs-down like-cf '.$c2.'"></span> 

      <span class="hide" id="cb_who_disliked">'.$popularity['who_disliked'].'</span>

      <span class="dislike_counts">('.$popularity['dislikes'].')</span>&nbsp;&nbsp;&nbsp;&nbsp;';

      if(in_array('administrator', $current_user->roles)){

      $str.='<span class="dashicons dashicons-trash cb_delete_confession"></span>&nbsp;&nbsp;&nbsp;&nbsp;

      <span class="dashicons dashicons-hidden cb_block_confession '.(($each_confession->blocked==1) ? 'blocked' : '').'"></span>';

      }

      $str.='

      <br />

      <span style="display:none" class="conf_msg"></span><br />

      </p>

      

      <h5 class="comment_head" style="cursor: pointer;color:skyblue">What People Say - </h5>

      <ul id="conf_comment_list" style="">'.$popularity['who_commented'].'</ul>



      <span class="conf_comments_container">

      <textarea style="resize:none;width:80%" name="conf_comments"  placeholder="Write comment here.." rows="1"></textarea>

      <input type="button" style="max-width:100px;background: skyblue;border: none;padding: 6px;border-radius: 0px;color: #fff;" id="wpcb_send_comment" value="Send">

      </span>

      <div class="wpcb_clear"></div>

      </div>

      

      ';

$confessions.=$str;

   /*if(($each_confession->id % 2)==1){

      $odd.=$str;

   }	

   else{

      $even.=$str;

   }*/

}//foreach



} // confession main if

if($confession_id==0 && $loop_last_conf_id>0 && $last_con_id==0){

$confessions.='<div first_id="'.$loop_last_conf_id.'" class="wpcb_read_more">'.__('Read More','wpcb').'</div>';

}



/*echo json_encode(array('odd'=>$odd,'even'=>$even,'new'=>$new));*/

echo json_encode(array('confessions'=>$confessions,'new'=>$new));

wp_die();

}



function wpcb_manage_confession(){





$log=array();

if (! isset( $_POST['verify_cf_submission'] ) || ! wp_verify_nonce( $_POST['verify_cf_submission'], 'validate_confession' ) ) {

$log['error'] = __("Non verified submission","");

}elseif (get_option('wpcb_toggle_title')==1 && strlen($_POST['cf_title']) < 3) {

	$log['error'] = __("Title is required","");

}elseif (strlen($_POST['cf_desc'])<20) {

	$log['error'] = __("Description is required","");

}elseif (get_option('wpcb_toggle_author')==1 && strlen($_POST['cf_author'])<3) {

	$log['error'] = __("Author is required","");

} else {



   $params=array(

   		'author_name' => (!empty($_POST['cf_author']) ? sanitize_text_field(stripslashes_deep($_POST['cf_author'])) : ''),

   		'age' => 0,

   		'location' => '',

   		'title' => (!empty($_POST['cf_title']) ? sanitize_text_field(stripslashes_deep($_POST['cf_title'])) : ''),

   		'confession' => sanitize_text_field(stripslashes_deep($_POST['cf_desc'])),

   		'category' => (!empty($_POST['cf_category']) ? intval($_POST['cf_category']) : 0),

   		'approved' => get_option('wpcb_approval_before_publish',0),

   		'created_at'=> date('Y-m-d'),

   		'ip_address'=> $_SERVER['REMOTE_ADDR'],

   		'blocked' => 0

   	);



if(isset($_POST['cf_id']) && (int)$_POST['cf_id'] > 0){

   $return=$this->DB->update( $this->wpcb_manager, $params , array('id'=> $_POST['cf_id']));

}else{

   $return=$this->DB->insert( $this->wpcb_manager, $params);

}

   



   if($return!=false){

   	if(get_option('wpcb_approval_before_publish') ==1){

   		$log['success']=__('Confession submitted successfully !','');

   	}else{

   		$log['success']=__('Confession submitted successfully ! now waiting for Admin approval.','');

   	}

   	

   }

}



echo json_encode($log);

wp_die();

}



function wpcb_box($atts){
   $a = shortcode_atts( array(
        'width' => '100%',
    ), $atts );
   wp_enqueue_style('wp-confessionbox-css');

   wp_enqueue_script('wp-confessionbox-js',array('jquery'));

global $wp_confession_box;

return wpcb_view('confessions-box',array('wpcb' => $wp_confession_box,'width'=>esc_attr($a['width'])));
	//include($wp_confession_box->wpcb_dirpath.'view/confessions-box.php');

}



function cb_custom_css(){



   $css='';

   $css.='<style id="wpcb_custom_style" type="text/css">';

   $css.= get_option('wpcb_custom_css');

   $css.='</style>';

   echo $css;

}



function wpcb_form($atts){

   $a = shortcode_atts( array(
        'width' => '100%',
    ), $atts );

   wp_enqueue_style('wp-confessionbox-css');

   wp_enqueue_script('wp-confessionbox-js',array('jquery'));



	global $wp_confession_box;



	//wp_enqueue_style( 'wpcb-bootstrap-css');

	//wp_enqueue_script( 'wpcb-tether-js');

	//wp_enqueue_script( 'wpcb-bootstrap-js');


return wpcb_view('confession-form',array('wpcb' => $wp_confession_box,'width'=>esc_attr($a['width'])));
	//include($wp_confession_box->wpcb_dirpath.'view/confession-form.php');

}



} //class

new WPCB_Controller;

} //if

?>