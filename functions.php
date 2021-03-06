<?php

// Enqueue JS scripts and CSS styles for the theme
function wpbs_styles_scripts() {
    global $redux_demo;
    if (!empty($redux_demo['stylesheet']) && $redux_demo['stylesheet'] != 'bootstrap')
        wp_enqueue_style( 'bootstrap', '//netdna.bootstrapcdn.com/bootswatch/latest/' . $redux_demo['stylesheet'] . '/bootstrap.min.css' );
    else
        wp_enqueue_style( 'bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/latest/css/bootstrap.min.css' );

    wp_enqueue_style( 'font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css' );
    wp_enqueue_style( 'mmenu', get_template_directory_uri() . '/assets/css/jquery.mmenu.css' );
    wp_enqueue_style( 'theme-style', get_stylesheet_uri() );

    if (!empty($redux_demo['css-code']))
        wp_add_inline_style( 'theme-style', $redux_demo['css-code'] );

    // Enqueue JS scripts globally
    wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/latest/js/bootstrap.min.js', array( 'jquery' ), false, true );
    wp_enqueue_script( 'mmenu', get_template_directory_uri() . '/assets/js/jquery.mmenu.min.js', array('jquery'), '4.7.5', true );
    
	// Enqueue JS scipts for all pages except the front_page(home page)
    if (! is_front_page()) {
        wp_enqueue_script( 'share', get_template_directory_uri() . '/assets/js/share.min.js', array(), '0.5.0', true );
        wp_enqueue_script( 'scroll-nav', get_template_directory_uri() . '/assets/js/jquery.scrollNav.min.js', array( 'jquery' ), '2.4.0', true );
        wp_enqueue_script( 'scroll-to-fixed', get_template_directory_uri() . '/assets/js/jquery-scrolltofixed-min.js', array( 'jquery' ), '1.0.6', true );
        wp_enqueue_script( 'validate', get_template_directory_uri() . '/assets/js/jquery.validate.min.js', array( 'jquery' ), '1.13.1', true );
    }
    wp_enqueue_script( 'wpbs', get_template_directory_uri() . '/assets/js/wpbs.js', array( 'jquery' ), '1.1.0', true );
}
add_action( 'wp_enqueue_scripts', 'wpbs_styles_scripts' );

// If WP Admin-bar is shown, then apply CSS to avoid conflict with fixed-top-navbar
function wpbs_adminbar_css() {
    if ( is_admin_bar_showing() ) { 
       echo '<style  type="text/css">
				@media (max-width: 767px) {
					.mm-fixed-top { top: 46px !important; }
					html.mm-opened body { margin-top: -46px; }
					#wpadminbar { position: fixed; }
				}
				@media (min-width: 768px) {
					.navbar-fixed-top {  margin-top:32px;}
					html.mm-opened body { margin-top: -32px; }
				}
			</style>';
    } 
}
add_filter( 'wp_head', 'wpbs_adminbar_css' );


function wpbs_setup() {
    // Switch default core markup for search form, comment form, and comments to output valid HTML5. 
    add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list' ) );
	
    // Enable support for Post Thumbnails
    add_theme_support( 'post-thumbnails' );
	
    // Add RSS feed links to <head> for posts and comments.
    add_theme_support( 'automatic-feed-links' );
}
add_action( 'after_setup_theme', 'wpbs_setup' );

// Register bootstrap navigation walker
require get_template_directory() . '/includes/wp_bootstrap_navwalker.php';

// Register theme components - Menus, Widgets and Sidebars
require get_template_directory() . '/includes/core-components.php';

// Template tags for Post Thumbnails
require get_template_directory() . '/includes/post-thumbnails.php';

// Template tags for Pagination & Post Navigation.
require get_template_directory() . '/includes/pagination.php';

// Template tags for Comments
require get_template_directory() . '/includes/comments-list.php';

// Template tags for excerpts (search results, blog list etc.)
require get_template_directory() . '/includes/excerpts.php';

//  Register TGM_Plugin_Activation class to install Redux Framework.
require_once dirname( __FILE__ ) . '/includes/tgm-plugin-activation.php';

function redux_register_required_plugins() {
	$plugin = array(
		'name' 			=> 'Redux Framework',
		'slug' 			=> 'redux-framework',
		'required' 		=> true,
	);
	$config = array(
		'has_notices'     	=> true,                       	
		'is_automatic'    	=> true,					   
		'nag_type'			=> 'error' 
	);
	tgmpa( array($plugin), $config );
}
add_action( 'tgmpa_register', 'redux_register_required_plugins' );

// Redux Framework config for STAGE
require_once(dirname(__FILE__).'/includes/redux-config.php');