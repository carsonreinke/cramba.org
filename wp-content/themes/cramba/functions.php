<?php
/**
 * Primer functions and definitions.
 *
 * Set up the theme and provide some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * @link https://codex.wordpress.org/Theme_Development
 * @link https://codex.wordpress.org/Child_Themes
 *
 * For more information on hooks, actions, and filters,
 * {@link https://codex.wordpress.org/Plugin_API}
 *
 * @package Functions
 * @since   1.0.0
 */

/**
 * Primer theme version.
 *
 * @since 1.0.0
 *
 * @var string
 */
define( 'PRIMER_VERSION', '1.8.8' );

/**
 * Minimum WordPress version required for Primer.
 *
 * @since 1.0.0
 *
 * @var string
 */
if ( ! defined( 'PRIMER_MIN_WP_VERSION' ) ) {

	define( 'PRIMER_MIN_WP_VERSION', '4.4' );

}

/**
 * Define the Primer child theme version if undefined.
 *
 * @since 1.5.0
 *
 * @var string
 */
if ( ! defined( 'PRIMER_CHILD_VERSION' ) ) {

	define( 'PRIMER_CHILD_VERSION', '' );

}

/**
 * Load theme translations.
 *
 * Translations can be filed in the /languages/ directory. If you're
 * building a theme based on Primer, use a find and replace to change
 * 'primer' to the name of your theme in all the template files.
 *
 * @link  https://codex.wordpress.org/Function_Reference/load_theme_textdomain
 * @since 1.0.0
 */
load_theme_textdomain( 'primer', get_template_directory() . '/languages' );

/**
 * Enforce the minimum WordPress version requirement.
 *
 * @since 1.0.0
 */
if ( version_compare( get_bloginfo( 'version' ), PRIMER_MIN_WP_VERSION, '<' ) ) {

	require_once get_template_directory() . '/inc/compat/wordpress.php';

}

/**
 * Load deprecated hooks and functions for this theme.
 *
 * @since 1.6.0
 */
require_once get_template_directory() . '/inc/compat/deprecated.php';

/**
 * Load functions for handling special child theme compatibility conditions.
 *
 * @since 1.6.0
 */
require_once get_template_directory() . '/inc/compat/child-themes.php';

/**
 * Load custom helper functions for this theme.
 *
 * @since 1.0.0
 */
require_once get_template_directory() . '/inc/helpers.php';

/**
 * Load custom template tags for this theme.
 *
 * @since 1.0.0
 */
require_once get_template_directory() . '/inc/template-tags.php';

/**
 * Load template parts and override some WordPress defaults.
 *
 * @since 1.0.0
 */
require_once get_template_directory() . '/inc/hooks.php';

/**
 * Load Beaver Builder compatibility file.
 *
 * @since 1.0.0
 */
if ( class_exists( 'FLBuilder' ) ) {

	require_once get_template_directory() . '/inc/compat/beaver-builder.php';

}

/**
 * Load Gutenberg compatiblity.
 *
 * @since 1.8.5
 */
require_once get_template_directory() . '/inc/compat/gutenberg.php';


/**
 * Load Jetpack compatibility file.
 *
 * @since 1.0.0
 */
if ( class_exists( 'Jetpack' ) ) {

	require_once get_template_directory() . '/inc/compat/jetpack.php';

}

/**
 * Load WooCommerce compatibility file.
 *
 * @since 1.0.0
 */
if ( class_exists( 'WooCommerce' ) ) {

	require_once get_template_directory() . '/inc/compat/woocommerce.php';

}

/**
 * Load Customizer class (must be required last).
 *
 * @since 1.0.0
 */
require_once get_template_directory() . '/inc/customizer.php';

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the 'after_setup_theme' hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 *
 * @global array $primer_image_sizes
 * @since  1.0.0
 */
