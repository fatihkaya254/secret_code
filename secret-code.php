<?php

/**
* @package SecretCode
*/
/*
Plugin Name: Secret Code
Plugin URI: https://isleyenzihinler.com.tr
Description: Görüşme programını düzenlemek için oluşturulmuştur.
Version: 1.0.7
Author: Fatih Kaya
Author URI: https://isleyenzihinler.com.tr
License: GNU
Text Domain: meeting-parents
*/


if ( ! defined('ABSPATH') ) {	die;   }



function meet_parents_js(){
	$jsway = site_url().'/wp-content/plugins/secret-code/assets/myscript.js';
	$cssway = site_url().'/wp-content/plugins/secret-code/assets/mystyle.css';
	?>
	<style src="<?php echo $cssway;?>" type="text/css"></style>
	<script src="<?php echo 'https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js';?>"></script> 
    <script src="<?php echo 'https://code.jquery.com/ui/1.12.1/jquery-ui.js';?>"></script>
	<script src="<?php echo $jsway;?>"></script> 
	<?php
}


class iz_panel_Activate
{

	public function iz_activate(){
		flush_rewrite_rules();
	}

}

function secret_register($params = array()) {
	ob_start();
	include (dirname(__FILE__, 1) . '/templates/register.php');
	$ob_str=ob_get_contents();
	ob_end_clean();
	return $ob_str;
}

// register shortcode
add_shortcode('sc-secret-register', 'secret_register');

function show_value($params = array()) {
	ob_start();
	include (dirname(__FILE__, 1) . '/templates/value.php');
	$ob_str=ob_get_contents();
	ob_end_clean();
	return $ob_str;
}

// register shortcode
add_shortcode('sc-show-value', 'show_value');

require_once  plugin_dir_path( __FILE__ )."izinc/Izinit.php";
include_once plugin_dir_path( __FILE__ )."izinc/Base/Activate.php";
require_once  plugin_dir_path( __FILE__ )."izinc/Base/Deactivate.php";

//if(class_exists('iz_panel_Activate')){
$semih = new Activate();
//}

register_activation_hook( __FILE__ , array( 'Activate', '__activate' ) );
register_deactivation_hook( __FILE__ , array( 'Deactivate', 'deactivate' ) );

//if ( class_exists('\inc\Init')){
Izinit::register_services();
//}