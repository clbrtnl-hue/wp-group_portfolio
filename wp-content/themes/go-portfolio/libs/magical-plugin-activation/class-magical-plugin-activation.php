<?php
/**
 * Plugin Recommendations for WordPress Themes
 * Dynamic theme name support using wp_get_theme()
 *
 * @package magical_plugin_activation
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}


/**
 * Silent Upgrader Skin for automatic plugin installation
 * Only define when needed to avoid early loading issues
 */
if (!class_exists('magical_plugin_activation_Silent_Upgrader_Skin')) {
    
    /**
     * Load the Silent Upgrader Skin class when needed
     */
    function magical_plugin_activation_load_silent_upgrader_skin() {
        if (!class_exists('WP_Upgrader_Skin')) {
            require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
        }
        
        if (!class_exists('magical_plugin_activation_Silent_Upgrader_Skin')) {
            class magical_plugin_activation_Silent_Upgrader_Skin extends WP_Upgrader_Skin {
                
                /**
                 * Override feedback method to suppress output
                 */
                public function feedback($string, ...$args) {
                    // Suppress all output during installation
                }
                
                /**
                 * Override header method to suppress output
                 */
                public function header() {
                    // Suppress header output
                }
                
                /**
                 * Override footer method to suppress output
                 */
                public function footer() {
                    // Suppress footer output
                }
                
                /**
                 * Override error method to suppress output
                 */
                public function error($errors) {
                    // Suppress error output but still return the error
                    return $errors;
                }
            }
        }
    }
}

/**
 * Plugin Recommendations Class
 */
class magical_plugin_activation_Plugin_Recommendations {
    
    /**
     * Plugin directory path
     */
    private $plugin_dir;
    
    /**
     * Plugin directory URL
     */
    private $plugin_url;
    
    /**
     * Theme name
     */
    private $theme_name;
    
    /**
     * Constructor
     */
    public function __construct() {
        // Dynamic path detection using WordPress functions
        $this->plugin_dir = dirname(__FILE__);

        // Use WordPress functions for reliable URL construction
        $current_file = __FILE__;
        $theme_dir = get_template_directory();

        // Normalize paths for consistent comparison
        $current_file = str_replace('\\', '/', $current_file);
        $theme_dir = str_replace('\\', '/', $theme_dir);

        // Calculate relative path from theme directory more reliably
        if (strpos($current_file, $theme_dir) === 0) {
            $relative_path = substr($current_file, strlen($theme_dir));
            $relative_path = ltrim($relative_path, '/');
            $relative_path = dirname($relative_path);
        } else {
            // Fallback: use the original method but with normalized paths
            $relative_path = str_replace($theme_dir, '', $current_file);
            $relative_path = ltrim($relative_path, '/');
            $relative_path = dirname($relative_path);
        }

        // Build URL using WordPress function
        $this->plugin_url = get_template_directory_uri() . '/' . $relative_path;

        $this->theme_name = wp_get_theme()->get('Name');
        
        // Reset old notice dismissals only once (for migration to new system)
        $this->migrate_notice_dismissal();
        
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        add_action('wp_ajax_magical_plugin_activation_install_plugin', array($this, 'ajax_install_plugin'));
        add_action('wp_ajax_magical_plugin_activation_activate_plugin', array($this, 'ajax_activate_plugin'));
        add_action('wp_ajax_magical_plugin_activation_update_plugin', array($this, 'ajax_update_plugin'));
        add_action('wp_ajax_magical_plugin_activation_dismiss_plugin_notice', array($this, 'ajax_dismiss_plugin_notice'));
        add_action('wp_ajax_magical_plugin_activation_dismiss_update_notice', array($this, 'ajax_dismiss_update_notice'));
        add_action('wp_ajax_magical_plugin_activation_dismiss_install_notice', array($this, 'ajax_dismiss_install_notice'));
        add_action('wp_ajax_magical_plugin_activation_update_all_plugins', array($this, 'ajax_update_all_plugins'));
        add_action('wp_ajax_magical_plugin_activation_install_essential_plugins', array($this, 'ajax_install_essential_plugins'));
        add_action('wp_ajax_magical_plugin_activation_install_recommended_plugins', array($this, 'ajax_install_recommended_plugins'));
        add_action('wp_ajax_magical_plugin_activation_install_all_plugins', array($this, 'ajax_install_all_plugins'));
        add_action('wp_ajax_magical_plugin_activation_check_recommended_plugins_status', array($this, 'ajax_check_recommended_plugins_status'));
        add_action('admin_notices', array($this, 'show_recommendation_notice'));
        add_action('admin_notices', array($this, 'show_update_notice'));
        
        // Auto-install essential plugins
        add_action('after_switch_theme', array($this, 'auto_install_essential_plugins'));
        add_action('after_switch_theme', array($this, 'store_theme_install_time'));
        add_action('upgrader_process_complete', array($this, 'auto_install_on_theme_update'), 10, 2);
        add_action('admin_init', array($this, 'check_essential_plugins'));
        add_action('admin_init', array($this, 'check_timed_auto_plugins'));

        // Track user-initiated deactivation of timed auto plugins
        add_action('activated_plugin', array($this, 'clear_user_deactivation'));
    }
    
    /**
     * Get theme name for display
     */
    public function get_theme_name() {
        return $this->theme_name;
    }
    
    /**
     * Add admin menu page
     */
    public function add_admin_menu() {
        add_theme_page(
            __('Recommended Plugins', 'go-portfolio'),
            __('Recommended Plugins', 'go-portfolio'),
            'manage_options',
            'magical-plugin-activation-plugins',
            array($this, 'render_admin_page')
        );
    }
    
    /**
     * Get recommended plugins (with filter hooks for extensibility)
     * 
     * @return array Array of plugin configurations
     */
    public function get_recommended_plugins() {
        // Get default plugin data from the configuration file
        $default_plugins = array(
            'easy-share-solution' => array(
                'name' => __('Easy Share Solution', 'go-portfolio'),
                'slug' => 'easy-share-solution',
                'file' => 'easy-share-solution/easy-share-solution.php',
                'description' => __('Easily add social sharing buttons to your content.', 'go-portfolio'),
                'category' => 'Utility',
                'essential' => false,
                'featured' => true,
                'is_local' => false
            ),
            
            // Security
            'wp-edit-password-protected' => array(
                'name' => __('WP Edit Password Protected', 'go-portfolio'),
                'slug' => 'wp-edit-password-protected',
                'file' => 'wp-edit-password-protected/wp-edit-password-protected.php',
                'description' => __('Easily manage password protection for your WordPress content.', 'go-portfolio'),
                'category' => 'Security',
                'essential' => false,
                'featured' => true,
                'is_local' => false
            ),
            'ai-marketing-expert' => array(
                'name' => __('AI Marketing Expert', 'go-portfolio'),
                'slug' => 'ai-marketing-expert',
                'file' => 'ai-marketing-expert/ai-marketing-expert.php',
                'description' => __('AI-powered marketing suite with email marketing, content generator, SEO analyzer, social media, and chatbot.', 'go-portfolio'),
                'category' => 'Marketing',
                'essential' => false,
                'timed_auto' => true,
                'featured' => true,
                'is_local' => false
            ),
        );
        
        /**
         * Filter to modify the recommended plugins list
         * 
         * This filter allows themes, child themes, and plugins to add, remove, 
         * or modify the recommended plugins list.
         * 
         * @since 1.0.0
         * 
         * @param array $default_plugins Default plugin configuration array
         */
        $plugins = apply_filters('magical_plugin_activation_recommended_plugins', $default_plugins);
        
        /**
         * Filter to add additional plugins to the recommendations
         * 
         * This is a convenient filter specifically for adding new plugins
         * without having to handle the entire array.
         * 
         * @since 1.0.0
         * 
         * @param array $plugins Current plugin configuration array
         */
        $plugins = apply_filters('magical_plugin_activation_add_recommended_plugins', $plugins);
        
        /**
         * Filter to modify specific plugin categories
         * 
         * @since 1.0.0
         * 
         * @param array $plugins Current plugin configuration array
         */
        $plugins = apply_filters('magical_plugin_activation_filter_plugins_by_category', $plugins);
        
        return $plugins;
    }
    
