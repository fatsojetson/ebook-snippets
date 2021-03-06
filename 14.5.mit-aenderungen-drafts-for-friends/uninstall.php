<?php
/**
 * Uninstalls data generated by the 'Drafts for Friends' plugin.
 *
 * This file removes post meta generated by the plugin.
 *
 * @since      0.1.0
 *
 * @package    WordPress
 * @subpackage drafts-for-friends
 */
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

if ( ! WP_UNINSTALL_PLUGIN ) {
	exit();
}

global $wpdb;

// delete post meta with the meta key 'dff_key'.
$wpdb->delete(
	$wpdb->postmeta,
	array( 'meta_key' => 'dff_key' )
);