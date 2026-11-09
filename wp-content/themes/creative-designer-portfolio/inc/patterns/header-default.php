<?php 
/**
 * Default Header
 */
return array(
	'title'      => esc_html__( 'Default Header', 'creative-designer-portfolio' ),
	'categories' => array( 'creative-designer-portfolio', 'header' ),
	'content'    => '<!-- wp:group {"metadata":{"patternName":"creative-designer-portfolio/header-default","name":"Default Header","categories":["creative-designer-portfolio","header"]},"className":"main-header","style":{"spacing":{"padding":{"right":"0","left":"0","top":"0","bottom":"0"},"margin":{"top":"0","bottom":"0"},"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group main-header" style="margin-top:0;margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:group {"className":"menu-header","style":{"spacing":{"padding":{"top":"0px","bottom":"0px"}},"border":{"bottom":{"color":"#4b4b4b","width":"1px"},"top":[],"right":[],"left":[]}},"backgroundColor":"background-two","layout":{"type":"constrained","contentSize":"80%"}} -->
<div class="wp-block-group menu-header has-background-two-background-color has-background" style="border-bottom-color:#4b4b4b;border-bottom-width:1px;padding-top:0px;padding-bottom:0px"><!-- wp:columns {"verticalAlignment":"center","className":"menu-header-boxes","style":{"spacing":{"padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40","left":"0","right":"0"},"margin":{"top":"0","bottom":"0"},"blockGap":{"top":"12px","left":"12px"}},"border":{"radius":"15px"}}} -->
<div class="wp-block-columns are-vertically-aligned-center menu-header-boxes" style="border-radius:15px;margin-top:0;margin-bottom:0;padding-top:var(--wp--preset--spacing--40);padding-right:0;padding-bottom:var(--wp--preset--spacing--40);padding-left:0"><!-- wp:column {"verticalAlignment":"center","width":"25%","className":"header-logo"} -->
<div class="wp-block-column is-vertically-aligned-center header-logo" style="flex-basis:25%"><!-- wp:group {"className":"logo-box","layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group logo-box"><!-- wp:site-logo {"width":80,"shouldSyncIcon":true} /-->

<!-- wp:site-title {"style":{"elements":{"link":{"color":{"text":"var:preset|color|title-color"}}},"typography":{"fontSize":"26px","fontStyle":"normal","fontWeight":"600"}},"textColor":"title-color"} /--></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","width":"60%","className":"menu-box"} -->
<div class="wp-block-column is-vertically-aligned-center menu-box" style="flex-basis:60%"><!-- wp:navigation {"textColor":"title-color","customBackgroundColor":"#00000000","overlayBackgroundColor":"white","overlayTextColor":"primary","metadata":{"ignoredHookedBlocks":["woocommerce/customer-account"]},"style":{"typography":{"fontStyle":"normal","fontWeight":"500","textTransform":"capitalize","fontSize":"15px"},"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"flex","justifyContent":"center"}} -->
<!-- wp:navigation-link {"label":"Home","type":"","url":"#","kind":"custom","isTopLevelLink":true} /-->

<!-- wp:navigation-link {"label":"About","type":"","url":"#","kind":"custom","isTopLevelLink":true} /-->

<!-- wp:navigation-link {"label":"Pages","type":"","url":"#","kind":"custom","isTopLevelLink":true} /-->

<!-- wp:navigation-link {"label":"Elements","type":"","url":"#","kind":"custom","isTopLevelLink":true} /-->

<!-- wp:navigation-link {"label":"Blog","type":"","url":"#","kind":"custom","isTopLevelLink":true} /-->

<!-- wp:navigation-link {"label":"Buy Now","type":"","opensInNewTab":true,"url":"'. esc_url( CREATIVE_DESIGNER_PORTFOLIO_BUY_NOW ) . '","kind":"custom","isTopLevelLink":true} /-->
<!-- /wp:navigation --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","width":"15%","className":"header-right"} -->
<div class="wp-block-column is-vertically-aligned-center header-right" style="flex-basis:15%"><!-- wp:buttons {"className":"header-btn","layout":{"type":"flex","justifyContent":"right"}} -->
<div class="wp-block-buttons header-btn"><!-- wp:button {"backgroundColor":"background","textColor":"white","style":{"elements":{"link":{"color":{"text":"var:preset|color|white"}}},"typography":{"fontSize":"15px","textTransform":"capitalize","fontStyle":"normal","fontWeight":"500"},"border":{"radius":{"topLeft":"0px","topRight":"0px","bottomLeft":"0px","bottomRight":"0px"}},"spacing":{"padding":{"left":"25px","right":"25px","top":"8px","bottom":"8px"}}}} -->
<div class="wp-block-button"><a class="wp-block-button__link has-white-color has-background-background-color has-text-color has-background has-link-color has-custom-font-size wp-element-button" href="#" style="border-top-left-radius:0px;border-top-right-radius:0px;border-bottom-left-radius:0px;border-bottom-right-radius:0px;padding-top:8px;padding-right:25px;padding-bottom:8px;padding-left:25px;font-size:15px;font-style:normal;font-weight:500;text-transform:capitalize">'. esc_html__('Download CV','creative-designer-portfolio').'</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->',
);