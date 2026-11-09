<?php
/**
 * Recommended plugins for GO PORTFOLIO
 */

function go_portfolio_magical_plugins($plugins) {
    $theme_plugins = array(
        'magical-posts-display' => array(
            'name'        => __('Posts Display', 'go-portfolio'),
            'slug'        => 'magical-posts-display',
            'file'        => 'magical-posts-display/magical-posts-display.php',
            'description' => __('Display posts in beautiful layouts.', 'go-portfolio'),
            'category'    => 'Content Display',
            'essential'    => false,
            'featured'    => true,
            'is_local'    => false,
        ),
        'magical-addons-for-elementor' => array(
            'name'        => __('Magical Addons', 'go-portfolio'),
            'slug'        => 'magical-addons-for-elementor',
            'file'        => 'magical-addons-for-elementor/magical-addons-for-elementor.php',
            'description' => __('Premium quality addons for Elementor page builder.', 'go-portfolio'),
            'category'    => 'Page Builder',
            'essential'    => false,
            'featured'    => true,
            'is_local'    => false,
        ),
        'gallery-box' => array(
            'name'        => __('Gallery Box', 'go-portfolio'),
            'slug'        => 'gallery-box',
            'file'        => 'gallery-box/gallery-box.php',
            'description' => __('Beautiful gallery and lightbox plugin.', 'go-portfolio'),
            'category'    => 'Media',
            'essential'    => false,
            'featured'    => true,
            'is_local'    => false,
        ),
        'click-to-top' => array(
            'name'        => __('Click To Top', 'go-portfolio'),
            'slug'        => 'click-to-top',
            'file'        => 'click-to-top/click-to-top.php',
            'description' => __('One-click scroll to top button.', 'go-portfolio'),
            'category'    => 'Utility',
            'essential'    => false,
            'featured'    => true,
            'is_local'    => false,
        ),
        'easy-share-solution' => array(
            'name'        => __('Easy Share', 'go-portfolio'),
            'slug'        => 'easy-share-solution',
            'file'        => 'easy-share-solution/easy-share-solution.php',
            'description' => __('Easily add social sharing buttons.', 'go-portfolio'),
            'category'    => 'Utility',
            'essential'    => false,
            'featured'    => true,
            'is_local'    => false,
        ),
        'wp-edit-password-protected' => array(
            'name'        => __('Password Protected', 'go-portfolio'),
            'slug'        => 'wp-edit-password-protected',
            'file'        => 'wp-edit-password-protected/wp-edit-password-protected.php',
            'description' => __('Manage password protection.', 'go-portfolio'),
            'category'    => 'Security',
            'essential'    => false,
            'featured'    => true,
            'is_local'    => false,
        ),
    );

    if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
        $theme_plugins['magical-products-display'] = array(
            'name'        => __('Magical Products Display', 'go-portfolio'),
            'slug'        => 'magical-products-display',
            'file'        => 'magical-products-display/magical-products-display.php',
            'description' => __('Display WooCommerce products in beautiful layouts.', 'go-portfolio'),
            'category'    => 'eCommerce',
            'essential'    => false,
            'featured'    => true,
            'is_local'    => false,
        );
    }

    return array_merge($plugins, $theme_plugins);
}
add_filter('magical_plugin_activation_recommended_plugins', 'go_portfolio_magical_plugins');
/**
 * AI Marketing Expert success notice
 */
function go_portfolio_ai_marketing_notice() {
    if (!is_plugin_active('ai-marketing-expert/ai-marketing-expert.php')) {
        return;
    }

    $user_id = get_current_user_id();
    $dismissed = get_user_meta($user_id, 'go_portfolio_ai_notice_dismissed', true);
    if ($dismissed) {
        return;
    }

    if (isset($_GET['go_portfolio_ai_dismiss']) && '1' === $_GET['go_portfolio_ai_dismiss']) {
        update_user_meta($user_id, 'go_portfolio_ai_notice_dismissed', 1);
        return;
    }

    $has_woo = is_plugin_active('woocommerce/woocommerce.php');
    $message = $has_woo
        ? __('Supercharge your sales and turn visitors into loyal customers — automatically. Built by WordPress experts, it\'s the smartest AI tool in your dashboard. We promise you\'ll be a fan!', 'go-portfolio')
        : __('Attract more visitors, grow your audience, and keep them engaged — automatically. Built by WordPress experts, it\'s the smartest AI tool in your dashboard. We promise you\'ll be a fan!', 'go-portfolio');
    ?>
    <div class="notice notice-success is-dismissible" style="border-left-color:#2271b1; padding:16px 20px;">
        <p style="font-size:14px; margin:0 0 8px;">
            <strong style="color:#2271b1;"><?php esc_html_e('AI Marketing Expert', 'go-portfolio'); ?></strong> &mdash;
            <?php esc_html_e('Now active on your site! — A must-have plugin in the current AI world.', 'go-portfolio'); ?>
        </p>
        <p style="margin:0 0 12px; font-size:13px; line-height:1.6;">
            <?php echo esc_html($message); ?>
        </p>
        <p style="margin:0;">
            <a href="<?php echo esc_url(add_query_arg('go_portfolio_ai_dismiss', '1')); ?>" class="button button-primary" style="font-weight:600;">
                <?php esc_html_e('Got it!', 'go-portfolio'); ?>
            </a>
        </p>
    </div>
    <?php
}
add_action('admin_notices', 'go_portfolio_ai_marketing_notice');
