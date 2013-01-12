<?php
    /*
    Plugin Name: Genesis Co-Authors Plus
    Plugin URI: http://www.jeangalea.com
    Description: Enables full support for the Co-Authors Plus plugin in Genesis
    Version: 1.0
    Author: Jean Galea
    Author URI: http://www.jeangalea.com
    License: GPLv3
    */

    /*
	Based on the excellent partial integration work of Bill Erickson:
	http://www.billerickson.net/wordpress-post-multiple-authors/
    */

    /*  
    Copyright 2012 Jean Galea (email : info@jeangalea.com)
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
    GNU General Public License for more details.
    
    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
    */



/** Add guest author without user profile functionality via the following functions */

// Remove Genesis Author Box and load our own

add_action( 'init', 'jg_coauthors_init' );

function jg_coauthors_init() {
	remove_action( 'genesis_after_post', 'genesis_do_author_box_single' );
	add_action( 'genesis_after_post', 'jg_author_box', 1 );
}
 
/**
 * Load Author Boxes
 *
 * @author Jean Galea
 */
function jg_author_box() {
 
	if( !is_single() )
		return;
 
	if( function_exists( 'get_coauthors' ) ) {
		
		$authors = get_coauthors();
		
		foreach( $authors as $author )
			jg_do_author_box( 'single', $author );
			
	} else {
		jg_do_author_box( 'single', get_the_author_ID() );	
	}
}
 
/**
 * Display Author Box
 * Modified from Genesis to use data from get_coauthors() function
 *
 * @author Jean Galea
 */
function jg_do_author_box( $context = '', $author ) {
 
	if( ! $author ) 
		return;

	$gravatar_size = apply_filters( 'genesis_author_box_gravatar_size', 70, $context );
	$gravatar      = get_avatar( $author->user_email , $gravatar_size );
	$title         = apply_filters( 'genesis_author_box_title', sprintf( '<strong>%s %s</strong>', __( 'About', 'genesis' ), $author->display_name  ), $context );
	$description   = wpautop( $author->description );

	/** The author box markup, contextual */
	$pattern = $context == 'single' ? '<div class="author-box"><div>%s %s<br />%s</div></div><!-- end .authorbox-->' : '<div class="author-box">%s<h1>%s</h1><div>%s</div></div><!-- end .authorbox-->';

	echo apply_filters( 'genesis_author_box', sprintf( $pattern, $gravatar, $title, $description ), $context, $pattern, $gravatar, $title, $description );	

}