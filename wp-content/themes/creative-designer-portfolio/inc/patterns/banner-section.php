<?php 
/**
 * Default Banner Section
 */

return array(
	'title'      => esc_html__( 'Banner Section', 'creative-designer-portfolio' ),
	'categories' => array( 'creative-designer-portfolio', 'Banner Section' ),
	'content'    => '<!-- wp:group {"metadata":{"patternName":"creative-designer-portfolio/banner-section","name":"Banner Section","categories":["creative-designer-portfolio","Banner Section"]},"className":"main-banner","style":{"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"top":"0","bottom":"0"}},"dimensions":{"minHeight":""}},"layout":{"type":"default"}} -->
<div class="wp-block-group main-banner" style="margin-top:0;margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:cover {"overlayColor":"background-two","isUserOverlayColor":true,"minHeight":700,"isDark":false,"className":"banner-bg","style":{"spacing":{"padding":{"top":"0px","bottom":"0px","left":"0px","right":"0px"}}},"layout":{"type":"constrained","contentSize":"80%"}} -->
<div class="wp-block-cover is-light banner-bg" style="padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px;min-height:700px"><span aria-hidden="true" class="wp-block-cover__background has-background-two-background-color has-background-dim-100 has-background-dim"></span><div class="wp-block-cover__inner-container"><!-- wp:columns {"verticalAlignment":null,"className":"banner-boxes","style":{"spacing":{"blockGap":{"top":"20px","left":"30px"}}}} -->
<div class="wp-block-columns banner-boxes"><!-- wp:column {"verticalAlignment":"center","width":"60%","className":"banner-box1"} -->
<div class="wp-block-column is-vertically-aligned-center banner-box1" style="flex-basis:60%"><!-- wp:group {"className":"banner-inner-box","style":{"spacing":{"blockGap":"12px"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group banner-inner-box"><!-- wp:heading {"className":"banner-left-title","style":{"elements":{"link":{"color":{"text":"var:preset|color|title-color"}}},"typography":{"fontSize":"112px","textTransform":"uppercase","fontStyle":"normal","fontWeight":"1000","lineHeight":"1"}},"textColor":"title-color"} -->
<h2 class="wp-block-heading banner-left-title has-title-color-color has-text-color has-link-color" style="font-size:112px;font-style:normal;font-weight:1000;line-height:1;text-transform:uppercase">'. esc_html__('designer.','creative-designer-portfolio').'</h2>
<!-- /wp:heading -->

<!-- wp:cover {"overlayColor":"background-three","isUserOverlayColor":true,"contentPosition":"bottom center","isDark":false,"sizeSlug":"full","className":"banner-img-bg","style":{"spacing":{"padding":{"bottom":"0px","top":"60px"}}},"layout":{"type":"default"}} -->
<div class="wp-block-cover is-light has-custom-content-position is-position-bottom-center banner-img-bg" style="padding-top:60px;padding-bottom:0px"><span aria-hidden="true" class="wp-block-cover__background has-background-three-background-color has-background-dim-100 has-background-dim"></span><div class="wp-block-cover__inner-container"><!-- wp:image {"id":9,"width":"auto","height":"550px","sizeSlug":"full","linkDestination":"none","align":"center","className":"banner-mid-img"} -->
<figure class="wp-block-image aligncenter size-full is-resized banner-mid-img"><img src="' . esc_url( get_theme_file_uri( '/assets/images/banner-img.png' ) ) .'" alt="" class="wp-image-9" style="width:auto;height:550px"/></figure>
<!-- /wp:image --></div></div>
<!-- /wp:cover --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"40%","className":"banner-box2"} -->
<div class="wp-block-column banner-box2" style="flex-basis:40%"><!-- wp:group {"className":"banner-info-box","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|60"}}},"layout":{"type":"flex","orientation":"vertical","verticalAlignment":"space-between","justifyContent":"left"}} -->
<div class="wp-block-group banner-info-box" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--60)"><!-- wp:group {"className":"banner-top-info wow fadeInDown","layout":{"type":"default"}} -->
<div class="wp-block-group banner-top-info wow fadeInDown"><!-- wp:paragraph {"className":"banner-info-sub-title","style":{"elements":{"link":{"color":{"text":"var:preset|color|title-color"}}},"typography":{"fontSize":"15px","textTransform":"uppercase","fontStyle":"normal","fontWeight":"500","letterSpacing":"5px","textAlign":"left"}},"textColor":"title-color"} -->
<p class="has-text-align-left banner-info-sub-title has-title-color-color has-text-color has-link-color" style="font-size:15px;font-style:normal;font-weight:500;letter-spacing:5px;text-transform:uppercase">'. esc_html__('freelance digital designer','creative-designer-portfolio').'</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"className":"banner-info-title","style":{"elements":{"link":{"color":{"text":"var:preset|color|title-color"}}},"typography":{"fontSize":"110px","fontStyle":"normal","fontWeight":"400","textTransform":"uppercase","letterSpacing":"5px","textAlign":"center","lineHeight":"1.1"},"spacing":{"margin":{"top":"5px"}}},"textColor":"title-color"} -->
<h2 class="wp-block-heading has-text-align-center banner-info-title has-title-color-color has-text-color has-link-color" style="margin-top:5px;font-size:110px;font-style:normal;font-weight:400;letter-spacing:5px;line-height:1.1;text-transform:uppercase">'. esc_html__('portfolio.','creative-designer-portfolio').'</h2>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"banner-btm-info wow fadeInUp","style":{"spacing":{"padding":{"right":"6rem"}}},"layout":{"type":"default"}} -->
<div class="wp-block-group banner-btm-info wow fadeInUp" style="padding-right:6rem"><!-- wp:heading {"level":3,"className":"banner-btm-title","style":{"elements":{"link":{"color":{"text":"var:preset|color|title-color"}}},"typography":{"fontSize":"35px","textTransform":"capitalize","lineHeight":"1.3"}},"textColor":"title-color"} -->
<h3 class="wp-block-heading banner-btm-title has-title-color-color has-text-color has-link-color" style="font-size:35px;line-height:1.3;text-transform:capitalize">'. esc_html__('hi, i’m ','creative-designer-portfolio').'<mark style="background-color:rgba(0, 0, 0, 0)" class="has-inline-color has-background-color">'. esc_html__('olivia carter','creative-designer-portfolio').'</mark>'. esc_html__(', and this is my portfolio.','creative-designer-portfolio').'</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"banner-desc","style":{"color":{"text":"#676767"},"elements":{"link":{"color":{"text":"#676767"}}},"spacing":{"margin":{"top":"12px"}}}} -->
<p class="banner-desc has-text-color has-link-color" style="color:#676767;margin-top:12px">'. esc_html__('Lorem Ipsum&nbsp;is simply dummy text of the printing and type setting industry.','creative-designer-portfolio').'</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"className":"banner-btn"} -->
<div class="wp-block-buttons banner-btn"><!-- wp:button {"backgroundColor":"background","textColor":"foreground","style":{"elements":{"link":{"color":{"text":"var:preset|color|foreground"}}},"typography":{"fontSize":"15px","textTransform":"capitalize","fontStyle":"normal","fontWeight":"600"},"border":{"radius":{"topLeft":"0px","topRight":"0px","bottomLeft":"0px","bottomRight":"0px"}},"spacing":{"padding":{"left":"22px","right":"22px","top":"10px","bottom":"10px"}}}} -->
<div class="wp-block-button"><a class="wp-block-button__link has-foreground-color has-background-background-color has-text-color has-background has-link-color has-custom-font-size wp-element-button" href="#" style="border-top-left-radius:0px;border-top-right-radius:0px;border-bottom-left-radius:0px;border-bottom-right-radius:0px;padding-top:10px;padding-right:22px;padding-bottom:10px;padding-left:22px;font-size:15px;font-style:normal;font-weight:600;text-transform:capitalize">'. esc_html__('lets talk','creative-designer-portfolio').'</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div></div>
<!-- /wp:cover --></div>
<!-- /wp:group -->',
);