<?php
/*
 *  Author: Sakri Koskimies
 *  Custom functions, support, custom post types and more.
 */

if ( function_exists( 'add_theme_support' ) ) {
	// Add Menu Support
	add_theme_support( 'menus' );

	// Add Thumbnail Theme Support
	add_theme_support( 'post-thumbnails' );
	add_image_size( 'large', 700, '', true ); // Large Thumbnail
	add_image_size( 'medium', 250, '', true ); // Medium Thumbnail
	add_image_size( 'small', 120, '', true ); // Small Thumbnail
	add_image_size( 'custom-size', 700, 200, true ); // Custom Thumbnail Size call using the_post_thumbnail('custom-size');

	// Enables post and comment RSS feed links to head
	add_theme_support( 'automatic-feed-links' );

	// Localisation Support
	load_theme_textdomain( 'kilpailusivu', get_template_directory() . '/languages' );
}

/*------------------------------------*\
	Functions
\*------------------------------------*/

/**
 * Nav
 */
function ks_nav() {
	wp_nav_menu(
		array(
			'theme_location'  => 'header-menu',
			'menu'            => '',
			'container'       => 'div',
			'container_class' => 'menu-{menu slug}-container',
			'container_id'    => '',
			'menu_class'      => 'menu',
			'menu_id'         => '',
			'echo'            => true,
			'fallback_cb'     => 'wp_page_menu',
			'before'          => '',
			'after'           => '',
			'link_before'     => '',
			'link_after'      => '',
			'items_wrap'      => '<ul>%3$s</ul>',
			'depth'           => 0,
			'walker'          => ''
		)
	);
}

/**
 * Enqueue scripts
 */
function ks_scripts() {
	if ( $GLOBALS['pagenow'] != 'wp-login.php' && ! is_admin() ) {

		wp_register_script( 'ks_scripts', get_template_directory_uri() . '/js/app.min.js', array(
			'jquery',
		), '1.0.1' );

		// Localize the script with new data
		$ks_data = array(
			'templateDirectoryUri' => get_template_directory_uri(),
		);
		wp_localize_script( 'ks_scripts', 'ksData', $ks_data );

		wp_enqueue_script( 'ks_scripts' );
	}
}

/**
 * Enqueue styles
 */
function ks_styles() {
	wp_register_style( 'normalize', get_template_directory_uri() . '/normalize.css', array(), '1.0', 'all' );
	wp_enqueue_style( 'normalize' ); // Enqueue it!

	wp_register_style( 'ks', get_template_directory_uri() . '/style.css', array(), '1.0', 'all' );
	wp_enqueue_style( 'ks' ); // Enqueue it!
}

/**
 * Register navigation
 */
function register_ks_menu() {
	register_nav_menus( array( // Using array to specify more menus if needed
		'header-menu'  => __( 'Header Menu', 'kilpailusivu' ),
		// Main Navigation
		'sidebar-menu' => __( 'Sidebar Menu', 'kilpailusivu' ),
		// Sidebar Navigation
		'extra-menu'   => __( 'Extra Menu', 'kilpailusivu' )
		// Extra Navigation if needed (duplicate as many as you need!)
	) );
}

// Remove the <div> surrounding the dynamic navigation to cleanup markup
function ks_wp_nav_menu_args( $args = '' ) {
	$args['container'] = false;

	return $args;
}

// Remove Injected classes, ID's and Page ID's from Navigation <li> items
function ks_css_attributes_filter( $var ) {
	return is_array( $var ) ? array() : '';
}

// Remove invalid rel attribute values in the categorylist
function remove_category_rel_from_category_list( $thelist ) {
	return str_replace( 'rel="category tag"', 'rel="tag"', $thelist );
}

// Add page slug to body class, love this - Credit: Starkers Wordpress Theme
function add_slug_to_body_class( $classes ) {
	global $post;
	if ( is_home() ) {
		$key = array_search( 'blog', $classes );
		if ( $key > - 1 ) {
			unset( $classes[ $key ] );
		}
	} elseif ( is_page() ) {
		$classes[] = sanitize_html_class( $post->post_name );
	} elseif ( is_singular() ) {
		$classes[] = sanitize_html_class( $post->post_name );
	}

	return $classes;
}