function primer_setup() {

	global $primer_image_sizes;

	/**
	 * Filter registered image sizes.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	$primer_image_sizes = (array) apply_filters(
		'primer_image_sizes',
		array(
			'primer-featured' => array(
				'width'  => 1600,
				'height' => 9999,
				'crop'   => false,
				'label'  => esc_html__( 'Featured', 'primer' ),
			),
			'primer-hero' => array(
				'width'  => 2400,
				'height' => 1300,
				'crop'   => array( 'center', 'center' ),
				'label'  => esc_html__( 'Hero', 'primer' ),
			),
		)
	);

	foreach ( $primer_image_sizes as $name => &$args ) {

		if ( empty( $name ) || empty( $args['width'] ) || empty( $args['height'] ) ) {

			unset( $primer_image_sizes[ $name ] );

			continue;

		}

		$args['crop']  = ! empty( $args['crop'] ) ? $args['crop'] : false;
		$args['label'] = ! empty( $args['label'] ) ? $args['label'] : ucwords( str_replace( array( '-', '_' ), ' ', $name ) );

		add_image_size(
			sanitize_key( $name ),
			absint( $args['width'] ),
			absint( $args['height'] ),
			$args['crop']
		);

	}

	if ( $primer_image_sizes ) {

		add_filter( 'image_size_names_choose', 'primer_image_size_names_choose' );

	}

	/**
	 * Enable support for Automatic Feed Links.
	 *
	 * @link  https://developer.wordpress.org/reference/functions/add_theme_support/#feed-links
	 * @since 1.0.0
	 */
	add_theme_support( 'automatic-feed-links' );

	/**
	 * Enable support for plugins and themes to manage the document title tag.
	 *
	 * @link  https://developer.wordpress.org/reference/functions/add_theme_support/#title-tag
	 * @since 1.0.0
	 */
	add_theme_support( 'title-tag' );

	/**
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link  https://developer.wordpress.org/reference/functions/add_theme_support/#post-thumbnails
	 * @since 1.0.0
	 */
	add_theme_support( 'post-thumbnails' );

	/**
	 * Enable support for customizer selective refresh.
	 *
	 * @link  https://developer.wordpress.org/reference/functions/add_theme_support/#customize-selective-refresh-widgets
	 * @since 1.0.0
	 */
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Register custom Custom Navigation Menus.
	 *
	 * @link  https://developer.wordpress.org/reference/functions/register_nav_menus/
	 * @since 1.0.0
	 */
	register_nav_menus(
		/**
		 * Filter registered nav menus.
		 *
		 * @since 1.0.0
		 *
		 * @var array
		 */
		(array) apply_filters(
			'primer_nav_menus',
			array(
				'primary' => esc_html__( 'Primary Menu', 'primer' ),
				'social'  => esc_html__( 'Social Menu', 'primer' ),
				'footer'  => esc_html__( 'Footer Menu', 'primer' ),
			)
		)
	);

	/**
	 * Enable support for HTML5 markup.
	 *
	 * @link  https://codex.wordpress.org/Function_Reference/add_theme_support#HTML5
	 * @since 1.0.0
	 */
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		)
	);

	/**
	 * Enable support for Post Formats.
	 *
	 * @link  https://codex.wordpress.org/Function_Reference/add_theme_support#Post_Formats
	 * @since 1.0.0
	 */
	add_theme_support(
		'post-formats',
		array(
			'aside',
			'image',
			'video',
			'quote',
			'link',
		)
	);

	/**
	 * Enable support for AMP.
	 *
	 * This is only done for version v1.2 and greater because in this version support was added for CSS tree shaking
	 * and the AMP toggleClass action. Also, this is the version that adds the ability to switch the mode even when
	 * the theme support flag is present.
	 *
	 * @link  https://amp-wp.org
	 * @since 1.9
	 */
	if ( defined( 'AMP__VERSION' ) && version_compare( strtok( AMP__VERSION, '-' ), '1.2', '>=' ) ) {

		add_theme_support(
			'amp',
			array(
				// Works in Standard and Transitional modes.
				'paired' => true,
			)
		);

	}

}
add_action( 'after_setup_theme', 'primer_setup' );

/**
 * Register image size labels.
 *
 * @filter image_size_names_choose
 * @since  1.0.0
 *
 * @param  array $size_names Array of image sizes and their names.
 *
 * @return array
 */
function primer_image_size_names_choose( $size_names ) {

	global $primer_image_sizes;

	$labels = array_combine(
		array_keys( $primer_image_sizes ),
		wp_list_pluck( $primer_image_sizes, 'label' )
	);

	return array_merge( $size_names, $labels );

}

/**
 * Sets the content width in pixels, based on the theme layout.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @action after_setup_theme
 * @global int $content_width
 * @since  1.0.0
 */
