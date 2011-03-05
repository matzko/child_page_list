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
			'orderby' => 'menu_order,title',
			'order' => 'ASC',
			'post_parent' => get_queried_object_id(),
			'post_type' => 'page',
			'showposts' => -1,
		);

		if ( ! empty( $args['id'] ) ) {
			$query_args['parent'] = (int) $args['id'];
		}

		$sub_pages_query = new WP_Query( $query_args );
	
		ob_start();
		while ( $sub_pages_query->have_posts() ) :
			$sub_pages_query->the_post();	
			?>
			<div class="subpage-wrap">
				<?php 
				$image_markup = $this->_get_first_attachment_markup( get_the_ID() ); ?> 
				<div class="first-attachment-wrap <?php 
					if ( empty( $image_markup ) ) {
						echo 'no-image';
					} ?>">
					<?php echo $image_markup; ?>
				</div><!-- .first-attachment-wrap -->
				<h2 class="subpage-title">
					<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
				</h2><!-- .subpage-title -->
			</div><!-- .subpage-wrap -->
			<?php	
		endwhile;
		return ob_get_clean();
	}

	protected function _get_first_attachment_markup( $parent_id = 0 )
	{
		$parent_id = (int) $parent_id;
		$children = get_posts( array(
			'post_type' => 'attachment',
			'showposts' => 1,
			'post_parent' => $parent_id,
		) );

		if ( ! is_wp_error( $children ) && is_array( $children ) ) { 
			$child = array_shift( $children );
			
			if ( ! empty( $child->ID ) ) { 
				return wp_get_attachment_image( $child->ID );
			}   
		}   
		return ''; 
	}
}

function load_child_page_list()
{
	global $child_page_list;
	$child_page_list = new Filosofo_Child_Page_List;
}

add_action( 'plugins_loaded', 'load_child_page_list' );
