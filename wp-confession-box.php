<?php



/**



 * @package WP Confession Box



 * @version 2.2



 */



/*



Plugin Name: WP Confession Box



Description: This plugin is a great platform to handle fully featured confession system and allow users to confess anonymously also like or comment on other's confessions. 



Author: Ankur Vishwakarma



Version: 2.2



Author URI: http://ankurvishwakarma.com



Author Email : ankurvishwakarma54@yahoo.com 



Domain : wpcb



*/



if (!class_exists('WP_ConfessionBox')) {



class WP_ConfessionBox {







var $wpcb_db_version;



var $wpcb_dirpath;



var $wpcb_urlpath;



var $wpcb_category;


var $wpcb_settings;




function __construct(){



$this->wpcb_version='2.2';



$this->wpcb_urlpath=plugin_dir_url( __FILE__ );



$this->wpcb_dirpath=plugin_dir_path(__FILE__);

register_activation_hook( __FILE__, array($this,'wpcb_setup'));



register_uninstall_hook( __FILE__, 'wpcb_uninstall');



//register_deactivation_hook( __FILE__, array($this,'wpcb_uninstall'));



add_action( 'wp_enqueue_scripts', array($this,'load_front_js_css_files' ));



add_action( 'admin_enqueue_scripts', array($this,'load_backend_js_css_files' ));


$this->wpcb_category = apply_filters("wpcb_add_categories",get_option('wpcb_categories'));
$this->likes_methods = array(	
				'cookie' => 'Cookie',
				'uid' => 'Logged-in Users'
				);

$this->wpcb_settings = array(
'manage_likes_by' => get_option('wpcb_likes_manage_by','cookie'),
'title' => get_option('wpcb_toggle_title',false),
'author' => get_option('wpcb_toggle_author',false),
'category' => get_option('wpcb_toggle_category',false),
'date' => get_option('wpcb_toggle_date',false),
'desc_min_length' => get_option('wpcb_desc_length',false),
'categories' => $this->wpcb_category,
'likes_methods' => $this->likes_methods,
'background_1' => get_option('wpcb_cb_background_color_1',false),
'background_2' => get_option('wpcb_cb_background_color_2',false),
'background_3' => get_option('wpcb_cb_background_color_3',false),
'background_4' => get_option('wpcb_cb_background_color_4',false),
'button_background' => get_option('wpcb_cb_background_color_btn',false),
'button_text' => get_option('wpcb_cb_text_color_btn',false),
'date_color' => get_option('wpcb_cb_date_color',false),
'text_color' => get_option('wpcb_cb_text_color',false),
);


$this->includes();



add_action('admin_menu',array($this,'cb_admin_area'));







if ( !$this->wpcb_is_current_version() ){



$this->wpcb_update_setup();	



} 







}











//Admin menus



function cb_admin_area(){



	add_menu_page(







        'WP Confession Box',







        'WP Confession Box',







        'delete_wpcb_confession',







        'wp-confession-manager',







        array($this,'wp_confession_manager_admin_area'),







        'dashicons-format-status'







    );







    add_submenu_page(



    	'wp-confession-manager',







        'Settings',







        'Settings',







        'delete_wpcb_confession',







        'wp-confession-settings',







        array($this,'wp_confession_box_admin_area')







    );



}







function wp_confession_manager_admin_area(){



	do_action('wpcb_confession_manager');



}



function wp_confession_box_admin_area(){



	do_action('wpcb_admin_section');



}



//Include files



function includes(){



	require_once $this->wpcb_dirpath.'wpcb_functions.php';



	require_once $this->wpcb_dirpath.'controller/wpcb_controller.php';



	require_once $this->wpcb_dirpath.'controller/wpcb_most_popular-widget.php';



	require_once $this->wpcb_dirpath.'controller/wpcb_categories-widget.php';



	



}







// Load JS







function load_backend_js_css_files($hook){



	$filters=array(
		'toplevel_page_wp-confession-manager',
		'wp-confession-box_page_wp-confession-settings',
	);



	if(!in_array($hook,$filters)){



		return false;



	}


wp_enqueue_script("jquery");
wp_enqueue_script("jquery-ui-core");
wp_enqueue_script("jquery-ui-tabs");
wp_enqueue_style( 'dashicons' );	
wp_enqueue_style( 'wp-color-picker');
wp_enqueue_script( 'wp-color-picker');

/*wp_register_style( 'admin-confessionbox-css', $this->wpcb_urlpath . 'assets/css/admin_confessionbox.css'  );



wp_register_script( 'admin-confessionbox-js', $this->wpcb_urlpath .  'assets/js/admin-confession-box.js' ,array('jquery'));



wp_localize_script( 'admin-confessionbox-js', 'wpcb_ajax',array( 'ajaxurl' => admin_url( 'admin-ajax.php' )) );



*/



wp_register_style('wpcb_datatable_css','https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css');



wp_register_script('wpcb_datatable_js','https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js');







wp_register_style( 'wp-confessionbox-css', $this->wpcb_urlpath . 'assets/css/wpcb_confessionbox.css'  );



wp_register_script( 'wp-confessionbox-js', $this->wpcb_urlpath .  'assets/js/wp-confession-box.js' ,array('jquery'));



wp_localize_script( 'wp-confessionbox-js', 'wpcb_ajax',array( 'ajaxurl' => admin_url( 'admin-ajax.php' )) );







}



function load_front_js_css_files(){







	wp_enqueue_style( 'dashicons' );



	wp_register_style( 'wpcb-bootstrap-css', $this->wpcb_urlpath . 'assets/css/wpcb_bootstrap.css' ); //



	wp_register_script( 'wpcb-tether-js', $this->wpcb_urlpath . 'assets/js/tether.min.js' );



	wp_register_script( 'wpcb-validator-js', $this->wpcb_urlpath . 'assets/js/validator.min.js' );



	wp_register_script( 'wpcb-bootstrap-js', $this->wpcb_urlpath . 'assets/js/bootstrap.min.js' );



	//wp_enqueue_script('jquery');



    wp_register_style( 'wp-confessionbox-css', $this->wpcb_urlpath . 'assets/css/wpcb_confessionbox.css'  );



	wp_register_script( 'wp-confessionbox-js', $this->wpcb_urlpath .  'assets/js/wp-confession-box.js' ,array('jquery'));



	wp_localize_script( 'wp-confessionbox-js', 'wpcb_ajax',array( 



		'ajaxurl' => admin_url( 'admin-ajax.php' ),



		'cf_length' => get_option('wpcb_desc_length',20),



		'category' => (!empty($_REQUEST['category']) ? (int)$_REQUEST['category'] : '' ),



		'confession' => (!empty($_REQUEST['confession']) ? (int)$_REQUEST['confession'] : '' ) 



	));



	



}







function wpcb_is_current_version(){



	$version = get_option( 'wpcb_version' );



    return version_compare($version, $this->wpcb_version, '=') ? true : false;



}



function wpcb_update_setup(){

	$this->wpcb_install();



	$this->wpcb_capabilities();



	//$this->wpcb_metakeys();

}



function wpcb_setup(){



	$this->wpcb_install();



	$this->wpcb_capabilities();



	$this->wpcb_metakeys();



}







function wpcb_metakeys(){



	update_option('wpcb_approval_before_publish',1);

	update_option('wpcb_likes_manage_by','cookie');

	update_option('wpcb_toggle_title',1);

	

	update_option('wpcb_toggle_category',1);



	update_option('wpcb_toggle_author',1);

	update_option('wpcb_toggle_date',1);



	update_option('wpcb_desc_length',20);

	update_option('wpcb_cb_background_color_1','#fff');

	update_option('wpcb_cb_background_color_2','skyblue');
	
	update_option('wpcb_cb_background_color_3','#fff');
	
	update_option('wpcb_cb_background_color_4','#fff');
	
	update_option('wpcb_cb_background_color_btn','skyblue');
	
	update_option('wpcb_cb_text_color_btn','#fff');
	
	update_option('wpcb_cb_date_color','#fff');
	
	update_option('wpcb_cb_text_color','#000');

	update_option('wpcb_categories',array(



										'1' => __("Family",""),



										'2' => __("Friends",""),



										'3' => __("Relative",""),



										'4' => __("School / College",""),



										'5' => __("Office",""),



										'6' => __("Journey",""),



										'7' => __("Habbit",""),



										'8' => __("Other",""),



										));



}







function wpcb_capabilities(){







	$role = get_role( 'administrator' );







	$role->add_cap( 'add_wpcb_confession');







	$role->add_cap( 'edit_wpcb_confession');







	$role->add_cap( 'delete_wpcb_confession');







}



function wpcb_install() {



	global $wpdb;







	$wpcb_manager = $wpdb->prefix . 'wpcb_manager';



	$wpcb_likes_manager = $wpdb->prefix . 'wpcb_likes_manager';



	$wpcb_comments_manager = $wpdb->prefix.'wpcb_comments_manager';



	$charset_collate = $wpdb->get_charset_collate();







	/*



	--



	-- Table structure for table `wpcb_manager`



	--



	*/



	$sql = "CREATE TABLE IF NOT EXISTS $wpcb_manager (



		  id mediumint(11) NOT NULL AUTO_INCREMENT,



		  author_name varchar(20) NOT NULL,



		  age int(11) NOT NULL,



		  location varchar(20) NOT NULL,



		  title varchar(200) NOT NULL,



		  confession text NOT NULL,



		  category int(11) NOT NULL,



		  approved int(11) NOT NULL,



		  created_at datetime NOT NULL,



		  ip_address varchar(20) NOT NULL,



		  blocked int(11) NOT NULL,



		  PRIMARY KEY (id)



		) $charset_collate;";



	



	/*



	--



	-- Table structure for table `wpcb_likes_manager`



	--



	*/



	$sql2 = "CREATE TABLE IF NOT EXISTS $wpcb_likes_manager (



			id int(11) NOT NULL AUTO_INCREMENT,



			confession_id int(11) NOT NULL,



			ip_address varchar(20) NOT NULL,



			user_id int(11) NOT NULL,



			applied int(11) NOT NULL,



			PRIMARY KEY (`id`)



			) $charset_collate;";







	$sql3 = "CREATE TABLE IF NOT EXISTS $wpcb_comments_manager (



			`id` int(11) NOT NULL AUTO_INCREMENT,



			`comment` varchar(200) NOT NULL,



			`parent_comment_id` int(11) NOT NULL,



			`confession_id` int(11) NOT NULL,



			`user` int(11) NOT NULL,



			`created_at` datetime NOT NULL,



			`blocked` int(11) NOT NULL,



			`ip_address` varchar(20) NOT NULL,



			PRIMARY KEY (`id`)



			) $charset_collate;";



	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );



	dbDelta( $sql );



	dbDelta( $sql2 );



	dbDelta( $sql3 );







	update_option( 'wpcb_version', $this->wpcb_version );



}







function wpcb_uninstall(){



	global $wpdb;



	



	$wpcb_manager = $wpdb->prefix . 'wpcb_manager';



	$wpcb_likes_manager = $wpdb->prefix . 'wpcb_likes_manager';



	$wpcb_comments_manager = $wpdb->prefix.'wpcb_comments_manager';







	$sql = "DROP TABLE IF EXISTS $wpcb_manager";



	$sql2 = "DROP TABLE IF EXISTS $wpcb_likes_manager";



	$sql3 = "DROP TABLE IF EXISTS $wpcb_comments_manager";



	$wpdb->query($sql);



	$wpdb->query($sql2);



	$wpdb->query($sql3);



	delete_option('wpcb_approval_before_publish');



	delete_option('wpcb_version');



}







} //class



global $wp_confession_box;



$wp_confession_box=new WP_ConfessionBox;



} //if