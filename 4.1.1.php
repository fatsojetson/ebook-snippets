<?php
/*
Plugin Name: Top-Level Menü erstellen
*/

add_action( 'admin_menu', 'mm_admin_menu' );

function mm_admin_menu() {
	add_menu_page(
		'Mein Titel',
		'Mein Menü',
		'manage_options',
		'mm-meins',
		'mm_main_page_render',
		'dashicons-universal-access-alt'
	);
}

function mm_main_page_render() {
	echo '<h1>Mein Titel</h1>';
}