// If Dynamic Sidebar Exists
if ( function_exists( 'register_sidebar' ) ) {
	// Define Sidebar Widget Area 1
	register_sidebar( array(
		'name'          => __( 'Widget Area 1', 'kilpailusivu' ),
		'description'   => __( 'Description for this widget-area...', 'kilpailusivu' ),
		'id'            => 'widget-area-1',
		'before_widget' => '<div id="%1$s" class="%2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3>',
		'after_title'   => '</h3>'
	) );

	// Define Sidebar Widget Area 2
	register_sidebar( array(
		'name'          => __( 'Widget Area 2', 'kilpailusivu' ),
		'description'   => __( 'Description for this widget-area...', 'kilpailusivu' ),
		'id'            => 'widget-area-2',
		'before_widget' => '<div id="%1$s" class="%2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3>',
		'after_title'   => '</h3>'
	) );
}

// Remove wp_head() injected Recent Comment styles
function ks_remove_recent_comments_style() {
	global $wp_widget_factory;
	remove_action( 'wp_head', array(
		$wp_widget_factory->widgets['WP_Widget_Recent_Comments'],
		'recent_comments_style'
	) );
}

// Pagination for paged posts, Page 1, Page 2, Page 3, with Next and Previous Links, No plugin
function ks_pagination() {
	global $wp_query;
	$big = 999999999;
	echo paginate_links( array(
		'base'    => str_replace( $big, '%#%', get_pagenum_link( $big ) ),
		'format'  => '?paged=%#%',
		'current' => max( 1, get_query_var( 'paged' ) ),
		'total'   => $wp_query->max_num_pages
	) );
}

// Custom Excerpts
function ks_index( $length ) // Create 20 Word Callback for Index page Excerpts
{
	return 20;
}

// Create 40 Word Callback for Custom Post Excerpts
function ks_custom_post( $length ) {
	return 40;
}

// Create the Custom Excerpts callback
function ks_excerpt( $length_callback = '', $more_callback = '' ) {
	global $post;
	if ( function_exists( $length_callback ) ) {
		add_filter( 'excerpt_length', $length_callback );
	}
	if ( function_exists( $more_callback ) ) {
		add_filter( 'excerpt_more', $more_callback );
	}
	$output = get_the_excerpt();
	$output = apply_filters( 'wptexturize', $output );
	$output = apply_filters( 'convert_chars', $output );
	$output = '<p>' . $output . '</p>';
	echo $output;
}

// Custom View Article link to Post
function ks_blank_view_article( $more ) {
	global $post;

	return '... <a class="view-article" href="' . get_permalink( $post->ID ) . '">' . __( 'View Article', 'kilpailusivu' ) . '</a>';
}

// Remove Admin bar
function remove_admin_bar() {
	return false;
}

// Remove 'text/css' from our enqueued stylesheet
function ks_style_remove( $tag ) {
	return preg_replace( '~\s+type=["\'][^"\']++["\']~', '', $tag );
}

// Remove thumbnail width and height dimensions that prevent fluid images in the_thumbnail
function remove_thumbnail_dimensions( $html ) {
	$html = preg_replace( '/(width|height)=\"\d*\"\s/', "", $html );

	return $html;
}

// Custom Gravatar in Settings > Discussion
function ks_blankgravatar( $avatar_defaults ) {
	$myavatar                     = get_template_directory_uri() . '/img/gravatar.jpg';
	$avatar_defaults[ $myavatar ] = "Custom Gravatar";

	return $avatar_defaults;
}

