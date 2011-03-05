<?php
/*
Plugin Name: Child Page List
Plugin URI:
Description:Show child page first attachment, title, and permalink.
Author: Austin Matzko 
Author URI: http://austinmatzko.com/
Version: 1.0
*/

class Filosofo_Child_Page_List
{
	public function __construct()
	{
		add_action( 'init', array($this, 'event_init' ) );	
	}

	public function event_init()
	{
		add_shortcode('child-page-list', array($this, 'shortcode_child_page_list'));
	}

	public function shortcode_child_page_list( $args = array() )
	{
		$query_args = array(
			'orderby' => 'menu_order',
			'order' => 'ASC',
			'page_id' => get_queried_object_id(),
			'post_type' => 'page',
			'showposts' => -1,
		);

		if ( ! empty( $args['id'] ) ) {
			$query_args['page_id'] = (int) $args['id'];
		}

		if ( ! empty( $args['slug'] ) ) {
			$query_args['name'] = $args['slug'];
		}

		$sub_pages_query = new WP_Query( $query_args );
	
		ob_start();
		while ( $sub_pages_query->have_posts() ) :
			
		endwhile;
		return ob_get_clean();
	}
}

function load_child_page_list()
{
	global $child_page_list;
	$child_page_list = new Filosofo_Child_Page_List;
}

add_action( 'plugins_loaded', 'load_child_page_list' );
