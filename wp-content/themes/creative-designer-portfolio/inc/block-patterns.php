<?php   
/**
 * Block Patterns
 *
 * @package Creative Designer Portfolio
 * @since 1.0
 */

/**
 * Registers block patterns and categories.
 *
 * @since 1.0
 *
 * @return void
 */
function creative_designer_portfolio_register_block_patterns() {
	$creative_designer_portfolio_block_pattern_categories = array(
		'creative-designer-portfolio' => array( 'label' => esc_html__( 'Creative Designer Portfolio Patterns', 'creative-designer-portfolio' ) ),
		'pages'    => array( 'label' => esc_html__( 'Pages', 'creative-designer-portfolio' ) ),
	);

	$creative_designer_portfolio_block_pattern_categories = apply_filters( 'creative_designer_portfolio_block_pattern_categories', $creative_designer_portfolio_block_pattern_categories );

	foreach ( $creative_designer_portfolio_block_pattern_categories as $creative_designer_portfolio_name => $creative_designer_portfolio_properties ) {
		if ( ! WP_Block_Pattern_Categories_Registry::get_instance()->is_registered( $creative_designer_portfolio_name ) ) {
			register_block_pattern_category( $creative_designer_portfolio_name, $creative_designer_portfolio_properties );
		}
	}

	$creative_designer_portfolio_block_patterns = array(
		'header-default',
		'banner-section',
		'project-section',
		'post-one-column',
		'post-two-column',
		'inner-banner',
		'archive-inner-banner',
		'search-banner',
		'hidden-404',
		'sidebar',
		'footer-default',
	);

	$creative_designer_portfolio_block_patterns = apply_filters( 'creative_designer_portfolio_block_patterns', $creative_designer_portfolio_block_patterns );

	foreach ( $creative_designer_portfolio_block_patterns as $creative_designer_portfolio_block_pattern ) {
		$creative_designer_portfolio_pattern_file = get_parent_theme_file_path( '/inc/patterns/' . $creative_designer_portfolio_block_pattern . '.php' );

		register_block_pattern(
			'creative-designer-portfolio/' . $creative_designer_portfolio_block_pattern,
			require $creative_designer_portfolio_pattern_file
		);
	}
}
add_action( 'init', 'creative_designer_portfolio_register_block_patterns', 9 );