function primer_content_width() {

	$layout        = primer_get_layout();
	$content_width = ( 'one-column-wide' === $layout ) ? 1068 : 688;

	/**
	 * Filter the content width in pixels.
	 *
	 * @since 1.0.0
	 *
	 * @param string $layout
	 *
	 * @var int
	 */
	$GLOBALS['content_width'] = (int) apply_filters( 'primer_content_width', $content_width, $layout );

}
add_action( 'after_setup_theme', 'primer_content_width', 0 );

/**
 * Enable support for custom editor styles if using the classic editor.
 *
 * @link  https://developer.wordpress.org/reference/functions/add_editor_style/
 * @since 1.0.0
 */
function primer_classic_editor_styles() {

	// Return if the block editor is not found.
	if ( ! function_exists( 'register_block_type' ) ) {

		return;

	}

	// Add editor styles for the classic editor.
	if ( ! get_current_screen()->is_block_editor() ) {

		add_editor_style( 'editor-style.css' );

	}

}
add_action( 'admin_print_styles', 'primer_classic_editor_styles', 10, 0 );

/**
 * Register sidebar areas.
 *
 * @link  http://codex.wordpress.org/Function_Reference/register_sidebar
 * @since 1.0.0
 */
function primer_register_sidebars() {

	/**
	 * Filter registered sidebars areas.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	$sidebars = (array) apply_filters(
		'primer_sidebars',
		array(
			'sidebar-1' => array(
				'name'          => esc_html__( 'Sidebar', 'primer' ),
				'description'   => esc_html__( 'The primary sidebar appears alongside the content of every page, post, archive, and search template.', 'primer' ),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h4 class="widget-title">',
				'after_title'   => '</h4>',
			),
			'sidebar-2' => array(
				'name'          => esc_html__( 'Secondary Sidebar', 'primer' ),
				'description'   => esc_html__( 'The secondary sidebar will only appear when you have selected a three-column layout.', 'primer' ),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h4 class="widget-title">',
				'after_title'   => '</h4>',
			),
			'footer-1' => array(
				'name'          => esc_html__( 'Footer 1', 'primer' ),
				'description'   => esc_html__( 'This sidebar is the first column of the footer widget area.', 'primer' ),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h4 class="widget-title">',
				'after_title'   => '</h4>',
			),
			'footer-2' => array(
				'name'          => esc_html__( 'Footer 2', 'primer' ),
				'description'   => esc_html__( 'This sidebar is the second column of the footer widget area.', 'primer' ),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h4 class="widget-title">',
				'after_title'   => '</h4>',
			),
			'footer-3' => array(
				'name'          => esc_html__( 'Footer 3', 'primer' ),
				'description'   => esc_html__( 'This sidebar is the third column of the footer widget area.', 'primer' ),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h4 class="widget-title">',
				'after_title'   => '</h4>',
			),
			'hero' => array(
				'name'          => esc_html__( 'Hero', 'primer' ),
				'description'   => esc_html__( 'Hero widgets appear over the header image on the front page.', 'primer' ),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h2 class="widget-title">',
				'after_title'   => '</h2>',
			),
		)
	);

	foreach ( $sidebars as $id => $args ) {

		register_sidebar( array_merge( array( 'id' => $id ), $args ) );

	}

}
add_action( 'widgets_init', 'primer_register_sidebars' );

/**
 * Register Primer widgets.
 *
 * @link  http://codex.wordpress.org/Function_Reference/register_widget
 * @since 1.6.0
 */
function primer_register_widgets() {

	require_once get_template_directory() . '/inc/hero-text-widget.php';

	register_widget( 'Primer_Hero_Text_Widget' );

}
add_action( 'widgets_init', 'primer_register_widgets' );

/**
 * Enqueue theme scripts and styles.
 *
 * @link  https://codex.wordpress.org/Function_Reference/wp_enqueue_style
 * @link  https://codex.wordpress.org/Function_Reference/wp_enqueue_script
 * @since 1.0.0
 */
function primer_scripts() {

	$stylesheet = get_stylesheet();
	$suffix     = SCRIPT_DEBUG ? '' : '.min';

	wp_enqueue_style( $stylesheet, get_stylesheet_uri(), false, defined( 'PRIMER_CHILD_VERSION' ) ? PRIMER_CHILD_VERSION : PRIMER_VERSION );

	wp_style_add_data( $stylesheet, 'rtl', 'replace' );

	$nav_dependencies = ( is_front_page() && function_exists( 'has_header_video' ) && has_header_video() ) ? array( 'jquery', 'wp-custom-header' ) : array( 'jquery' );

	// The interactivity of the menu in AMP is defined inline.
	if ( ! primer_is_amp() ) {

		wp_enqueue_script( 'primer-navigation', get_template_directory_uri() . "/assets/js/navigation{$suffix}.js", $nav_dependencies, PRIMER_VERSION, true );

	}

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) && ! primer_is_amp() ) {

		wp_enqueue_script( 'comment-reply' );

	}

	if ( primer_has_hero_image() ) {

		$css = sprintf(
			SCRIPT_DEBUG ? '%s { background-image: url(%s); }' : '%s{background-image:url(%s);}',
			primer_get_hero_image_selector(),
			esc_url( primer_get_hero_image() )
		);

		wp_add_inline_style( $stylesheet, $css );

	}

}
add_action( 'wp_enqueue_scripts', 'primer_scripts' );

