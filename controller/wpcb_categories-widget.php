<?php

// register widget

// add_action('widgets_init', create_function('', 'return register_widget("wpcb_categories");')); // Depricated in PHP 7.2+

add_action ('widgets_init', 'wpcb_categories_init');

function wpcb_categories_init ()
{
    return register_widget('wpcb_categories');
}


class wpcb_categories extends WP_Widget {



// constructor

function __construct() {

// Give widget name here

parent::__construct(false, $name = __('Confession Box Categories list', 'wpcb'), array(

			'description' => 'List Categories assigned to confessions',

			) );



}



// widget form creation



function form($instance) {



// Check values

if( $instance) {

$title = esc_attr($instance['title']);

} else {

$title = '';

}

?>



<p>

<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'wpcb'); ?></label>

<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />

</p>







<?php

}



function update($new_instance, $old_instance) {

$instance = $old_instance;

// Fields

$instance['title'] = strip_tags($new_instance['title']);

return $instance;

}



// display widget

function widget($args, $instance) {

extract( $args );



// these are the widget options

$title = apply_filters('widget_title', $instance['title']);

echo $before_widget;



// Display the widget

echo '<div class="widget-text wpcb_categories_list" style="width:100%; margin: 10px 0 25px 0;">';

echo '<div class="widget-title" style="width: 90%; height:30px; margin-left:3%; ">';



// Check if title is set

if ( $title ) {

echo $before_title . $title . $after_title ;

}

echo '</div>';



// Check if conf_count is set

echo '<div class="widget-conf_count" style="width: 90%; margin-left:3%; padding:8px;  border-radius: 3px; min-height: 70px;">';

$categories= get_option('wpcb_categories');

$confession_box_page_id = get_option('wpcb_confession_page',0);

if(!empty($confession_box_page_id) && $confession_box_page_id>0){

	$cf_page=get_permalink($confession_box_page_id);

}

if(!empty($categories)){

unset($categories[0]);
$list='';
$list.='<ol>';

echo '<li><a style="text-decoration:none;" href="'.$cf_page.'/">All</a></li>';

foreach ($categories as $id => $name) {

	echo '<li><a style="text-decoration:none;" href="'.$cf_page.'?category='.$id.'">'.$name.'</a></li>';

}

$list.='</ol>';

echo $list;



}





echo '</div>';

echo '</div>';

echo $after_widget;

}

}