// Threaded Comments
function enable_threaded_comments() {
	if ( ! is_admin() ) {
		if ( is_singular() and comments_open() and ( get_option( 'thread_comments' ) == 1 ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}
}

// Custom Comments Callback
function ks_blankcomments( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	extract( $args, EXTR_SKIP );

	if ( 'div' == $args['style'] ) {
		$tag       = 'div';
		$add_below = 'comment';
	} else {
		$tag       = 'li';
		$add_below = 'div-comment';
	}
	?>
    <!-- heads up: starting < for the html tag (li or div) in the next line: -->
    <<?php echo $tag ?><?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ) ?> id="comment-<?php comment_ID() ?>">
	<?php if ( 'div' != $args['style'] ) : ?>
        <div id="div-comment-<?php comment_ID() ?>" class="comment-body">
	<?php endif; ?>
    <div class="comment-author vcard">
		<?php if ( $args['avatar_size'] != 0 ) {
			echo get_avatar( $comment, $args['180'] );
		} ?>
		<?php printf( __( '<cite class="fn">%s</cite> <span class="says">says:</span>' ), get_comment_author_link() ) ?>
    </div>
	<?php if ( $comment->comment_approved == '0' ) : ?>
        <em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.' ) ?></em>
        <br/>
	<?php endif; ?>

    <div class="comment-meta commentmetadata"><a
                href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>">
			<?php
			printf( __( '%1$s at %2$s' ), get_comment_date(), get_comment_time() ) ?></a><?php edit_comment_link( __( '(Edit)' ), '  ', '' );
		?>
    </div>

	<?php comment_text() ?>

    <div class="reply">
		<?php comment_reply_link( array_merge( $args, array(
			'add_below' => $add_below,
			'depth'     => $depth,
			'max_depth' => $args['max_depth']
		) ) ) ?>
    </div>
	<?php if ( 'div' != $args['style'] ) : ?>
        </div>
	<?php endif; ?>
<?php }

/*------------------------------------*\
	Actions + Filters + ShortCodes
\*------------------------------------*/

// Add Actions
add_action( 'init', 'ks_scripts' );
add_action( 'get_header', 'enable_threaded_comments' );
add_action( 'wp_enqueue_scripts', 'ks_styles' );
add_action( 'init', 'register_ks_menu' );
add_action( 'init', 'ks_create_post_type' );
add_action( 'widgets_init', 'ks_remove_recent_comments_style' );
add_action( 'init', 'ks_pagination' );

// Remove Actions
remove_action( 'wp_head', 'feed_links_extra', 3 );
remove_action( 'wp_head', 'feed_links', 2 );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'index_rel_link' );
remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
remove_action( 'wp_head', 'adjacent_posts_rel_link', 10, 0 );
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
remove_action( 'wp_head', 'rel_canonical' );
remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );

// Add Filters
add_filter( 'avatar_defaults', 'ks_blankgravatar' );
add_filter( 'body_class', 'add_slug_to_body_class' );
add_filter( 'widget_text', 'do_shortcode' );
add_filter( 'widget_text', 'shortcode_unautop' );
add_filter( 'wp_nav_menu_args', 'ks_wp_nav_menu_args' );
add_filter( 'the_category', 'remove_category_rel_from_category_list' );
add_filter( 'the_excerpt', 'shortcode_unautop' );
add_filter( 'the_excerpt', 'do_shortcode' );
add_filter( 'excerpt_more', 'ks_blank_view_article' );
add_filter( 'show_admin_bar', 'remove_admin_bar' );
add_filter( 'style_loader_tag', 'ks_style_remove' );
add_filter( 'post_thumbnail_html', 'remove_thumbnail_dimensions', 10 );
add_filter( 'image_send_to_editor', 'remove_thumbnail_dimensions', 10 );

// Remove Filters
remove_filter( 'the_excerpt', 'wpautop' );

/*------------------------------------*\
	Custom Post Types
\*------------------------------------*/

/**
 * Custom post type for pic competition entries
 */
function ks_create_post_type() {
	register_post_type( 'image-entry',
		array(
			'labels'       => array(
				'name'               => __( 'Comp. Entries', 'kilpailusivu' ),
				'singular_name'      => __( 'Entry', 'kilpailusivu' ),
				'add_new'            => __( 'Add New', 'kilpailusivu' ),
				'add_new_item'       => __( 'Add New Entry', 'kilpailusivu' ),
				'edit'               => __( 'Edit', 'kilpailusivu' ),
				'edit_item'          => __( 'Edit Entry', 'kilpailusivu' ),
				'new_item'           => __( 'New Entry', 'kilpailusivu' ),
				'view'               => __( 'View', 'kilpailusivu' ),
				'view_item'          => __( 'View Entry', 'kilpailusivu' ),
				'search_items'       => __( 'Search Entries', 'kilpailusivu' ),
				'not_found'          => __( 'No Entries found', 'kilpailusivu' ),
				'not_found_in_trash' => __( 'No Entries found in Trash', 'kilpailusivu' )
			),
			'public'       => true,
			'hierarchical' => true,
			'has_archive'  => true,
			'supports'     => array(
				'title',
			),
			'menu_icon'    => 'dashicons-smiley',
			'can_export'   => true,
			'taxonomies'   => array()
		) );
}

?>