/**
 * Fix skip link focus in IE11.
 *
 * This does not enqueue the script because it is tiny and because it is only for IE11,
 * thus it does not warrant having an entire dedicated blocking script being loaded.
 * This minified code comes from the Twenty Nineteen theme; refer to the theme for the
 * unminified source. Since IE11 marketshare is very small, this code can likely be
 * eliminated entirely soon.
 *
 * @see twentynineteen_skip_link_focus_fix()
 * @link https://git.io/vWdr2
 */
function primer_skip_link_focus_fix() {

	// Skip enqueueing skip-focus-link script since part of the AMP. See <https://github.com/ampproject/amphtml/pull/19037>.
	if ( primer_is_amp() ) {
		return;
	}

	?>
	<script>
	/* IE11 skip link focus fix */
	/(trident|msie)/i.test(navigator.userAgent)&&document.getElementById&&window.addEventListener&&window.addEventListener("hashchange",function(){var t,e=location.hash.substring(1);/^[A-z0-9_-]+$/.test(e)&&(t=document.getElementById(e))&&(/^(?:a|select|input|button|textarea)$/i.test(t.tagName)||(t.tabIndex=-1),t.focus())},!1);
	</script>
	<?php
}
add_action( 'wp_print_footer_scripts', 'primer_skip_link_focus_fix' );

/**
 * Sets the authordata global when viewing an author archive.
 *
 * This provides backwards compatibility with
 * http://core.trac.wordpress.org/changeset/25574
 *
 * It removes the need to call `the_post()` and `rewind_posts()`
 * in an author template to print information about the author.
 *
 * @action wp
 * @global WP_Query $wp_query
 * @global WP_User  $authordata
 * @since  1.0.0
 */
function primer_setup_author() {

	global $wp_query, $authordata;

	if ( $wp_query->is_author() && isset( $wp_query->post ) ) {

		$authordata = get_userdata( $wp_query->post->post_author ); // override ok.

	}

}
add_action( 'wp', 'primer_setup_author' );

/**
 * Reset the transient for the active categories check.
 *
 * @action create_category
 * @action edit_category
 * @action delete_category
 * @action save_post
 * @see    primer_has_active_categories()
 * @since  1.0.0
 */
function primer_has_active_categories_reset() {

	delete_transient( 'primer_has_active_categories' );

}
add_action( 'create_category', 'primer_has_active_categories_reset' );
add_action( 'edit_category', 'primer_has_active_categories_reset' );
add_action( 'delete_category', 'primer_has_active_categories_reset' );
add_action( 'save_post', 'primer_has_active_categories_reset' );

/**
 * Move some elements around.
 *
 * @action template_redirect
 * @since  1.0.0
 */
function lyrical_move_elements() {

	remove_action( 'primer_after_header', 'primer_add_primary_navigation', 11 );
	remove_action( 'primer_after_header', 'primer_add_page_title',         12 );
	remove_action( 'primer_header', 'primer_add_site_title',               5 );

	add_action( 'primer_header', 'primer_add_site_title',         5 );
	add_action( 'primer_header', 'primer_add_primary_navigation', 5 );

	if ( ! is_front_page() || ! is_active_sidebar( 'hero' ) ) {

		add_action( 'primer_hero', 'primer_add_page_title', 12 );

	}

}
add_action( 'template_redirect', 'lyrical_move_elements' );

/**
 * Set custom logo args.
 *
 * @filter primer_custom_logo_args
 * @since  1.0.0
 *
 * @param  array $args
 *
 * @return array
 */
