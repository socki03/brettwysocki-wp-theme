<?php
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function brettwysocki_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed at WordPress.org. See: https://translate.wordpress.org/projects/wp-themes/brettwysocki
	 * If you're building a theme based on Twenty Seventeen, use a find and replace
	 * to change 'brettwysocki' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'brettwysocki' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	add_image_size( 'brettwysocki-featured-image', 2000, 1200, true );

	add_image_size( 'brettwysocki-thumbnail-avatar', 100, 100, true );

	// This theme uses wp_nav_menu() in two locations.
	register_nav_menus( array(
		'top'    => __( 'Top Menu', 'brettwysocki' ),
		'social' => __( 'Social Links Menu', 'brettwysocki' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array( 'comment-form', 'comment-list', 'gallery', 'caption', ) );

	/*
	 * This theme styles the visual editor to resemble the theme style,
	 * specifically font, colors, and column width.
 	 */
	add_editor_style( array( 'assets/css/editor-style.css', brettwysocki_fonts_url() ) );

}
add_action( 'after_setup_theme', 'brettwysocki_setup' );

/**
 * Register custom fonts.
 */
function brettwysocki_fonts_url() {
	$fonts_url = '';

	$font_families = array();

	$font_families[] = 'Signika:400,700';

	$query_args = array(
		'family' => urlencode( implode( '|', $font_families ) ),
		'subset' => urlencode( 'latin,latin-ext' ),
	);

	$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );

	return esc_url_raw( $fonts_url );
}

/**
 * Add preconnect for Google Fonts.
 *
 * @since Twenty Seventeen 1.0
 *
 * @param array  $urls           URLs to print for resource hints.
 * @param string $relation_type  The relation type the URLs are printed.
 * @return array $urls           URLs to print for resource hints.
 */
function brettwysocki_resource_hints( $urls, $relation_type ) {
	if ( wp_style_is( 'brettwysocki-fonts', 'queue' ) && 'preconnect' === $relation_type ) {
		$urls[] = array(
			'href' => 'https://fonts.gstatic.com',
			'crossorigin',
		);
	}

	return $urls;
}
add_filter( 'wp_resource_hints', 'brettwysocki_resource_hints', 10, 2 );

/**
 * Replaces "[...]" (appended to automatically generated excerpts) with ... and
 * a 'Continue reading' link.
 *
 * @since Twenty Seventeen 1.0
 *
 * @return string 'Continue reading' link prepended with an ellipsis.
 */
function brettwysocki_excerpt_more( $link ) {
	if ( is_admin() ) {
		return $link;
	}

	$link = sprintf( '<p class="link-more"><a href="%1$s" class="more-link">%2$s</a></p>',
		esc_url( get_permalink( get_the_ID() ) ),
		/* translators: %s: Name of current post */
		sprintf( __( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'brettwysocki' ), get_the_title( get_the_ID() ) )
	);
	return ' &hellip; ' . $link;
}
add_filter( 'excerpt_more', 'brettwysocki_excerpt_more' );

/**
 * Handles JavaScript detection.
 *
 * Adds a `js` class to the root `<html>` element when JavaScript is detected.
 *
 * @since Twenty Seventeen 1.0
 */
function brettwysocki_javascript_detection() {
	echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";
}
add_action( 'wp_head', 'brettwysocki_javascript_detection', 0 );

/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 */
function brettwysocki_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">' . "\n", get_bloginfo( 'pingback_url' ) );
	}
}
add_action( 'wp_head', 'brettwysocki_pingback_header' );

/**
 * Enqueue scripts and styles.
 */
function brettwysocki_scripts() {
	
	// Sometimes you need to be fancy.
	wp_enqueue_style( 'brettwysocki-fonts', brettwysocki_fonts_url(), array(), null );

	wp_enqueue_script( 'fontawesome-base', get_bloginfo('template_directory') . '/assets/js/fontawesome.min.js', array(), '5.0.0-beta6' );

	wp_enqueue_script( 'fontawesome-solid', get_bloginfo('template_directory') . '/assets/js/packs/solid.min.js', array('fontawesome-base'), '5.0.0-beta6' );

	// Gotta have style.
	wp_enqueue_style( 'brettwysocki-style', get_stylesheet_uri(), array(),  filemtime( get_stylesheet_directory() . '/style.css' ) );

	// Load the dark colorscheme.
	/*if ( 'dark' === get_theme_mod( 'colorscheme', 'light' ) || is_customize_preview() ) {
		wp_enqueue_style( 'brettwysocki-colors-dark', get_theme_file_uri( '/assets/css/colors-dark.css' ), array( 'brettwysocki-style' ), '1.0' );
	}*/

	wp_localize_script( 'brettwysocki-skip-link-focus-fix', 'brettwysockiScreenReaderText', $brettwysocki_l10n );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

}
add_action( 'wp_enqueue_scripts', 'brettwysocki_scripts' );

function brettwysocki_add_defer_attribute($tag, $handle) {
   // add script handles to the array below
   $scripts_to_defer = array('fontawesome-base', 'fontawesome-solid');
   
   foreach($scripts_to_defer as $defer_script) {
      if ($defer_script === $handle) {
         return str_replace(' src', ' defer="defer" src', $tag);
      }
   }

   return $tag;

}

add_filter('script_loader_tag', 'brettwysocki_add_defer_attribute', 10, 2);

/**
 * Add custom image sizes attribute to enhance responsive image functionality
 * for content images.
 *
 * @since Twenty Seventeen 1.0
 *
 * @param string $sizes A source size value for use in a 'sizes' attribute.
 * @param array  $size  Image size. Accepts an array of width and height
 *                      values in pixels (in that order).
 * @return string A source size value for use in a content image 'sizes' attribute.
 */
