<?php

/**
 * 子比主题
 * Zibll Theme
 * 官方网站：https://www.zibll.com/
 * 作者QQ：770349780
 * 感谢您使用子比主题，主题源码有详细的注释，支持二次开发
 * 如您需要定制功能、或者其它任何交流欢迎加QQ
 * 后台框架基于  Options Framework  修改、增强
 * http://wptheming.com
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Don't load if optionsframework_init is already defined
if (is_admin() && ! function_exists( 'optionsframework_init' ) ) :

function optionsframework_init() {

	//  If user can't edit theme options, exit
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}

	// Loads the required Options Framework classes.
	require plugin_dir_path( __FILE__ ) . 'includes/class-options-framework.php';
	require plugin_dir_path( __FILE__ ) . 'includes/class-options-framework-admin.php';
	require plugin_dir_path( __FILE__ ) . 'includes/class-options-interface.php';
	require plugin_dir_path( __FILE__ ) . 'includes/class-options-media-uploader.php';
	require plugin_dir_path( __FILE__ ) . 'includes/class-options-sanitization.php';

	// Instantiate the options page.
	$options_framework_admin = new Options_Framework_Admin;
	$options_framework_admin->init();

	// Instantiate the media uploader class
	$options_framework_media_uploader = new Options_Framework_Media_Uploader;
	$options_framework_media_uploader->init();

}

add_action( 'init', 'optionsframework_init', 20 );

endif;

if ( ! function_exists( 'get_option_framework_name' ) ){
	function get_option_framework_name() {
		$name = '';
		if ( function_exists( 'framework_option_args' ) && isset(framework_option_args()['name'] )) {
			$name = framework_option_args()['name'];
		}
		if ( '' == $name ) {
			$name = get_option( 'stylesheet' );
			$name = preg_replace( "/\W/", "_", strtolower( $name ) );
		}
		return $name;
	}
}

if ( ! function_exists( 'of_get_option' ) ) {
	function of_get_option( $name, $default = false ) {
		$option_name = get_option_framework_name();
		$options = get_option( $option_name );
		if ( isset( $options[$name] ) ) {
			return $options[$name];
		}
		return $default;
	}
}

if ( ! function_exists( 'of_set_option' ) ){
	function of_set_option( $name, $value ) {
		if(!$name) {return false;}
		$get_option = array();
		$option_name = get_option_framework_name();
		$get_option = get_option( $option_name );
		$get_option[$name] = $value;
		return	update_option($option_name, $get_option);
	}
}

if ( ! function_exists( 'of_get_menuurl' ) ){
	function of_get_menuurl($option_id='') {
		$name = get_option_framework_name();
		$url = admin_url('admin.php?page=framework_'.$name.'#'.$option_id);
		return $url;
	}
}