function lyrical_custom_logo_args( $args ) {

	$args['width']  = 325;
	$args['height'] = 100;

	return $args;

}
add_filter( 'primer_custom_logo_args', 'lyrical_custom_logo_args' );

/**
 * Display author avatar over the post thumbnail.
 *
 * @action primer_after_post_thumbnail
 * @since  1.0.0
 */
function lyrical_add_author_avatar() {

	?>
	<div class="avatar-container">

		<?php echo get_avatar( get_the_author_meta( 'user_email' ), '128' ); ?>

	</div>
	<?php

}
add_action( 'primer_after_post_thumbnail', 'lyrical_add_author_avatar' );

/**
 * Set fonts.
 *
 * @filter primer_fonts
 * @since  1.0.0
 *
 * @param  array $fonts
 *
 * @return array
 */
function lyrical_fonts( $fonts ) {

	$fonts[] = 'Playfair Display';
	$fonts[] = 'Raleway';

	return $fonts;

}
add_filter( 'primer_fonts', 'lyrical_fonts' );

/**
 * Set font types.
 *
 * @filter primer_font_types
 * @since  1.0.0
 *
 * @param  array $font_types
 *
 * @return array
 */
function lyrical_font_types( $font_types ) {

	$overrides = array(
		'site_title_font' => array(
			'default' => 'Playfair Display',
		),
		'navigation_font' => array(
			'default' => 'Raleway',
		),
		'heading_font' => array(
			'default' => 'Raleway',
		),
		'primary_font' => array(
			'default' => 'Raleway',
		),
		'secondary_font' => array(
			'default' => 'Raleway',
		),
	);

	return primer_array_replace_recursive( $font_types, $overrides );

}
add_filter( 'primer_font_types', 'lyrical_font_types' );

/**
 * Set colors.
 *
 * @filter primer_colors
 * @since  1.0.0
 *
 * @param  array $colors
 *
 * @return array
 */
function lyrical_colors( $colors ) {

	unset(
		$colors['menu_background_color'],
		$colors['footer_widget_content_background_color']
	);

	$overrides = array(
		/**
		 * Text colors
		 */
		'header_textcolor' => array(
			'default' => '#ffffff',
		),
		'tagline_text_color' => array(
			'default' => '#ffffff',
		),
		'hero_text_color' => array(
			'default' => '#ffffff',
		),
		'menu_text_color' => array(
			'default' => '#ffffff',
		),
		'menu_dropdown_background_color' => array(
			'label'   => esc_html__( 'Dropdown Background', 'primer' ),
			'default' => '#1985a1',
			'section' => 'colors-menu',
			'css'     => array(
				'.main-navigation li li a,
				.menu-toggle:not( [style*="display: none"] ) + .main-navigation,
				.menu-toggle:not( [style*="display: none"] ) + .main-navigation .expand' => array(
					'background-color' => '%1$s',
				),
			),
			'rgba_css' => array(
				'.main-navigation li li a:hover,
				.main-navigation li li a:visited:hover' => array(
					'background-color' => 'rgba(%1$s, 0.8)',
				),
			),
		),
		'heading_text_color' => array(
			'default' => '#353535',
		),
		'primary_text_color' => array(
			'default' => '#252525',
		),
		'secondary_text_color' => array(
			'default' => '#686868',
		),
		'footer_widget_heading_text_color' => array(
			'default' => '#ffffff',
		),
		'footer_widget_text_color' => array(
			'default' => '#ffffff',
		),
		'footer_menu_text_color' => array(
			'default' => '#686868',
		),
		'footer_text_color' => array(
			'default' => '#686868',
		),
		/**
		 * Link / Button colors
		 */
		'link_color' => array(
			'default'  => '#4c99ba',
		),
		'button_color' => array(
			'default'  => '#4c99ba',
			'css'     => array(
				'.woocommerce-cart-menu-item .woocommerce.widget_shopping_cart p.buttons a' => array(
					'background-color' => '%1$s',
				),
			),
		),
		'button_text_color' => array(
			'default'  => '#ffffff',
		),
		/**
		 * Background colors
		 */
		'background_color' => array(
			'default' => '#f5f5f5',
		),
		'content_background_color' => array(
			'default' => '#ffffff',
		),
		'hero_background_color' => array(
			'default' => '#141414',
		),
		'footer_widget_background_color' => array(
			'default' => '#141414',
		),
		'footer_background_color' => array(
			'default' => '#2d2d2d',
		),
	);

	return primer_array_replace_recursive( $colors, $overrides );

}
add_filter( 'primer_colors', 'lyrical_colors' );

