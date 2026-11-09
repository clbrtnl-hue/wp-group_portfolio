<?php 
/**
 * Default Project Section
 */
return array(
	'title'      => esc_html__( 'Project Section', 'creative-designer-portfolio' ),
	'categories' => array( 'creative-designer-portfolio', 'Project Section' ),
	'content'    => '<!-- wp:group {"metadata":{"patternName":"creative-designer-portfolio/offer-section","name":"Project Section","categories":["creative-designer-portfolio","Offer Section"]},"className":"project-section","style":{"spacing":{"margin":{"top":"0px"}}},"layout":{"type":"constrained","contentSize":"80%"}} -->
<div class="wp-block-group project-section" style="margin-top:0px"><!-- wp:spacer {"height":"60px"} -->
<div style="height:60px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:group {"className":"project-head-box wow fadeInDown","style":{"spacing":{"padding":{"bottom":"var:preset|spacing|40"}}},"layout":{"type":"default"}} -->
<div class="wp-block-group project-head-box wow fadeInDown" style="padding-bottom:var(--wp--preset--spacing--40)"><!-- wp:paragraph {"className":"project-sub-title has-background-color has-text-color has-link-color","style":{"elements":{"link":{"color":{"text":"var:preset|color|background"}}},"spacing":{"margin":{"top":"10px"}},"typography":{"fontStyle":"normal","fontWeight":"600","fontSize":"16px","textTransform":"capitalize","textAlign":"center"}},"textColor":"background"} -->
<p class="has-text-align-center project-sub-title has-background-color has-text-color has-link-color" style="margin-top:10px;font-size:16px;font-style:normal;font-weight:600;text-transform:capitalize">'. esc_html__('previous projects','creative-designer-portfolio').'</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3,"className":"project-title","style":{"typography":{"fontSize":"30px","textTransform":"capitalize","fontStyle":"normal","fontWeight":"600","lineHeight":"1.4","textAlign":"center"},"spacing":{"margin":{"top":"6px"}},"elements":{"link":{"color":{"text":"var:preset|color|primary"}}}},"textColor":"primary"} -->
<h3 class="wp-block-heading has-text-align-center project-title has-primary-color has-text-color has-link-color" style="margin-top:6px;font-size:30px;font-style:normal;font-weight:600;line-height:1.4;text-transform:capitalize">'. esc_html__('our creative case studies','creative-designer-portfolio').'</h3>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:query {"queryId":8,"query":{"perPage":6,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false},"metadata":{"categories":["posts"],"patternName":"core/query-standard-posts","name":"Standard"},"className":"project-boxes wow fadeInUp"} -->
<div class="wp-block-query project-boxes wow fadeInUp"><!-- wp:post-template {"layout":{"type":"grid","columnCount":3}} -->
<!-- wp:group {"align":"wide","className":"project-info-box","style":{"spacing":{"padding":{"top":"12px","bottom":"12px","left":"12px","right":"12px"}}},"backgroundColor":"foreground","layout":{"type":"default"}} -->
<div class="wp-block-group alignwide project-info-box has-foreground-background-color has-background" style="padding-top:12px;padding-right:12px;padding-bottom:12px;padding-left:12px"><!-- wp:group {"align":"wide","className":"project-img-bg","style":{"dimensions":{"minHeight":"400px"},"color":{"background":"#00000030"},"spacing":{"padding":{"top":"0px","bottom":"0px","left":"0px","right":"0px"}}},"layout":{"type":"default"}} -->
<div class="wp-block-group alignwide project-img-bg has-background" style="background-color:#00000030;min-height:400px;padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px"><!-- wp:post-featured-image {"isLink":true,"height":"400px","align":"wide"} /--></div>
<!-- /wp:group -->

<!-- wp:columns {"verticalAlignment":"center","className":"project-info"} -->
<div class="wp-block-columns are-vertically-aligned-center project-info"><!-- wp:column {"verticalAlignment":"center","width":"90%","className":"project-left-info"} -->
<div class="wp-block-column is-vertically-aligned-center project-left-info" style="flex-basis:90%"><!-- wp:post-title {"isLink":true,"className":"project-info-title","style":{"typography":{"fontSize":"22px","textTransform":"capitalize","fontStyle":"normal","fontWeight":"500"}}} /-->

<!-- wp:post-excerpt {"showMoreOnNewLine":false,"excerptLength":15,"className":"project-info-desc","style":{"color":{"text":"#676767"},"elements":{"link":{"color":{"text":"#676767"}}},"typography":{"fontSize":"14px","fontStyle":"normal","fontWeight":"300"},"spacing":{"margin":{"top":"10px"}}}} /--></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","width":"10%","className":"project-right-info"} -->
<div class="wp-block-column is-vertically-aligned-center project-right-info" style="flex-basis:10%"><!-- wp:post-excerpt {"moreText":" ","className":"project-info-btn","style":{"spacing":{"margin":{"top":"0px"}}}} /--></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->
<!-- /wp:post-template --></div>
<!-- /wp:query -->

<!-- wp:spacer {"height":"60px"} -->
<div style="height:60px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer --></div>
<!-- /wp:group -->',
);