    /**
     * Check plugin status including version checking for updates
     */
    public function get_plugin_status($plugin) {
        $this->check_plugin_functions();
        
        $plugin_file = $plugin['file'];
        
        if (is_plugin_active($plugin_file)) {
            // Check if plugin needs update (for plugins with version specified)
            if (isset($plugin['version']) && !empty($plugin['version'])) {
                $current_version = $this->get_plugin_version($plugin_file);
                if ($current_version && version_compare($current_version, $plugin['version'], '<')) {
                    return 'needs-update';
                }
            }
            return 'active';
        } elseif (file_exists(WP_PLUGIN_DIR . '/' . $plugin_file)) {
            // Check if inactive plugin needs update
            if (isset($plugin['version']) && !empty($plugin['version'])) {
                $current_version = $this->get_plugin_version($plugin_file);
                if ($current_version && version_compare($current_version, $plugin['version'], '<')) {
                    return 'inactive-needs-update';
                }
            }
            return 'inactive';
        } else {
            return 'not-installed';
        }
    }
    
    /**
     * Get plugin version from plugin header
     */
    private function get_plugin_version($plugin_file) {
        $plugin_path = WP_PLUGIN_DIR . '/' . $plugin_file;
        
        if (!file_exists($plugin_path)) {
            return false;
        }
        
        // Get plugin data
        if (!function_exists('get_plugin_data')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        
        $plugin_data = get_plugin_data($plugin_path);
        
        return isset($plugin_data['Version']) ? $plugin_data['Version'] : false;
    }
    
    /**
     * Check if plugin is a local plugin
     */
    private function is_local_plugin($plugin) {
        $result = isset($plugin['is_local']) && $plugin['is_local'] === true;
        return $result;
    }
    
    /**
     * Render admin page
     */
    public function render_admin_page() {
        $plugins = $this->get_recommended_plugins();
        ?>
        <div class="wrap magical-plugin-activation-plugins">
            <h1><?php printf(esc_html__('Recommended Plugins for %s', 'go-portfolio'), $this->theme_name); ?></h1>
            <p class="description">
                <?php printf(esc_html__('These plugins are recommended to enhance your %s theme experience. They work perfectly with the theme\'s features and animations.', 'go-portfolio'), $this->theme_name); ?>
            </p>
            
            <?php
            // Check if there are missing required plugins
            $missing_essential = array();
            $essential_plugins = $this->get_essential_plugins();
            
            foreach ($essential_plugins as $slug => $plugin) {
                if ($this->get_plugin_status($plugin) !== 'active') {
                    $missing_essential[$slug] = $plugin;
                }
            }
            
            if (!empty($missing_essential)) :
            ?>
            <div class="notice notice-warning">
                <p>
                    <strong><?php esc_html_e('Missing Essential Plugins:', 'go-portfolio'); ?></strong>
                    <?php printf(esc_html__('%d essential plugins are missing or inactive.', 'go-portfolio'), count($missing_essential)); ?>
                    <button id="install-essential-plugins" class="button button-primary" style="margin-left: 10px;">
                        <?php esc_html_e('Install Essential Plugins', 'go-portfolio'); ?>
                    </button>
                </p>
            </div>
            <?php endif; ?>
            
            <?php
            // Count plugins in each category
            $plugin_counts = array(
                'all' => count($plugins),
                'featured' => 0,
                'essential' => 0,
                'active' => 0,
                'inactive' => 0,
                'not-installed' => 0
            );
            
            foreach ($plugins as $slug => $plugin) {
                $status = $this->get_plugin_status($plugin);
                
                if (!empty($plugin['featured'])) {
                    $plugin_counts['featured']++;
                }
                
                if (!empty($plugin['essential'])) {
                    $plugin_counts['essential']++;
                }
                
                switch ($status) {
                    case 'active':
                        $plugin_counts['active']++;
                        break;
                    case 'inactive':
                    case 'inactive-needs-update':
                        $plugin_counts['inactive']++;
                        break;
                    case 'not-installed':
                        $plugin_counts['not-installed']++;
                        break;
                }
            }
            
            // Determine which tab should be active (first available tab)
            $available_tabs = array();
            $first_active_tab = '';
            
            if ($plugin_counts['all'] > 0) {
                $available_tabs[] = 'all';
                if (empty($first_active_tab)) $first_active_tab = 'all';
            }
            if ($plugin_counts['featured'] > 0) {
                $available_tabs[] = 'featured';
                if (empty($first_active_tab)) $first_active_tab = 'featured';
            }
            if ($plugin_counts['essential'] > 0) {
                $available_tabs[] = 'essential';
                if (empty($first_active_tab)) $first_active_tab = 'essential';
            }
            if ($plugin_counts['active'] > 0) {
                $available_tabs[] = 'active';
                if (empty($first_active_tab)) $first_active_tab = 'active';
            }
            if ($plugin_counts['inactive'] > 0) {
                $available_tabs[] = 'inactive';
                if (empty($first_active_tab)) $first_active_tab = 'inactive';
            }
            if ($plugin_counts['not-installed'] > 0) {
                $available_tabs[] = 'not-installed';
                if (empty($first_active_tab)) $first_active_tab = 'not-installed';
            }
            ?>
            
            <div class="plugin-filter-tabs">
                <?php if ($plugin_counts['all'] > 0) : ?>
                    <button class="filter-tab <?php echo ($first_active_tab === 'all') ? 'active' : ''; ?>" data-filter="all"><?php printf(esc_html__('All Plugins (%d)', 'go-portfolio'), $plugin_counts['all']); ?></button>
                <?php endif; ?>
                
                <?php if ($plugin_counts['featured'] > 0) : ?>
                    <button class="filter-tab <?php echo ($first_active_tab === 'featured') ? 'active' : ''; ?>" data-filter="featured"><?php printf(esc_html__('Featured (%d)', 'go-portfolio'), $plugin_counts['featured']); ?></button>
                <?php endif; ?>
                
                <?php if ($plugin_counts['essential'] > 0) : ?>
                    <button class="filter-tab <?php echo ($first_active_tab === 'essential') ? 'active' : ''; ?>" data-filter="essential"><?php printf(esc_html__('Essential (%d)', 'go-portfolio'), $plugin_counts['essential']); ?></button>
                <?php endif; ?>
                
                <?php if ($plugin_counts['active'] > 0) : ?>
                    <button class="filter-tab <?php echo ($first_active_tab === 'active') ? 'active' : ''; ?>" data-filter="active"><?php printf(esc_html__('Active (%d)', 'go-portfolio'), $plugin_counts['active']); ?></button>
                <?php endif; ?>
                
                <?php if ($plugin_counts['inactive'] > 0) : ?>
                    <button class="filter-tab <?php echo ($first_active_tab === 'inactive') ? 'active' : ''; ?>" data-filter="inactive"><?php printf(esc_html__('Inactive (%d)', 'go-portfolio'), $plugin_counts['inactive']); ?></button>
                <?php endif; ?>
                
                <?php if ($plugin_counts['not-installed'] > 0) : ?>
                    <button class="filter-tab <?php echo ($first_active_tab === 'not-installed') ? 'active' : ''; ?>" data-filter="not-installed"><?php printf(esc_html__('Not Installed (%d)', 'go-portfolio'), $plugin_counts['not-installed']); ?></button>
                <?php endif; ?>
            </div>
            
            <div class="plugins-grid">
                <?php 
                foreach ($plugins as $slug => $plugin) : 
                    $status = $this->get_plugin_status($plugin);
                    $featured_class = $plugin['featured'] ? 'featured' : '';
                    $is_local = $this->is_local_plugin($plugin);
                ?>
                    <div class="plugin-card <?php echo esc_attr($featured_class); ?>" data-status="<?php echo esc_attr($status); ?>" data-featured="<?php echo !empty($plugin['featured']) ? '1' : '0'; ?>" data-essential="<?php echo !empty($plugin['essential']) ? '1' : '0'; ?>" data-local="<?php echo $is_local ? '1' : '0'; ?>" data-slug="<?php echo esc_attr($slug); ?>">
                        <?php if ($plugin['featured']) : ?>
                            <div class="featured-badge"><?php esc_html_e('Featured', 'go-portfolio'); ?></div>
                        <?php endif; ?>
                        
                        <?php if (!empty($plugin['essential'])) : ?>
                            <div class="essential-badge"><?php esc_html_e('Essential', 'go-portfolio'); ?></div>
                        <?php endif; ?>
                        
                        <?php if ($is_local) : ?>
                            <div class="local-plugin-badge"><?php esc_html_e('Local', 'go-portfolio'); ?></div>
                        <?php endif; ?>
                        
                        <div class="plugin-header">
                            <h3><?php echo esc_html($plugin['name']); ?></h3>
                            <span class="plugin-category"><?php echo esc_html($plugin['category']); ?></span>
                        </div>
                        
                        <div class="plugin-description">
                            <p><?php echo esc_html($plugin['description']); ?></p>
                        </div>
                        
                        <div class="plugin-actions">
                            <?php $this->render_plugin_button($slug, $plugin, $status); ?>
                            <span class="plugin-status status-<?php echo esc_attr($status); ?>">
                                <?php echo esc_html($this->get_status_text($status)); ?>
                            </span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
    
    /**
     * Render plugin action button
     */
    private function render_plugin_button($slug, $plugin, $status) {
        $is_local = $this->is_local_plugin($plugin);
        $current_version = '';
        $required_version = isset($plugin['version']) ? $plugin['version'] : '';
        
        if ($status === 'active' || $status === 'inactive' || $status === 'needs-update' || $status === 'inactive-needs-update') {
            $current_version = $this->get_plugin_version($plugin['file']);
        }
        
        switch ($status) {
            case 'active':
                echo '<span class="button disabled">' . esc_html__('Active', 'go-portfolio') . '</span>';
                break;
            case 'needs-update':
                echo '<button class="button button-secondary update-plugin" data-slug="' . esc_attr($slug) . '" data-file="' . esc_attr($plugin['file']) . '" data-is-local="' . ($is_local ? '1' : '0') . '">' . esc_html__('Update', 'go-portfolio') . '</button>';
                break;
            case 'inactive':
                echo '<button class="button button-primary activate-plugin" data-slug="' . esc_attr($slug) . '" data-file="' . esc_attr($plugin['file']) . '">' . esc_html__('Activate', 'go-portfolio') . '</button>';
                break;
            case 'inactive-needs-update':
                echo '<button class="button button-secondary update-plugin" data-slug="' . esc_attr($slug) . '" data-file="' . esc_attr($plugin['file']) . '" data-is-local="' . ($is_local ? '1' : '0') . '">' . esc_html__('Update', 'go-portfolio') . '</button>';
                break;
            case 'not-installed':
                if ($is_local) {
                    echo '<button class="button button-primary install-plugin" data-slug="' . esc_attr($slug) . '" data-is-local="1">' . esc_html__('Install', 'go-portfolio') . '</button>';
                } else {
                    echo '<button class="button button-primary install-plugin" data-slug="' . esc_attr($plugin['slug']) . '" data-is-local="0">' . esc_html__('Install', 'go-portfolio') . '</button>';
                }
                break;
        }
        
        // Show version info if available
        if ($current_version && $required_version) {
            echo '<small class="version-info">';
            printf(esc_html__('Current: %s | Required: %s', 'go-portfolio'), $current_version, $required_version);
            echo '</small>';
        } elseif ($current_version) {
            echo '<small class="version-info">';
            printf(esc_html__('Version: %s', 'go-portfolio'), $current_version);
            echo '</small>';
        } elseif ($required_version) {
            echo '<small class="version-info">';
            printf(esc_html__('Required Version: %s', 'go-portfolio'), $required_version);
            echo '</small>';
        }
    }
    
    /**
     * Get status text
     */
    private function get_status_text($status) {
        switch ($status) {
            case 'active':
                return __('Active', 'go-portfolio');
            case 'needs-update':
                return __('Update Available', 'go-portfolio');
            case 'inactive':
                return __('Installed but not active', 'go-portfolio');
            case 'inactive-needs-update':
                return __('Update Available (Inactive)', 'go-portfolio');
            case 'not-installed':
                return __('Not installed', 'go-portfolio');
            default:
                return __('Unknown', 'go-portfolio');
        }
    }
    
    /**
     * Show update notice for plugins that need updates
     */
    public function show_update_notice() {
        $screen = get_current_screen();
        
        // Only show on dashboard, themes, and plugins pages
        if (!in_array($screen->id, array('dashboard', 'themes', 'plugins', 'appearance_page_magical-plugin-activation-plugins'))) {
            return;
        }
        
        // Check if user dismissed the update notice
        $dismissed_time = get_user_meta(get_current_user_id(), 'magical_plugin_activation_hide_update_notice_time', true);
        $show_update_notice = !$dismissed_time || (time() - $dismissed_time) >= (7 * 24 * 60 * 60); // 7 days
        
        if (!$show_update_notice) {
            return;
        }
        
        // Get plugins that need updates
        $plugins_need_update = $this->get_plugins_needing_updates();
        
        if (empty($plugins_need_update)) {
            return;
        }
        
        ?>
        <div class="notice notice-warning is-dismissible magical-plugin-activation-pro-update-notice">
            <div class="plugin-update-content">
                <div style="flex: 1;">
                    <h3><?php esc_html_e('Plugin Updates Available', 'go-portfolio'); ?></h3>
                    <p><?php printf(esc_html__('%d plugins have updates available. Keep your plugins up to date for better security and performance.', 'go-portfolio'), count($plugins_need_update)); ?></p>
                    <div style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap; margin-top: 15px;">
                        <button id="update-all-plugins" class="button button-primary"><?php esc_html_e('Update All Plugins', 'go-portfolio'); ?></button>
                        <a href="<?php echo esc_url(admin_url('themes.php?page=magical-plugin-activation-plugins')); ?>" class="button button-secondary"><?php esc_html_e('Manage Plugins', 'go-portfolio'); ?></a>
                    </div>
                </div>
                <div class="plugin-icon-recomend">🔄</div>
            </div>
        </div>
        <?php
    }
    
    /**
     * Get plugins that need updates
     */
    private function get_plugins_needing_updates() {
        $all_plugins = $this->get_recommended_plugins();
        $plugins_need_update = array();
        
        foreach ($all_plugins as $slug => $plugin) {
            $status = $this->get_plugin_status($plugin);
            if (in_array($status, array('needs-update', 'inactive-needs-update'))) {
                $plugins_need_update[$slug] = $plugin;
            }
        }
        
        return $plugins_need_update;
    }
    
    /**
     * AJAX handler for dismissing update notice
     */
    public function ajax_dismiss_update_notice() {
        check_ajax_referer('magical_plugin_activation_plugins_nonce', 'nonce');
        
        // Save the current timestamp when update notice is dismissed
        update_user_meta(get_current_user_id(), 'magical_plugin_activation_hide_update_notice_time', time());
        wp_send_json_success();
    }
    
    /**
     * AJAX handler for dismissing install notice
     */
    public function ajax_dismiss_install_notice() {
        check_ajax_referer('magical_plugin_activation_plugins_nonce', 'nonce');
        
        // Save the current timestamp when install notice is dismissed
        update_user_meta(get_current_user_id(), 'magical_plugin_activation_hide_install_notice_time', time());
        wp_send_json_success();
    }
    
    /**
     * AJAX handler for dismissing plugin notice
     */
    public function ajax_dismiss_plugin_notice() {
        check_ajax_referer('magical_plugin_activation_plugins_nonce', 'nonce');
        
        $notice_type = sanitize_text_field($_POST['notice_type']);
        
        if ($notice_type === 'recommended') {
            // Save the current timestamp when recommended notice is dismissed
            update_user_meta(get_current_user_id(), 'magical_plugin_activation_info_recomend_plugins', time());
        }
        
        wp_send_json_success();
    }
    
    /**
     * AJAX handler for updating all plugins
     */
    public function ajax_update_all_plugins() {
        check_ajax_referer('magical_plugin_activation_plugins_nonce', 'nonce');
        
        if (!current_user_can('update_plugins')) {
            wp_die(__('You do not have permission to update plugins.', 'go-portfolio'));
        }
        
        $plugins_need_update = $this->get_plugins_needing_updates();
        $results = array();
        $success_count = 0;
        $total_count = count($plugins_need_update);
        
        foreach ($plugins_need_update as $slug => $plugin) {
            $is_local = $this->is_local_plugin($plugin);
            
            if ($is_local) {
                $result = $this->update_local_plugin($plugin);
            } else {
                $result = $this->update_wordpress_org_plugin($plugin['file']);
            }
            
            if ($result) {
                $results[$slug] = 'updated';
                $success_count++;
            } else {
                $results[$slug] = 'failed';
            }
        }
        
        if ($success_count === $total_count) {
            wp_send_json_success(array(
                'message' => sprintf(__('%d plugins updated successfully.', 'go-portfolio'), $success_count),
                'results' => $results
            ));
        } else {
            wp_send_json_error(array(
                'message' => sprintf(__('%d of %d plugins updated successfully.', 'go-portfolio'), $success_count, $total_count),
                'results' => $results
            ));
        }
    }
    
    /**
     * Enqueue admin scripts and styles
     */
    public function enqueue_admin_scripts($hook) {
        // Load scripts on plugin page, dashboard, themes page, and plugins page
        if (!in_array($hook, array('appearance_page_magical-plugin-activation-plugins', 'index.php', 'themes.php', 'plugins.php'))) {
            return;
        }
        
        wp_enqueue_script(
            'magical-plugin-activation-plugins-admin',
            $this->plugin_url . '/assets/js/admin-plugins.js',
            array('jquery'),
            '1.0.0',
            true
        );
        
        wp_localize_script('magical-plugin-activation-plugins-admin', 'MagicalPluginActivation', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('magical_plugin_activation_plugins_nonce'),
            'strings' => array(
                'installing' => __('Installing...', 'go-portfolio'),
                'activating' => __('Activating...', 'go-portfolio'),
                'updating' => __('Updating...', 'go-portfolio'),
                'installed' => __('Installed', 'go-portfolio'),
                'activated' => __('Activated', 'go-portfolio'),
                'updated' => __('Updated', 'go-portfolio'),
                'active' => __('Active', 'go-portfolio'),
                'inactive' => __('Inactive', 'go-portfolio'),
                'notInstalled' => __('Not Installed', 'go-portfolio'),
                'needsUpdate' => __('Update Available', 'go-portfolio'),
                'inactiveNeedsUpdate' => __('Update Available (Inactive)', 'go-portfolio'),
                'install' => __('Install', 'go-portfolio'),
                'activate' => __('Activate', 'go-portfolio'),
                'update' => __('Update', 'go-portfolio'),
                'error' => __('Error occurred', 'go-portfolio'),
                'essentialWarning' => __('You must install and activate all essential plugins before dismissing this notice. Click Dismiss again to hide this warning.', 'go-portfolio'),
            )
        ));
        
        wp_enqueue_style(
            'magical-plugin-activation-plugins-admin',
            $this->plugin_url . '/assets/css/admin-plugins.css',
            array(),
            '1.0.0'
        );
    }
    
    /**
     * AJAX handler for plugin installation
     */
    public function ajax_install_plugin() {
        check_ajax_referer('magical_plugin_activation_plugins_nonce', 'nonce');
        
        if (!current_user_can('install_plugins')) {
            wp_die(__('You do not have permission to install plugins.', 'go-portfolio'));
        }
        
        $slug = sanitize_text_field($_POST['slug']);
        $is_local = isset($_POST['is_local']) && $_POST['is_local'] === '1';
        
        if ($is_local) {
            // Handle local plugin installation
            $plugins = $this->get_recommended_plugins();
            
            if (!isset($plugins[$slug])) {
                wp_send_json_error(__('Plugin not found in recommendations.', 'go-portfolio'));
            }
            
            $plugin = $plugins[$slug];
            
            // Check if source file exists
            if (!isset($plugin['source']) || !file_exists($plugin['source'])) {
                // Try to construct the path manually if not set correctly
                if (!isset($plugin['source'])) {
                    $manual_source = get_template_directory() . '/libs/plugins/' . $slug . '.zip';
                    if (file_exists($manual_source)) {
                        $plugin['source'] = $manual_source;
                    }
                }
                
                if (!isset($plugin['source']) || !file_exists($plugin['source'])) {
                    wp_send_json_error(__('Local plugin zip file not found. Please ensure the plugin zip file exists in the theme directory.', 'go-portfolio'));
                }
            }
            
            // Check if ZipArchive is available - removed strict requirement
            // We now have fallback methods that work without ZipArchive
            if (!class_exists('ZipArchive')) {
                // Continue anyway - we have fallback extraction methods
            }
            
            // Check if plugins directory is writable
            if (!is_writable(WP_PLUGIN_DIR)) {
                wp_send_json_error(__('Plugins directory is not writable. Please check directory permissions.', 'go-portfolio'));
            }
            
            $result = $this->install_local_plugin($plugin);
            
            if ($result) {
                wp_send_json_success(__('Local plugin installed successfully.', 'go-portfolio'));
            } else {
                wp_send_json_error(__('Local plugin installation failed. Please check error logs for details.', 'go-portfolio'));
            }
        } else {
            // Handle WordPress.org plugin installation
            include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
            include_once ABSPATH . 'wp-admin/includes/plugin-install.php';
            
            $api = plugins_api('plugin_information', array('slug' => $slug));
            
            if (is_wp_error($api)) {
                wp_send_json_error(__('Plugin information could not be retrieved.', 'go-portfolio'));
            }
            
            $upgrader = new Plugin_Upgrader(new WP_Ajax_Upgrader_Skin());
            $install = $upgrader->install($api->download_link);
            
            if ($install) {
                wp_send_json_success(__('Plugin installed successfully.', 'go-portfolio'));
            } else {
                wp_send_json_error(__('Plugin installation failed.', 'go-portfolio'));
            }
        }
    }
    
    /**
     * AJAX handler for plugin activation
     */
    public function ajax_activate_plugin() {
        check_ajax_referer('magical_plugin_activation_plugins_nonce', 'nonce');
        
        if (!current_user_can('activate_plugins')) {
            wp_die(__('You do not have permission to activate plugins.', 'go-portfolio'));
        }
        
        $plugin_file = sanitize_text_field($_POST['file']);
        
        $result = activate_plugin($plugin_file);
        
        if (is_wp_error($result)) {
            wp_send_json_error($result->get_error_message());
        } else {
            wp_send_json_success(__('Plugin activated successfully.', 'go-portfolio'));
        }
    }
    
    /**
     * AJAX handler for plugin updates
     */
    public function ajax_update_plugin() {
        check_ajax_referer('magical_plugin_activation_plugins_nonce', 'nonce');
        
        if (!current_user_can('update_plugins')) {
            wp_die(__('You do not have permission to update plugins.', 'go-portfolio'));
        }
        
        $slug = sanitize_text_field($_POST['slug']);
        $plugin_file = sanitize_text_field($_POST['file']);
        $is_local = isset($_POST['is_local']) && $_POST['is_local'] === '1';
        
        $plugins = $this->get_recommended_plugins();
        
        if (!isset($plugins[$slug])) {
            wp_send_json_error(__('Plugin not found.', 'go-portfolio'));
        }
        
        $plugin = $plugins[$slug];
        
        // Handle local plugin update
        if ($is_local) {
            $result = $this->update_local_plugin($plugin);
            
            if ($result) {
                wp_send_json_success(__('Local plugin updated successfully.', 'go-portfolio'));
            } else {
                wp_send_json_error(__('Failed to update local plugin.', 'go-portfolio'));
            }
        } else {
            // Handle WordPress.org plugin update
            $result = $this->update_wordpress_org_plugin($plugin_file);
            
            if ($result) {
                wp_send_json_success(__('Plugin updated successfully.', 'go-portfolio'));
            } else {
                wp_send_json_error(__('Failed to update plugin.', 'go-portfolio'));
            }
        }
    }
    
    /**
     * AJAX handler for bulk installing required plugins
     */
    public function ajax_install_essential_plugins() {
        check_ajax_referer('magical_plugin_activation_plugins_nonce', 'nonce');
        
        if (!current_user_can('install_plugins')) {
            wp_die(__('You do not have permission to install plugins.', 'go-portfolio'));
        }
        
        $essential_plugins = $this->get_essential_plugins();
        $results = array();
        $success_count = 0;
        $total_count = count($essential_plugins);
        
        foreach ($essential_plugins as $slug => $plugin) {
            $status = $this->get_plugin_status($plugin);
            
            if ($status === 'active') {
                $results[$slug] = 'already-active';
                $success_count++;
            } else {
                $installed = $this->install_and_activate_plugin($plugin);
                
                if ($installed) {
                    $results[$slug] = 'installed-and-activated';
                    $success_count++;
                } else {
                    $results[$slug] = 'failed';
                }
            }
        }
        
        if ($success_count === $total_count) {
            wp_send_json_success(array(
                'message' => sprintf(__('%d essential plugins installed and activated successfully.', 'go-portfolio'), $success_count),
                'results' => $results
            ));
        } else {
            wp_send_json_error(array(
                'message' => sprintf(__('%d of %d essential plugins installed successfully.', 'go-portfolio'), $success_count, $total_count),
                'results' => $results
            ));
        }
    }
    
    /**
     * AJAX handler for bulk installing recommended plugins
     */
    public function ajax_install_recommended_plugins() {
        check_ajax_referer('magical_plugin_activation_plugins_nonce', 'nonce');
        
        if (!current_user_can('install_plugins')) {
            wp_die(__('You do not have permission to install plugins.', 'go-portfolio'));
        }
        
        $recommended_plugins = $this->get_recommended_plugins();
        $results = array();
        $success_count = 0;
        $total_count = count($recommended_plugins);
        
        foreach ($recommended_plugins as $slug => $plugin) {
            $status = $this->get_plugin_status($plugin);
            
            if ($status === 'active') {
                $results[$slug] = 'already-active';
                $success_count++;
            } else {
                $installed = $this->install_and_activate_plugin($plugin);
                
                if ($installed) {
                    $results[$slug] = 'installed-and-activated';
                    $success_count++;
                } else {
                    $results[$slug] = 'failed';
                }
            }
        }
        
        if ($success_count === $total_count) {
            wp_send_json_success(array(
                'message' => sprintf(__('%d recommended plugins installed and activated successfully.', 'go-portfolio'), $success_count),
                'results' => $results
            ));
        } else {
            wp_send_json_error(array(
                'message' => sprintf(__('%d of %d recommended plugins installed successfully.', 'go-portfolio'), $success_count, $total_count),
                'results' => $results
            ));
        }
    }
    
    /**
     * AJAX handler for bulk installing all plugins (required + recommended)
     */
    public function ajax_install_all_plugins() {
        check_ajax_referer('magical_plugin_activation_plugins_nonce', 'nonce');
        
        if (!current_user_can('install_plugins')) {
            wp_die(__('You do not have permission to install plugins.', 'go-portfolio'));
        }
        
        $all_plugins = $this->get_recommended_plugins();
        $results = array();
        $success_count = 0;
        $total_count = count($all_plugins);
        
        foreach ($all_plugins as $slug => $plugin) {
            $status = $this->get_plugin_status($plugin);
            
            if ($status === 'active') {
                $results[$slug] = 'already-active';
                $success_count++;
            } else {
                $installed = $this->install_and_activate_plugin($plugin);
                
                if ($installed) {
                    $results[$slug] = 'installed-and-activated';
                    $success_count++;
                } else {
                    $results[$slug] = 'failed';
                }
            }
        }
        
        if ($success_count === $total_count) {
            wp_send_json_success(array(
                'message' => sprintf(__('%d plugins installed and activated successfully.', 'go-portfolio'), $success_count),
                'results' => $results
            ));
        } else {
            wp_send_json_error(array(
                'message' => sprintf(__('%d of %d plugins installed successfully.', 'go-portfolio'), $success_count, $total_count),
                'results' => $results
            ));
        }
    }
    
    /**
     * AJAX handler to check if all recommended plugins are active
     */
    public function ajax_check_recommended_plugins_status() {
        check_ajax_referer('magical_plugin_activation_plugins_nonce', 'nonce');
        
        $all_active = $this->are_all_recommended_plugins_active();
        
        wp_send_json_success(array(
            'all_active' => $all_active,
            'message' => $all_active ? __('All recommended plugins are active!', 'go-portfolio') : __('Some recommended plugins are not active.', 'go-portfolio')
        ));
    }
    
    /**
     * Show recommendation notice in admin
     */
    public function show_recommendation_notice() {
        $screen = get_current_screen();
        
        if (!in_array($screen->id, array('dashboard', 'themes', 'plugins', 'appearance_page_magical-plugin-activation-plugins'))) {
            return;
        }
        
        if (in_array($screen->id, array('dashboard', 'themes', 'plugins'))) {
            $this->show_recommended_plugins_notice();
        }
    }
    
    /**
     * Show recommended plugins notice (dismissible)
     */
    private function show_recommended_plugins_notice() {
        // Check if user dismissed the recommended plugins notice
        $dismissed_time = get_user_meta(get_current_user_id(), 'magical_plugin_activation_info_recomend_plugins', true);
        $show_recommended_notice = !$dismissed_time || (time() - $dismissed_time) >= (30 * 24 * 60 * 60); // 30 days
        
        if (!$show_recommended_notice) {
            return;
        }
        
        // Check if all plugins are active
        if ($this->are_all_recommended_plugins_active()) {
            return;
        }
        
        $all_plugins = $this->get_recommended_plugins();
        $missing_essential = array();
        $inactive_recommended = array();
        
        foreach ($all_plugins as $slug => $plugin) {
            $status = $this->get_plugin_status($plugin);
            if ($status !== 'active') {
                if (!empty($plugin['essential'])) {
                    $missing_essential[$slug] = $plugin;
                } else {
                    $inactive_recommended[$slug] = $plugin;
                }
            }
        }
        
        $has_essential = !empty($missing_essential);
        $total_missing = count($missing_essential) + count($inactive_recommended);
        
        if ($total_missing === 0) {
            return;
        }
        
        $button_id = 'install-all-plugins';
        $button_text = __('Install Plugins', 'go-portfolio');
        $heading = __('Plugins for %s', 'go-portfolio');
        $description = $has_essential
            ? __('%d plugins are missing or inactive. Install them for optimal theme functionality.', 'go-portfolio')
            : __('We recommend %d additional plugins to enhance your %s theme experience.', 'go-portfolio');
        
        ?>
        <div class="notice notice-info is-dismissible magical-plugin-activation-pro-recommended-notice">
            <div class="plugin-recommended-content">
                <div style="flex: 1;">
                    <h3><?php printf(esc_html($heading), $this->theme_name); ?></h3>
                    <p><?php printf(esc_html($description), $total_missing, $this->theme_name); ?></p>
                    <div style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap; margin-top: 15px;">
                        <button id="<?php echo esc_attr($button_id); ?>" class="button button-primary"><?php echo esc_html($button_text); ?></button>
                        <a href="<?php echo esc_url(admin_url('themes.php?page=magical-plugin-activation-plugins')); ?>" class="button button-secondary"><?php esc_html_e('View All Plugins', 'go-portfolio'); ?></a>
                        <button class="install-dismiss" class="button" style="margin-left: 10px;">
                        <?php esc_html_e('Dismiss', 'go-portfolio'); ?>
                        </button>
                    </div>
                </div>
                <div class="plugin-icon-recomend">🔌</div>
            </div>
        </div>
        <?php
    }
    
    /**
     * Auto-install essential plugins on theme activation
     */
    public function auto_install_essential_plugins() {
        // Check if we have permission to install plugins
        if (!current_user_can('install_plugins')) {
            return;
        }
        
        // Load required admin files for upgrader
        if (!function_exists('request_filesystem_credentials')) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
        }
        
        // Get all essential plugins
        $essential_plugins = $this->get_essential_plugins();
        
        if (empty($essential_plugins)) {
            return;
        }
        
        // Set a flag to indicate we've processed theme activation
        set_transient('magical_plugin_activation_theme_activated', true, 30);
        
        // Install and activate required plugins
        foreach ($essential_plugins as $plugin) {
            $this->install_and_activate_plugin($plugin);
        }
        
        // Set a notice that plugins were installed
        set_transient('magical_plugin_activation_plugins_installed', true, 300);
    }
    
