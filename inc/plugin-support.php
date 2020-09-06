<?php
/**
 * Code that improves theme support for various plugins
 */
function twenty_fifteen_indieweb_plugin_support() {
	/*
	 * Adds support for Syndication Links
	 */
	if ( class_exists( 'Syn_Meta' ) && has_filter( 'the_content', array( 'Syn_Config', 'the_content' ) ) ) {
		remove_filter( 'the_content', array( 'Syn_Config', 'the_content' ), 30 );
	}
	/*
	 * Adds support for Simple Location
	 */
	if ( class_exists( 'Loc_View' ) && has_filter( 'the_content', array( 'Loc_View', 'location_content' ) ) ) {
		remove_filter( 'the_content', array( 'Loc_View', 'location_content' ), 12 );
	}
}
add_action( 'init', 'twenty_fifteen_indieweb_plugin_support', 11 );
