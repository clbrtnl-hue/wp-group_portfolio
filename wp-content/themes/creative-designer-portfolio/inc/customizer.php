<?php
/**
 * Customizer
 * 
 * @package WordPress
 * @subpackage Creative Designer Portfolio
 * @since Creative Designer Portfolio 1.7
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function creative_designer_portfolio_customize_register( $wp_customize ) {
    // Check for existence of WP_Customize_Manager before proceeding
	if ( ! class_exists( 'WP_Customize_Manager' ) ) {
        return;
    }
    
	$wp_customize->add_section( new Creative_Designer_Portfolio_Customizer_Pro_Button( $wp_customize, 'upsell_premium_section', array(
		'title'       => __( 'Creative Designer Portfolio Pro', 'creative-designer-portfolio' ),
		'button_text' => __( 'Buy Pro Theme', 'creative-designer-portfolio' ),
		'url'         => esc_url( CREATIVE_DESIGNER_PORTFOLIO_BUY_NOW ),
		'priority'    => 0,
	)));

	$wp_customize->add_section( new Creative_Designer_Portfolio_Customizer_Pro_Button( $wp_customize, 'upsell_bundle_section', array(
		'title'       => __( 'Get All Themes', 'creative-designer-portfolio' ),
		'button_text' => __( 'Buy Now', 'creative-designer-portfolio' ),
		'url'         => esc_url( CREATIVE_DESIGNER_PORTFOLIO_BUNDLE_LINK ),
		'priority'    => 0,
	)));

}
add_action( 'customize_register', 'creative_designer_portfolio_customize_register' );

if ( class_exists( 'WP_Customize_Section' ) ) {
	class Creative_Designer_Portfolio_Customizer_Pro_Button extends WP_Customize_Section {
		public $type = 'creative-designer-portfolio-buynow';
		public $button_text = '';
		public $url = '';

		protected function render() {
			?>
			<li id="accordion-section-<?php echo esc_attr( $this->id ); ?>" class="creative_designer_portfolio_customizer_pro_button accordion-section control-section control-section-<?php echo esc_attr( $this->id ); ?> cannot-expand">
				<h3 class="accordion-section-title premium-details">
					<?php echo esc_html( $this->title ); ?>
					<a href="<?php echo esc_url( $this->url ); ?>" class="button button-secondary alignright" target="_blank" style="margin-top: -4px;"><?php echo esc_html( $this->button_text ); ?></a>
				</h3>
			</li>
			<?php
		}
	}
}

/**
 * Enqueue script for custom customize control.
 */
function creative_designer_portfolio_custom_control_scripts() {
	wp_enqueue_script( 'creative-designer-portfolio-custom-controls-js', get_template_directory_uri() . '/assets/js/custom-controls.js', array( 'jquery', 'jquery-ui-core', 'jquery-ui-sortable' ), '1.0', true );

    wp_enqueue_style( 'creative-designer-portfolio-customizer-css', get_template_directory_uri() . '/assets/css/customizer.css', array(), '1.0' );
}
add_action( 'customize_controls_enqueue_scripts', 'creative_designer_portfolio_custom_control_scripts' );