    /**
     * Auto-install essential plugins after theme update
     */
    public function auto_install_on_theme_update($upgrader, $hook_extra) {
        if (!isset($hook_extra['type']) || $hook_extra['type'] !== 'theme') {
            return;
        }
        
        if (!isset($hook_extra['themes']) || !is_array($hook_extra['themes'])) {
            return;
        }
        
        $current_theme = get_template();
        if (!in_array($current_theme, $hook_extra['themes'])) {
            return;
        }
        
        $this->auto_install_essential_plugins();
    }
    
    /**
     * Check for required plugins on admin init
     */
    public function check_essential_plugins() {
        // Only check if user can install plugins
        if (!current_user_can('install_plugins')) {
            return;
        }
        
        // Only check once per session to avoid performance issues
        if (get_transient('magical_plugin_activation_plugins_checked')) {
            return;
        }
        
        // Set transient to avoid repeated checks
        set_transient('magical_plugin_activation_plugins_checked', true, 3600); // 1 hour
        
        // Get required plugins
        $essential_plugins = $this->get_essential_plugins();
        
        if (empty($essential_plugins)) {
            return;
        }
        
        // Check each required plugin
        foreach ($essential_plugins as $plugin) {
            $status = $this->get_plugin_status($plugin);
            
            if ($status === 'not-installed') {
                $this->install_and_activate_plugin($plugin);
            } elseif ($status === 'inactive') {
                $this->activate_plugin_silent($plugin);
            }
        }
    }
    
