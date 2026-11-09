<?php
/**
 * Creative Designer Portfolio functions and definitions
 *
 * @package Creative Designer Portfolio
 * @since 1.0
 */

if ( ! function_exists( 'creative_designer_portfolio_support' ) ) :
	function creative_designer_portfolio_support() {
		
		load_theme_textdomain( 'creative-designer-portfolio', get_template_directory() . '/languages' );

		add_theme_support( 'html5', array(
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		add_theme_support( 'custom-background', apply_filters( 'creative_designer_portfolio_custom_background', array(
            'default-color' => 'ffffff',
            'default-image' => '',
        )));
		
		add_theme_support( 'wp-block-styles' );

		add_editor_style( 'style.css' );

		define('CREATIVE_DESIGNER_PORTFOLIO_BUY_NOW',__('https://www.themescarts.com/products/designer-portfolio-WordPress-theme/','creative-designer-portfolio'));
		define('CREATIVE_DESIGNER_PORTFOLIO_FOOTER_BUY_NOW',__('https://www.themescarts.com/products/creative-designer-portfolio/','creative-designer-portfolio'));
		define('CREATIVE_DESIGNER_PORTFOLIO_BUNDLE_LINK',__('https://www.themescarts.com/products/WordPress-theme-bundle/','creative-designer-portfolio'));

	}
endif;
add_action( 'after_setup_theme', 'creative_designer_portfolio_support' );

/*-------------------------------------------------------------
 Enqueue Styles
--------------------------------------------------------------*/

if ( ! function_exists( 'creative_designer_portfolio_styles' ) ) :
	function creative_designer_portfolio_styles() {
		// Register theme stylesheet.
		wp_enqueue_style('creative-designer-portfolio-style', get_stylesheet_uri(), array(), wp_get_theme()->get('version') );
		wp_enqueue_style('creative-designer-portfolio-style-blocks', get_template_directory_uri(). '/assets/css/blocks.css');
		wp_enqueue_style('creative-designer-portfolio-style-responsive', get_template_directory_uri(). '/assets/css/responsive.css');
		wp_style_add_data( 'creative-designer-portfolio-style', 'rtl', 'replace' );
		wp_enqueue_script( 'wow-js', get_theme_file_uri( '/assets/js/wow.js' ), array( 'jquery' ), wp_get_theme()->get('Version'), true );
		wp_enqueue_style( 'animate-css', get_template_directory_uri().'/assets/css/animate.css', array(), wp_get_theme()->get('Version') );
		wp_enqueue_style( 'fontawesome', get_template_directory_uri() . '/assets/font-awesome/css/all.css', array(), '7.0.0' );
		wp_enqueue_script( 'creative-designer-portfolio-custom-js', get_theme_file_uri( '/assets/js/custom.js' ), array( 'jquery' ), true );

		wp_enqueue_style( 'dashicons' );
	}
endif;
add_action( 'wp_enqueue_scripts', 'creative_designer_portfolio_styles' );

function creative_designer_portfolio_enqueue_admin_script($hook) {
    // Enqueue admin JS for notices
    wp_enqueue_script('creative-designer-portfolio-welcome-notice', get_template_directory_uri() . '/inc/creative-designer-portfolio-theme-info-page/js/creative-designer-portfolio-welcome-notice.js', array('jquery'), '', true);
    
    // Localize script to pass data to JavaScript
    wp_localize_script('creative-designer-portfolio-welcome-notice', 'creative_designer_portfolio_localize', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('creative_designer_portfolio_welcome_nonce'),
        'dismiss_nonce' => wp_create_nonce('creative_designer_portfolio_welcome_nonce'), // Nonce for dismissal
        'redirect_url' => admin_url('themes.php?page=creative-designer-portfolio-theme-info-page')
    ));
}
add_action('admin_enqueue_scripts', 'creative_designer_portfolio_enqueue_admin_script');

if (!function_exists('creative_designer_portfolio_enable_plugin_autoupdate')) {

    add_filter('auto_update_plugin', function ($update, $item) {
        if ($item->slug === 'siteready-coming-soon-under-construction') {
            return true;
        }
        return $update;
    }, 10, 2);

}

function creative_designer_portfolio_plugin_update_available($slug, $file) {
    $updates = get_site_transient('update_plugins');

    if (!isset($updates->response[$slug . '/' . $file])) {
        return false; // No update available
    }

    return $updates->response[$slug . '/' . $file];
}

require get_template_directory() .'/inc/TGM/tgm.php';

// Add block patterns
require get_template_directory() . '/inc/block-patterns.php';

require_once get_theme_file_path( 'inc/creative-designer-portfolio-theme-info-page/templates/class-theme-notice.php' );

require_once get_theme_file_path( 'inc/creative-designer-portfolio-theme-info-page/class-theme-info.php' );

require_once get_theme_file_path( '/inc/customizer.php' );	
?>