/**
 * Set color schemes.
 *
 * @filter primer_color_schemes
 * @since  1.0.0
 *
 * @param  array $color_schemes
 *
 * @return array
 */
function lyrical_color_schemes( $color_schemes ) {

	$overrides = array(
		'blush' => array(
			'colors' => array(
				'link_color'                     => $color_schemes['blush']['base'],
				'button_color'                   => $color_schemes['blush']['base'],
				'menu_dropdown_background_color' => $color_schemes['blush']['base'],
			),
		),
		'bronze' => array(
			'colors' => array(
				'link_color'                     => $color_schemes['bronze']['base'],
				'button_color'                   => $color_schemes['bronze']['base'],
				'menu_dropdown_background_color' => $color_schemes['bronze']['base'],
			),
		),
		'canary' => array(
			'colors' => array(
				'link_color'                     => $color_schemes['canary']['base'],
				'button_color'                   => $color_schemes['canary']['base'],
				'menu_dropdown_background_color' => $color_schemes['canary']['base'],
			),
		),
		'cool' => array(
			'colors' => array(
				'link_color'                     => $color_schemes['cool']['base'],
				'button_color'                   => $color_schemes['cool']['base'],
				'menu_dropdown_background_color' => $color_schemes['cool']['base'],
			),
		),
		'dark' => array(
			'colors' => array(
				// Text
				'tagline_text_color'               => '#999999',
				'heading_text_color'               => '#ffffff',
				'primary_text_color'               => '#e5e5e5',
				'secondary_text_color'             => '#c1c1c1',
				'footer_widget_heading_text_color' => '#ffffff',
				'footer_widget_text_color'         => '#ffffff',
				// Backgrounds
				'background_color'               => '#222222',
				'content_background_color'       => '#2d2d2d',
				'hero_background_color'          => '#141414',
				'footer_widget_background_color' => '#141414',
				'footer_background_color'        => '#2d2d2d',
				'menu_dropdown_background_color' => $color_schemes['dark']['base'],
			),
		),
		'iguana' => array(
			'colors' => array(
				'link_color'                     => $color_schemes['iguana']['base'],
				'button_color'                   => $color_schemes['iguana']['base'],
				'menu_dropdown_background_color' => $color_schemes['iguana']['base'],
			),
		),
		'muted' => array(
			'colors' => array(
				// Text
				'heading_text_color'     => '#4f5875',
				'primary_text_color'     => '#4f5875',
				'secondary_text_color'   => '#888c99',
				'footer_menu_text_color' => $color_schemes['muted']['base'],
				'footer_text_color'      => '#4f5875',
				// Links & Buttons
				'link_color'   => $color_schemes['muted']['base'],
				'button_color' => $color_schemes['muted']['base'],
				// Backgrounds
				'background_color'               => '#d5d6e0',
				'hero_background_color'          => '#5a6175',
				'menu_background_color'          => '#5a6175',
				'menu_dropdown_background_color' => $color_schemes['muted']['base'],
				'footer_widget_background_color' => '#b6b9c5',
				'footer_background_color'        => '#d5d6e0',

			),
		),
		'plum' => array(
			'colors' => array(
				'link_color'                     => $color_schemes['plum']['base'],
				'button_color'                   => $color_schemes['plum']['base'],
				'menu_dropdown_background_color' => $color_schemes['plum']['base'],
			),
		),
		'rose' => array(
			'colors' => array(
				'link_color'                     => $color_schemes['rose']['base'],
				'button_color'                   => $color_schemes['rose']['base'],
				'menu_dropdown_background_color' => $color_schemes['rose']['base'],
			),
		),
		'tangerine' => array(
			'colors' => array(
				'link_color'                     => $color_schemes['tangerine']['base'],
				'button_color'                   => $color_schemes['tangerine']['base'],
				'menu_dropdown_background_color' => $color_schemes['tangerine']['base'],
			),
		),
		'turquoise' => array(
			'colors' => array(
				'link_color'                     => $color_schemes['turquoise']['base'],
				'button_color'                   => $color_schemes['turquoise']['base'],
				'menu_dropdown_background_color' => $color_schemes['turquoise']['base'],
			),
		),
	);

	return primer_array_replace_recursive( $color_schemes, $overrides );

}
add_filter( 'primer_color_schemes', 'lyrical_color_schemes' );
