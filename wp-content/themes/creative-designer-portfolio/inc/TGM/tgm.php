<?php
	
load_template( get_template_directory() . '/inc/TGM/class-tgm-plugin-activation.php' );

/**
 * Recommended plugins.
 */
function creative_designer_portfolio_register_recommended_plugins() {
	$plugins = array(
		array(
			'name'      => esc_html__( 'Siteready Coming Soon Under Construction', 'creative-designer-portfolio' ),
			'slug'      => 'siteready-coming-soon-under-construction',
			'required'  => false,
		)
	);
	$config = array();
	creative_designer_portfolio_tgmpa( $plugins, $config );
}
add_action( 'tgmpa_register', 'creative_designer_portfolio_register_recommended_plugins' );