<?php
/*
Plugin Name: belajar api woo
Plugin URI: https://twitter.com/belajar-api-woo
Description: 
Version: 1.0
Author: belajar-api-woo
Author URI: https://instagram.com/belajar-api-woo
License: Private
*/


add_filter( 'page_template', 'belajar_api_woo_halaman_rest' );
function belajar_api_woo_halaman_rest( $page_template ){
	$slug = 'belajar-api-woo';
	if ( is_page($slug) ) {
		$page_template = dirname( __FILE__ ) . '/endpoint.php';
	}
	return $page_template;
}

function belajar_api_woo_install_cs() {
	
	$post_data = array(
		'post_title' => '_for_belajar-api-woo',
		'post_type' => 'page',
		'post_status'   => 'publish',
		'post_name' => 'belajar-api-woo',
	);
	$post_id = wp_insert_post( $post_data );
}
register_activation_hook( __FILE__, 'belajar_api_woo_install_cs' ); 


function belajar_api_woo_uninstall_cs() {
	$data_halaman = get_pages();
	for ($i=0; $i < count($data_halaman); $i++) { 
		if ($data_halaman[$i]->post_name == "belajar-api-woo") {
			wp_delete_post( $data_halaman[$i]->ID, true);
		}
	}
}
register_deactivation_hook( __FILE__, 'belajar_api_woo_uninstall_cs' );