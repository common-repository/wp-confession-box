<?php

// register widget

// add_action('widgets_init', create_function('', 'return register_widget("wpcb_most_popular_confessions");'));// Depricated in PHP 7.2+

add_action ('widgets_init', 'wpcb_most_popular_confessions_init');

function wpcb_most_popular_confessions_init ()
{
    return register_widget('wpcb_most_popular_confessions');
}

class wpcb_most_popular_confessions extends WP_Widget {



// constructor

function __construct() {

// Give widget name here

parent::__construct(false, $name = __('Most Popular Confessions', 'wpcb'), array(

			'description' => 'List Most Popular Confessions',

			));



}



// widget form creation



function form($instance) {



// Check values

if( $instance) {

$title = esc_attr($instance['title']);

$conf_count = $instance['conf_count'];

//$conf_option = $instance['conf_option'];

} else {

$title = '';

$conf_count=1;

//$conf_option='';

}

?>



<p>

<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'wpcb'); ?></label>

<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />

</p>



<!-- <p>

<label for="<?php //echo $this->get_field_id('conf_option'); ?>"><?php //_e('Select option', 'wpcb'); ?></label>

<select class="widefat" id="<?php //echo $this->get_field_id('conf_option'); ?>" name="<?php //echo $this->get_field_name('conf_option'); ?>">

<option value="top_liked" <?php //echo ($conf_option=='top_liked') ? 'selected' : '' ?>>Top Liked</option>

<option value="top_commented" <?php //echo ($conf_option=='top_commented') ? 'selected' : '' ?>>Top Commented</option>

<option value="most_popular" <?php //echo ($conf_option=='most_popular') ? 'selected' : '' ?>>Most Popular(Likes + Comments)</option>

</select>

</p> -->



<p>

<label for="<?php echo $this->get_field_id('conf_count'); ?>"><?php _e('Display number of confessions', 'wpcb'); ?></label>

<input class="widefat" id="<?php echo $this->get_field_id('conf_count'); ?>" name="<?php echo $this->get_field_name('conf_count'); ?>" type="number" min="1" value="<?php echo $conf_count; ?>" />

</p>



<?php

}



function update($new_instance, $old_instance) {

$instance = $old_instance;

// Fields

$instance['title'] = strip_tags($new_instance['title']);

$instance['conf_count'] = (int)$new_instance['conf_count'];

//$instance['conf_option'] = strip_tags($new_instance['conf_option']);

return $instance;

}



function limit_text($text, $limit) {

      if (str_word_count($text, 0) > $limit) {

          $words = str_word_count($text, 2);

          $pos = array_keys($words);

          $text = substr($text, 0, $pos[$limit]) . '...';

      }

      return $text;

   }

// display widget

function widget($args, $instance) {

global $wpdb,$wp_confession_box;



$wpcb_manager=$wpdb->prefix.'wpcb_manager';

$wpcb_likes_manager=$wpdb->prefix.'wpcb_likes_manager';

$wpcb_comments_manager=$wpdb->prefix.'wpcb_comments_manager';



extract( $args );



// these are the widget options

$title = apply_filters('widget_title', $instance['title']);

$conf_count = (int)$instance['conf_count'];

//$conf_option = $instance['conf_option'];

echo $before_widget;



// Display the widget

echo '<div class="widget-text wpcb_box" style="width:100%; margin: 10px 0 25px 0;">';

echo '<div class="widget-title" style="width: 100%; height:30px; margin-left:3%; ">';



// Check if title is set

if ( $title ) {

echo $before_title . $title . $after_title ;

}

echo '</div>';



// Check if conf_count is set

echo '<div class="widget-conf_count" style="width: 100%; margin-left:3%; padding:8px;border-radius: 3px; min-height: 70px;">';

if( $conf_count>0 ) {

//echo '<p class="wpcb_conf_count" style="font-size:15px;">'.$conf_count.'</p>';

$most_liked_confessions = $wpdb->get_results(sprintf("

	SELECT lm.confession_id,COUNT(lm.confession_id) as rating, m.confession as description 

	FROM $wpcb_likes_manager as lm

	INNER JOIN $wpcb_manager as m

	ON lm.confession_id=m.id

	WHERE lm.applied='1' 

	AND m.blocked='0'

	AND m.approved='1'

	GROUP BY lm.confession_id

	ORDER by rating DESC 

	LIMIT %d

	",$conf_count));

$most_commented_confessions = $wpdb->get_results(sprintf("

	SELECT cm.confession_id,COUNT(cm.confession_id) as rating, m.confession as description 

	FROM $wpcb_comments_manager as cm

	INNER JOIN $wpcb_manager as m

	ON cm.confession_id=m.id

	WHERE cm.blocked='0' 

	AND m.blocked='0'

	AND m.approved='1'

	GROUP BY cm.confession_id 

	ORDER by rating DESC 

	LIMIT %d

	",$conf_count));

$popular_confessions_arr=array();



/*print_r($most_liked_confessions);

print_r($most_commented_confessions);

die;*/

if(!empty($most_liked_confessions)){

foreach ($most_liked_confessions as $confession) {

	$popular_confessions_arr[$confession->confession_id]=$confession->rating;

	$popular_confessions_data[$confession->confession_id]['likes']=$confession->rating;

	$popular_confessions_data[$confession->confession_id]['description']=$confession->description;

}

}



if(!empty($most_commented_confessions)){

foreach ($most_commented_confessions as $confession) {

	if(array_key_exists($confession->confession_id, $popular_confessions_arr)){
		$popular_confessions_arr[$confession->confession_id]=$popular_confessions_arr[$confession->confession_id]+$confession->rating;

	}else{

		$popular_confessions_arr[$confession->confession_id]=$confession->rating;

	}

	$popular_confessions_data[$confession->confession_id]['comments']=$confession->rating;

	$popular_confessions_data[$confession->confession_id]['description']=$confession->description;

	

}

}

if(!empty($popular_confessions_arr)){

arsort($popular_confessions_arr);

if(count($popular_confessions_arr)>$conf_count){

$popular_confessions_arr=array_slice($popular_confessions_arr, 0, $conf_count,true);

} //slice array to limit

//$ids=implode(",",array_keys($popular_confessions_arr));

$confession_box_page_id = get_option('wpcb_confession_page',0);

if(!empty($confession_box_page_id) && $confession_box_page_id>0){

	$cf_page=get_permalink($confession_box_page_id);

}
$list='';
$list.='<ol>';

foreach ($popular_confessions_arr as $id => $rating) {

	$list.='<li style="font-size:13px;border-bottom:5px solid #eee;text-align:left;padding:5px;">';

	$list.='<a href="'.$cf_page.'?confession='.$id.'">

	<p style="">'.$this->limit_text($popular_confessions_data[$id]['description'],10).'</p>

	<em>Likes: '.(!empty($popular_confessions_data[$id]['likes']) ? $popular_confessions_data[$id]['likes'] : 0).'</em>

    <em style="float:right">Comments: '.(!empty($popular_confessions_data[$id]['comments']) ? $popular_confessions_data[$id]['comments'] : 0).'</em></a>

    </a>';

	$list.='</li>

	<u></u>

	';	

}

$list.='</ol>';







}









echo $list;



}

echo '</div>';

echo '</div>';

echo $after_widget;

}

}