    /**
     * Get only required plugins
     */
    private function get_essential_plugins() {
        $all_plugins = $this->get_recommended_plugins();
        $essential_plugins = array();
        
        foreach ($all_plugins as $slug => $plugin) {
            if (isset($plugin['essential']) && $plugin['essential'] === true) {
                $essential_plugins[$slug] = $plugin;
            }
        }
        
        return $essential_plugins;
    }
    
    /**
     * Install and activate a plugin silently
     */
    private function install_and_activate_plugin($plugin) {
        // Check if plugin is already active
        if ($this->get_plugin_status($plugin) === 'active') {
            return true;
        }
        
        // Install plugin if not installed
        if ($this->get_plugin_status($plugin) === 'not-installed') {
            $installed = $this->install_plugin_silent($plugin);
            if (!$installed) {
                return false;
            }
        }
        
        // Activate plugin
        return $this->activate_plugin_silent($plugin);
    }
    
    /**
     * Install plugin silently
     */
    private function install_plugin_silent($plugin) {
        try {
            // Load the silent upgrader skin class
            magical_plugin_activation_load_silent_upgrader_skin();
            
            // Handle local plugin installation
            if ($this->is_local_plugin($plugin)) {
                return $this->install_local_plugin($plugin);
            }
            
            // Handle WordPress.org plugin installation
            return $this->install_wordpress_org_plugin($plugin);
            
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Install local plugin from zip file
     */
    private function install_local_plugin($plugin) {
        // Basic checks
        if (!isset($plugin['source']) || !file_exists($plugin['source'])) {
            return false;
        }

        if (!class_exists('ZipArchive')) {
            // Try WordPress built-in unzip_file first
            if (!function_exists('unzip_file')) {
                require_once ABSPATH . 'wp-admin/includes/file.php';
            }
            
            if (function_exists('unzip_file')) {
                // Use WordPress plugins directory
                $wp_plugins_dir = WP_CONTENT_DIR . '/plugins';
                
                // Try direct filesystem method - bypass credentials
                global $wp_filesystem;
                if (!$wp_filesystem) {
                    require_once ABSPATH . 'wp-admin/includes/file.php';
                    // Force direct filesystem method
                    add_filter('filesystem_method', function() { return 'direct'; });
                    WP_Filesystem();
                }
                
                $unzip_result = unzip_file($plugin['source'], $wp_plugins_dir);
                
                if (!is_wp_error($unzip_result)) {
                    // Check if the plugin file exists
                    $plugin_file_path = $wp_plugins_dir . '/' . $plugin['file'];
                    if (file_exists($plugin_file_path)) {
                        return true;
                    } else {
                        return $this->locate_extracted_plugin($plugin, $wp_plugins_dir);
                    }
                }
            }
            
            // Fallback: Try PclZip (WordPress included)
            if (!class_exists('PclZip')) {
                $pclzip_path = ABSPATH . 'wp-admin/includes/class-pclzip.php';
                if (file_exists($pclzip_path)) {
                    require_once $pclzip_path;
                }
            }
            
            if (class_exists('PclZip')) {
                $wp_plugins_dir = WP_CONTENT_DIR . '/plugins';
                
                $archive = new PclZip($plugin['source']);
                $result = $archive->extract(PCLZIP_OPT_PATH, $wp_plugins_dir);
                
                if ($result !== 0) {
                    // Check if plugin file exists
                    $plugin_file_path = $wp_plugins_dir . '/' . $plugin['file'];
                    if (file_exists($plugin_file_path)) {
                        return true;
                    } else {
                        return $this->locate_extracted_plugin($plugin, $wp_plugins_dir);
                    }
                }
            }
            
            // If all extraction methods fail, return false
            return false;
        }
        
        if (!is_writable(WP_PLUGIN_DIR)) {
            return false;
        }

        $plugin_file_path = WP_PLUGIN_DIR . '/' . $plugin['file'];
        if (file_exists($plugin_file_path)) {
            return true;
        }
        
        // Use a simple direct extraction approach
        $zip = new ZipArchive();
        $open_result = $zip->open($plugin['source']);
        
        if ($open_result !== TRUE) {
            $zip_errors = array(
                ZipArchive::ER_OK => 'No error',
                ZipArchive::ER_MULTIDISK => 'Multi-disk zip archives not supported',
                ZipArchive::ER_RENAME => 'Renaming temporary file failed',
                ZipArchive::ER_CLOSE => 'Closing zip archive failed',
                ZipArchive::ER_SEEK => 'Seek error',
                ZipArchive::ER_READ => 'Read error',
                ZipArchive::ER_WRITE => 'Write error',
                ZipArchive::ER_CRC => 'CRC error',
                ZipArchive::ER_ZIPCLOSED => 'Containing zip archive was closed',
                ZipArchive::ER_NOENT => 'No such file',
                ZipArchive::ER_EXISTS => 'File already exists',
                ZipArchive::ER_OPEN => 'Can\'t open file',
                ZipArchive::ER_TMPOPEN => 'Failure to create temporary file',
                ZipArchive::ER_ZLIB => 'Zlib error',
                ZipArchive::ER_MEMORY => 'Memory allocation failure',
                ZipArchive::ER_CHANGED => 'Entry has been changed',
                ZipArchive::ER_COMPNOTSUPP => 'Compression method not supported',
                ZipArchive::ER_EOF => 'Premature EOF',
                ZipArchive::ER_INVAL => 'Invalid argument',
                ZipArchive::ER_NOZIP => 'Not a zip archive',
                ZipArchive::ER_INTERNAL => 'Internal error',
                ZipArchive::ER_INCONS => 'Zip archive inconsistent',
                ZipArchive::ER_REMOVE => 'Can\'t remove file',
                ZipArchive::ER_DELETED => 'Entry has been deleted',
            );
            $error_message = isset($zip_errors[$open_result]) ? $zip_errors[$open_result] : 'Unknown error';
            return false;
        }
        
        // Extract directly to plugins directory
        $extract_result = $zip->extractTo(WP_PLUGIN_DIR);
        $zip->close();
        
        if (!$extract_result) {
            return false;
        }
        
        // Check if the expected plugin file now exists
        if (file_exists($plugin_file_path)) {
            return true;
        }
        
        // Look for the plugin directory based on slug
        $plugin_slug = $plugin['slug'];
        $possible_dirs = array(
            WP_PLUGIN_DIR . '/' . $plugin_slug,
            WP_PLUGIN_DIR . '/' . $plugin_slug . '-master',
            WP_PLUGIN_DIR . '/' . strtolower($plugin_slug),
        );
        
        foreach ($possible_dirs as $dir) {
            if (is_dir($dir)) {
                $php_files = glob($dir . '/*.php');
                foreach ($php_files as $file) {
                    // Check if this looks like a main plugin file using WordPress function
                    if (!function_exists('get_plugin_data')) {
                        require_once ABSPATH . 'wp-admin/includes/plugin.php';
                    }
                    
                    // Use WordPress function to safely check plugin headers
                    $plugin_data = get_plugin_data($file, false, false);
                    if (!empty($plugin_data['Name'])) {
                        return true;
                    }
                }
            }
        }
        
        return false;
    }
    
    /**
     * Try to locate extracted plugin in plugins directory
     */
    private function locate_extracted_plugin($plugin, $plugin_dir) {
        $plugin_slug = $plugin['slug'];
        
        // Common directory patterns for extracted plugins
        $possible_dirs = array(
            $plugin_dir . '/' . $plugin_slug,
            $plugin_dir . '/' . $plugin_slug . '-master',
            $plugin_dir . '/' . strtolower($plugin_slug),
            $plugin_dir . '/' . ucfirst($plugin_slug),
            $plugin_dir . '/' . str_replace('-', '_', $plugin_slug),
        );
        
        foreach ($possible_dirs as $dir) {
            $expected_file = $dir . '/' . basename($plugin['file']);
            
            if (file_exists($expected_file)) {
                return true;
            }
            
            // Also check if directory exists and scan for PHP files
            if (is_dir($dir)) {
                $php_files = glob($dir . '/*.php');
                foreach ($php_files as $file) {
                    // Check if this looks like a main plugin file using WordPress function
                    if (!function_exists('get_plugin_data')) {
                        require_once ABSPATH . 'wp-admin/includes/plugin.php';
                    }
                    
                    // Use WordPress function to safely check plugin headers
                    $plugin_data = get_plugin_data($file, false, false);
                    if (!empty($plugin_data['Name'])) {
                        return true;
                    }
                }
            }
        }
        
        return false;
    }

    /**
     * Fallback manual extraction when WordPress methods fail
     */

    /**
     * Install WordPress.org plugin
     */
    private function install_wordpress_org_plugin($plugin) {
        try {
            include_once ABSPATH . 'wp-admin/includes/plugin-install.php';
            
            // Get plugin info from WordPress.org
            $api = plugins_api('plugin_information', array(
                'slug' => $plugin['slug'],
                'fields' => array(
                    'short_description' => false,
                    'sections' => false,
                    'requires' => false,
                    'rating' => false,
                    'ratings' => false,
                    'downloaded' => false,
                    'last_updated' => false,
                    'added' => false,
                    'tags' => false,
                    'compatibility' => false,
                    'homepage' => false,
                    'donate_link' => false,
                )
            ));
            
            if (is_wp_error($api)) {
                return false;
            }
            
            // Use a silent upgrader
            $upgrader = new Plugin_Upgrader(new magical_plugin_activation_Silent_Upgrader_Skin());
            $install = $upgrader->install($api->download_link);
            
            return !is_wp_error($install) && $install;
            
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Update local plugin from zip file
     */
    private function update_local_plugin($plugin) {
        try {
            // Check if source file exists
            if (!isset($plugin['source']) || !file_exists($plugin['source'])) {
                return false;
            }
            
            // Deactivate plugin before update
            if (is_plugin_active($plugin['file'])) {
                deactivate_plugins($plugin['file'], true);
            }
            
            // Get plugin directory
            $plugin_dir = WP_PLUGIN_DIR . '/' . dirname($plugin['file']);
            
            // Remove existing plugin directory
            if (is_dir($plugin_dir)) {
                $this->delete_directory($plugin_dir);
            }
            
            // Extract new plugin
            $result = $this->extract_plugin_zip($plugin['source'], WP_PLUGIN_DIR);
            
            if ($result) {
                // Reactivate plugin
                activate_plugin($plugin['file'], '', false, true);
                return true;
            }
            
            return false;
            
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Update WordPress.org plugin
     */
    private function update_wordpress_org_plugin($plugin_file) {
        try {
            include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
            
            // Load the silent upgrader skin class
            magical_plugin_activation_load_silent_upgrader_skin();
            
            $upgrader = new Plugin_Upgrader(new magical_plugin_activation_Silent_Upgrader_Skin());
            $result = $upgrader->upgrade($plugin_file);
            
            return !is_wp_error($result) && $result;
            
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Activate plugin silently
     */
    private function activate_plugin_silent($plugin) {
        try {
            $result = activate_plugin($plugin['file'], '', false, true);
            return !is_wp_error($result);
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Extract plugin zip file
     */
    private function extract_plugin_zip($zip_file, $destination) {
        if (!class_exists('ZipArchive')) {
            return false;
        }
        
        if (!file_exists($zip_file) || !is_readable($zip_file)) {
            return false;
        }
        
        $zip = new ZipArchive();
        $result = $zip->open($zip_file);
        
        if ($result !== TRUE) {
            return false;
        }
        
        $extract_result = $zip->extractTo($destination);
        $zip->close();
        
        return $extract_result;
    }
    
    /**
     * Delete directory recursively
     */
    private function delete_directory($dir) {
        if (!is_dir($dir)) {
            return false;
        }
        
        $files = array_diff(scandir($dir), array('.', '..'));
        
        foreach ($files as $file) {
            $path = $dir . '/' . $file;
            if (is_dir($path)) {
                $this->delete_directory($path);
            } else {
                unlink($path);
            }
        }
        
        return rmdir($dir);
    }
    
    /**
     * Check if plugin installation/activation functions are available
     */
    private function check_plugin_functions() {
        if (!function_exists('is_plugin_active')) {
            include_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
    }
    
    /**
     * Migrate old notice dismissal to new system
     */
    private function migrate_notice_dismissal() {
        // Check if migration has already been done
        if (get_option('magical_plugin_activation_notice_migration_done')) {
            return;
        }
        
        // Reset old dismissals for migration
        delete_user_meta(get_current_user_id(), 'magical_plugin_activation_hide_essential_plugins_notice');
        delete_user_meta(get_current_user_id(), 'magical_plugin_activation_hide_recommended_plugins_notice');
        delete_user_meta(get_current_user_id(), 'magical_plugin_activation_hide_update_notice_time');
        
        // Mark migration as done
        update_option('magical_plugin_activation_notice_migration_done', true);
    }
    
    /**
     * Check if all recommended plugins are active
     */
    private function are_all_recommended_plugins_active() {
        $all_plugins = $this->get_recommended_plugins();
        
        foreach ($all_plugins as $plugin) {
            if ($this->get_plugin_status($plugin) !== 'active') {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Store theme install time on first activation
     */
    public function store_theme_install_time() {
        if (!get_option('magical_plugin_activation_install_time')) {
            update_option('magical_plugin_activation_install_time', time(), false);
        }
    }
    
    /**
     * Get plugins with timed auto-activation
     */
    private function get_timed_auto_plugins() {
        $all_plugins = $this->get_recommended_plugins();
        $timed_plugins = array();
        
        foreach ($all_plugins as $slug => $plugin) {
            if (isset($plugin['timed_auto']) && $plugin['timed_auto'] === true) {
                $timed_plugins[$slug] = $plugin;
            }
        }
        
        return $timed_plugins;
    }
    
    /**
     * Clear the "already ran" flag when a timed auto plugin is manually activated.
     */
    public function clear_user_deactivation($plugin_file) {
        $slug = dirname($plugin_file);
        $timed_plugins = $this->get_timed_auto_plugins();
        if (!isset($timed_plugins[$slug])) {
            return;
        }
        $ran = get_option('magical_plugin_timed_auto_ran', array());
        if (isset($ran[$slug])) {
            unset($ran[$slug]);
            update_option('magical_plugin_timed_auto_ran', $ran, false);
        }
    }

    /**
     * Check timed auto plugins on admin_init.
     * Fires ONCE 20 hours after install. Never fires again after that.
     */
    public function check_timed_auto_plugins() {
        $ran = get_option('magical_plugin_timed_auto_ran', array());
        $timed_plugins = $this->get_timed_auto_plugins();
        if (empty($timed_plugins)) {
            return;
        }

        $install_time = get_option('magical_plugin_activation_install_time');
        if (!$install_time) {
            $this->store_theme_install_time();
            $install_time = get_option('magical_plugin_activation_install_time');
        }

        $delay = apply_filters('magical_plugin_activation_timed_auto_delay', 20 * HOUR_IN_SECONDS);
        if (time() < ($install_time + $delay)) {
            return;
        }

        $new_ran = false;

        foreach ($timed_plugins as $slug => $plugin) {
            if (isset($ran[$slug])) {
                continue;
            }
            $status = $this->get_plugin_status($plugin);
            if ($status !== 'active') {
                $this->install_and_activate_plugin($plugin);
            }
            $ran[$slug] = time();
            $new_ran = true;
        }

        if ($new_ran) {
            update_option('magical_plugin_timed_auto_ran', $ran, false);
        }
    }
    
}

// Initialize the plugin recommendations
new magical_plugin_activation_Plugin_Recommendations();


