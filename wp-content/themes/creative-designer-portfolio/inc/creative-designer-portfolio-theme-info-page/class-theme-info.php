<?php
/**
 * Theme Info Page
 *
 * @package Creative Designer Portfolio
 */

function creative_designer_portfolio_theme_details() {
	add_theme_page( 'Themes', 'Creative Designer Portfolio Theme', 'edit_theme_options', 'creative-designer-portfolio-theme-info-page', 'theme_details_display', null );
}
add_action( 'admin_menu', 'creative_designer_portfolio_theme_details' );

function theme_details_display() {

	include_once 'templates/theme-details.php';

}

add_action( 'admin_enqueue_scripts', 'creative_designer_portfolio_theme_details_style' );

function creative_designer_portfolio_theme_details_style() {
    wp_register_style( 'creative_designer_portfolio_theme_details_css', get_template_directory_uri() . '/inc/creative-designer-portfolio-theme-info-page/css/theme-details.css', false, '1.0.0' );
    wp_enqueue_style( 'creative_designer_portfolio_theme_details_css' );

    wp_register_script( 'creative_designer_portfolio_copy_coupon_js', get_template_directory_uri() . '/inc/creative-designer-portfolio-theme-info-page/js/creative-designer-portfolio-copy-coupon.js', array(), '1.0.0', true );
    wp_enqueue_script( 'creative_designer_portfolio_copy_coupon_js' );
}