function brettwysocki_content_image_sizes_attr( $sizes, $size ) {
	$width = $size[0];

	if ( 740 <= $width ) {
		$sizes = '(max-width: 706px) 89vw, (max-width: 767px) 82vw, 740px';
	}

	if ( is_active_sidebar( 'sidebar-1' ) || is_archive() || is_search() || is_home() || is_page() ) {
		if ( ! ( is_page() && 'one-column' === get_theme_mod( 'page_options' ) ) && 767 <= $width ) {
			 $sizes = '(max-width: 767px) 89vw, (max-width: 1000px) 54vw, (max-width: 1071px) 543px, 580px';
		}
	}

	return $sizes;
}
add_filter( 'wp_calculate_image_sizes', 'brettwysocki_content_image_sizes_attr', 10, 2 );

/**
 * Add custom image sizes attribute to enhance responsive image functionality
 * for post thumbnails.
 *
 * @since Twenty Seventeen 1.0
 *
 * @param array $attr       Attributes for the image markup.
 * @param int   $attachment Image attachment ID.
 * @param array $size       Registered image size or flat array of height and width dimensions.
 * @return string A source size value for use in a post thumbnail 'sizes' attribute.
 */
function brettwysocki_post_thumbnail_sizes_attr( $attr, $attachment, $size ) {
	if ( is_archive() || is_search() || is_home() ) {
		$attr['sizes'] = '(max-width: 767px) 89vw, (max-width: 1000px) 54vw, (max-width: 1071px) 543px, 580px';
	} else {
		$attr['sizes'] = '100vw';
	}

	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'brettwysocki_post_thumbnail_sizes_attr', 10, 3 );


/**
 * My Custom Post Type & Taxonomy Labels
 * 
 * @since Brett_Wysocki 1.0
 * @param string $singular Singular label name
 * @param string $pluarl Plural label name or false if it's just singular with an s
 * @param string $type either Custom Post Type (cpt) or taxonomy
 * @return array $labels Label array
 */
function brettwysocki_get_labels( $singular, $plural = false, $type = 'cpt' ) {

	$labels = array();

	if ( !$plural ) {
		$plural = $singular . 's';
	}

	if ( $type == 'cpt' ) {

		$labels = array(
			'name'               => __( $plural ),
			'singular_name'      => __( $singular ),
			'menu_name'          => __( $plural ),
			'name_admin_bar'     => __( $singular ),
			'add_new'            => __( 'Add New' ),
			'add_new_item'       => __( 'Add New ' . $singular ),
			'new_item'           => __( 'New ' . $singular ),
			'edit_item'          => __( 'Edit ' . $singular ),
			'view_item'          => __( 'View ' . $singular ),
			'all_items'          => __( 'All ' . $plural ),
			'search_items'       => __( 'Search ' . $plural ),
			'parent_item_colon'  => __( 'Parent ' . $plural . ':' ),
			'not_found'          => __( 'No ' . strtolower( $plural ) . ' found.' ),
			'not_found_in_trash' => __( 'No ' . strtolower( $plural ) . ' found in Trash.' )
		);

	} else if ( $type == 'taxonomy' ) {

		$labels = array(
			'name'                       => _x( $plural, 'taxonomy general name' ),
			'singular_name'              => _x( $singular, 'taxonomy singular name' ),
			'search_items'               => __( 'Search ' . $plural ),
			'popular_items'              => __( 'Popular ' . $plural ),
			'all_items'                  => __( 'All ' . $plural ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => __( 'Edit ' . $singular ),
			'update_item'                => __( 'Update ' . $singular ),
			'add_new_item'               => __( 'Add New ' . $singular ),
			'new_item_name'              => __( 'New ' . $singular . ' Name' ),
			'separate_items_with_commas' => __( 'Separate ' . $plural . ' with commas' ),
			'add_or_remove_items'        => __( 'Add or remove ' . $plural ),
			'choose_from_most_used'      => __( 'Choose from the most used ' . $plural ),
			'not_found'                  => __( 'No ' . $plural . ' found.' ),
			'menu_name'                  => __( $plural ),
		);
	
	}

	return $labels;
}

/**
 * My Custom Post Types
 *
 * Creates new custom post types
 *
 * @since Brett_Wysocki 1.0
 * @return null
 */
function brettwysocki_custom_post_types() {

		// Portfolio Item
		register_post_type( 'portfolio-item',
			array(
				'labels' => brettwysocki_get_labels( 'Portfolio Item' ),
				'public' => true,
				'supports' => array( 'title' ),
				'menu_icon' => 'dashicons-location',
			)
		);

}
add_action( 'init', 'brettwysocki_custom_post_types' ); // Uncomment to enable custom post type creation.

/**
 * My Custom Taxonomies
 * 
 * Creates new taxonomies
 * 
 * @since Brett_Wysocki 1.0
 * @return null
 */
function brettwysocki_custom_taxonomies() {

	$args = array(
		'labels'            => brettwysocki_get_labels( 'Portfolio Category', 'Portfolio Categories', 'taxonomy' ),
		'hierarchical'      => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'meta_box_cb'		=> false,
		'rewrite'           => array( 'slug' => 'portfolio-category' ),
	);

	register_taxonomy( 'portfolio-category', array( 'portfolio-item' ), $args );

}
add_action( 'init', 'brettwysocki_custom_taxonomies' ); // Uncomment to enable custom taxonomy creation. 
?>