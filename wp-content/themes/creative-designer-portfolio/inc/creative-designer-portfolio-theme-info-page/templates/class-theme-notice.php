<?php
/**
 * Theme Info Page (optimized)
 *
 * @package Creative Designer Portfolio
 */

namespace Creative_Designer_Portfolio;

if ( ! class_exists( '\WP_Upgrader_Skin' ) ) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
}

use const DAY_IN_SECONDS;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Ensure required WP files are available before we extend or call WP classes/functions.
 * These are only required in admin context; if loaded on front-end, they still safely exist but we guard with is_admin().
 */
if ( is_admin() ) {
	if ( ! function_exists( 'get_plugins' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}
	require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/misc.php';
	if ( ! class_exists( 'WP_Upgrader' ) ) {
		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
	}
}

/**
 * Simple silent upgrader skin to suppress output.
 */
class Silent_Skin extends \WP_Upgrader_Skin {
	public function header() {}
	public function footer() {}
	public function feedback( $string, ...$args ) {}
	public function error( $errors ) {}
	public function before() {}
	public function after() {}
}

/* -------------------------------
 * Helper functions (namespaced)
 * ------------------------------- */

/**
 * Check if a plugin folder exists in plugins directory.
 *
 * @param string $plugin_slug Plugin folder slug.
 * @return bool
 */
function is_creative_designer_portfolio_plugin_installed( $plugin_slug ) {
	$installed_plugins = \get_plugins();
	foreach ( $installed_plugins as $path => $details ) {
		if ( 0 === strpos( $path, $plugin_slug ) ) {
			return true;
		}
	}
	return false;
}

/**
 * Update install/activate progress stored in transient.
 *
 * @param string $plugin_name Plugin human name.
 * @param string $status      Status string.
 * @return void
 */
function update_install_and_activate_progress( $plugin_name, $status ) {
	$progress = (array) \get_transient( 'install_and_activate_progress' );
	$progress[] = array(
		'plugin' => $plugin_name,
		'status' => $status,
	);
	\set_transient( 'install_and_activate_progress', $progress, MINUTE_IN_SECONDS * 10 );
}

/**
 * Get the list of recommended plugins.
 *
 * @return array
 */
function creative_designer_portfolio_get_recommended_plugins() {
	return array(
		array(
			'name' => __( 'Siteready Coming Soon Under Construction', 'creative-designer-portfolio' ),
			'slug' => 'siteready-coming-soon-under-construction',
			'file' => 'siteready-coming-soon-under-construction.php',
		),
	);
}

/**
 * Check whether all recommended plugins are active.
 *
 * @return bool
 */
function creative_designer_portfolio_is_activation_completed() {
	if ( ! function_exists( 'is_plugin_active' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}
	foreach ( creative_designer_portfolio_get_recommended_plugins() as $plugin ) {
		if ( ! \is_plugin_active( $plugin['slug'] . '/' . $plugin['file'] ) ) {
			return false;
		}
	}
	return true;
}

/* -------------------------------
 * AJAX: Install & Activate Plugins
 * ------------------------------- */

/**
 * Install and activate recommended plugins (AJAX).
 *
 * Expects:
 * - nonce (key: 'nonce')
 *
 * Returns JSON: { success: true, data: { redirect_url: '...' } }
 *
 * @return void
 */
function creative_designer_portfolio_install_and_activate_plugins() {

	// Capability check.
	if ( ! \current_user_can( 'manage_options' ) ) {
		\wp_send_json_error( array( 'message' => __( 'Insufficient permissions', 'creative-designer-portfolio' ) ) );
	}

	// Nonce check (JS posts 'nonce')
	\check_ajax_referer( 'creative_designer_portfolio_welcome_nonce', 'nonce' );

	$recommended_plugins = creative_designer_portfolio_get_recommended_plugins();

	\set_transient( 'install_and_activate_progress', array(), MINUTE_IN_SECONDS * 10 );

	foreach ( $recommended_plugins as $plugin ) {
		$plugin_slug = $plugin['slug'];
		$plugin_file = $plugin['file'];
		$plugin_name = $plugin['name'];

		// If already active -> update it if WordPress has flagged an update available.
		if ( \is_plugin_active( $plugin_slug . '/' . $plugin_file ) ) {
			$update_plugins = \get_site_transient( 'update_plugins' );
			if ( isset( $update_plugins->response[ $plugin_slug . '/' . $plugin_file ] ) ) {
				$upgrader = new \Plugin_Upgrader( new Silent_Skin() );
				$upgrader->upgrade( $plugin_slug . '/' . $plugin_file );
				// Upgrading can deactivate the plugin as a safety step; restore its active state.
				if ( ! \is_plugin_active( $plugin_slug . '/' . $plugin_file ) ) {
					\activate_plugin( $plugin_slug . '/' . $plugin_file, '', false, true );
				}
				update_install_and_activate_progress( $plugin_name, 'Updated' );
			} else {
				update_install_and_activate_progress( $plugin_name, 'Already Active' );
			}
			continue;
		}

		// If installed but not active -> activate
		if ( is_creative_designer_portfolio_plugin_installed( $plugin_slug ) ) {
			$activate = \activate_plugin( $plugin_slug . '/' . $plugin_file, '', false, true );
			if ( \is_wp_error( $activate ) ) {
				update_install_and_activate_progress( $plugin_name, 'Error' );
				continue;
			}
			update_install_and_activate_progress( $plugin_name, 'Activated' );
			continue;
		}

		// Not installed -> install
		update_install_and_activate_progress( $plugin_name, 'Installing' );

		$api = \plugins_api( 'plugin_information', array(
			'slug'   => $plugin_slug,
			'fields' => array( 'sections' => false ),
		) );

		if ( \is_wp_error( $api ) ) {
			update_install_and_activate_progress( $plugin_name, 'Error' );
			continue;
		}

		// Use global Plugin_Upgrader (fully qualified)
		$upgrader = new \Plugin_Upgrader( new Silent_Skin() );
		$install  = $upgrader->install( $api->download_link );

		if ( $install ) {
			$activate = \activate_plugin( $plugin_slug . '/' . $plugin_file, '', false, true );
			if ( \is_wp_error( $activate ) ) {
				update_install_and_activate_progress( $plugin_name, 'Error' );
				continue;
			}
			update_install_and_activate_progress( $plugin_name, 'Activated' );
		} else {
			update_install_and_activate_progress( $plugin_name, 'Error' );
		}
	}

	\delete_transient( 'install_and_activate_progress' );

	if ( \ob_get_length() ) {
		\ob_clean();
	}

	$redirect_url = \admin_url( 'themes.php?page=creative-designer-portfolio-theme-info-page' );

	\wp_send_json_success( array(
		'redirect_url' => $redirect_url,
	) );
}

/* -------------------------------
 * AJAX: Dismiss Notice Handler
 * ------------------------------- */

/**
 * Handle AJAX notice dismissal (sets transient so notice stops showing for a few days).
 *
 * Expects:
 * - wpnonce (key: 'wpnonce')
 *
 * @return void
 */
function creative_designer_portfolio_dismissed_notice_handler() {
	// Must be logged in (ajax is admin-ajax)
	if ( ! \current_user_can( 'manage_options' ) ) {
		\wp_send_json_error();
	}

	\check_ajax_referer( 'creative_designer_portfolio_welcome_nonce', 'wpnonce' );

	\set_transient( 'creative_designer_portfolio_notice_dismissed', true, DAY_IN_SECONDS * 3 );

	\wp_send_json_success( array( 'message' => __( 'Notice dismissed', 'creative-designer-portfolio' ) ) );
}

/* -------------------------------
 * Register AJAX hooks (namespaced)
 * ------------------------------- */

add_action( 'wp_ajax_creative_designer_portfolio_install_and_activate_plugins', __NAMESPACE__ . '\\creative_designer_portfolio_install_and_activate_plugins' );
add_action( 'wp_ajax_nopriv_creative_designer_portfolio_install_and_activate_plugins', __NAMESPACE__ . '\\creative_designer_portfolio_install_and_activate_plugins' );

add_action( 'wp_ajax_creative_designer_portfolio_dismissed_notice_handler', __NAMESPACE__ . '\\creative_designer_portfolio_dismissed_notice_handler' );
add_action( 'wp_ajax_nopriv_creative_designer_portfolio_dismissed_notice_handler', __NAMESPACE__ . '\\creative_designer_portfolio_dismissed_notice_handler' );

/* -------------------------------
 * Enqueue admin script (localized for JS)
 * ------------------------------- */

/**
 * Enqueue admin JS/CSS for notice only on relevant admin pages.
 *
 * Uses the object `creative_designer_portfolio_localize` which your JS expects.
 *
 * @param string $hook Current admin page hook.
 * @return void
 */
function creative_designer_portfolio_enqueue_admin_script( $hook ) {

	// Only load on themes page or our theme info/guide pages (adjust slugs if needed)
	if (
		'appearance_page_creative-designer-portfolio-theme-info-page' !== $hook &&
		'themes.php' !== $hook
	) {
		return;
	}

	wp_localize_script( 'creative-designer-portfolio-welcome-notice', 'creative_designer_portfolio_localize', array(
		'ajax_url'     => esc_url_raw( admin_url( 'admin-ajax.php' ) ),
		'nonce'        => wp_create_nonce( 'creative_designer_portfolio_welcome_nonce' ), // for install action (JS uses 'nonce')
		'dismiss_nonce'=> wp_create_nonce( 'creative_designer_portfolio_welcome_nonce' ), // for dismiss action (JS posts 'wpnonce')
		'redirect_url' => esc_url_raw( admin_url( 'themes.php?page=creative-designer-portfolio-theme-info-page' ) ),
		'i18n'         => array(
			'installing' => esc_html__( 'Installing and Activating recommended plugins…', 'creative-designer-portfolio' ),
			'retry'      => esc_html__( 'Installation failed. Click to retry', 'creative-designer-portfolio' ),
		),
		'activated'    => creative_designer_portfolio_is_activation_completed(),
	) );

	// Styles for notice (if any)
	wp_enqueue_style(
		'creative-designer-portfolio-theme-notice-css',
		get_template_directory_uri() . '/inc/creative-designer-portfolio-theme-info-page/css/theme-details.css',
		array(),
		'1.0.0'
	);
}
add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\\creative_designer_portfolio_enqueue_admin_script' );

/* -------------------------------
 * Theme Notice Class (renders the notice)
 * ------------------------------- */

new Creative_Designer_Portfolio_Theme_Notice();

class Creative_Designer_Portfolio_Theme_Notice {

	/** @var \WP_Theme */
	private $creative_designer_portfolio_theme;

	private $creative_designer_portfolio_url = 'https://www.themescarts.com/';

	/**
	 * Class construct.
	 */
	public function __construct() {
		$this->creative_designer_portfolio_theme = \wp_get_theme();

		// Handle dismiss via normal form POST as fallback
		add_action( 'init', array( $this, 'handle_dismiss_notice' ) );

		// Add admin notice if transient not set
		if ( ! \get_transient( 'creative_designer_portfolio_notice_dismissed' ) ) {
			add_action( 'admin_notices', array( $this, 'creative_designer_portfolio_render_notice' ) );
		}

		add_action( 'switch_theme', array( $this, 'show_notice' ) );
		// Script/style enqueue handled globally above
	}

	/**
	 * Delete notice transient on theme switch.
	 *
	 * @return void
	 */
	public function show_notice() {
		\delete_transient( 'creative_designer_portfolio_notice_dismissed' );
	}

	/**
	 * Render the admin notice.
	 */
	public function creative_designer_portfolio_render_notice() {
		?>
		<div id="creative-designer-portfolio-theme-notice" class="notice notice-info is-dismissible" data-notice="creative_designer_portfolio_welcome">
			<div class="creative-designer-portfolio-content-wrap">
				<div class="creative-designer-portfolio-notice-left">
					<?php
					$this->creative_designer_portfolio_render_title();
					$this->creative_designer_portfolio_render_content();
					$this->creative_designer_portfolio_render_actions();
					?>
				</div>
				<div class="creative-designer-portfolio-notice-right">
					<img src="<?php echo esc_url( get_template_directory_uri() . '/screenshot.png' ); ?>" alt="<?php esc_attr_e( 'Theme Notice Image', 'creative-designer-portfolio' ); ?>">
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Render title.
	 */
	protected function creative_designer_portfolio_render_title() {
		?>
		<h2>
			<?php
			printf(
				// translators: %s is the theme name
				esc_html__( 'Thank you for installing %s!', 'creative-designer-portfolio' ),
				'<span>' . esc_html( $this->creative_designer_portfolio_theme->get( 'Name' ) ) . '</span>'
			);
			?>
		</h2>
		<?php
	}

	/**
	 * Render content.
	 */
	protected function creative_designer_portfolio_render_content() {
		$creative_designer_portfolio_link = '<a href="' . esc_url( $this->creative_designer_portfolio_url ) . '" target="_blank">' . esc_html__( 'ThemesCarts', 'creative-designer-portfolio' ) . '</a>';

		$creative_designer_portfolio_text = sprintf(
			/* translators: %1$s: Author Name, %2$s: Link */
			esc_html__( 'Unlock the full potential of your new store with %1$s! Get started today by visiting %2$s to explore a wide range of ready-to-use patterns and demo templates, designed to enhance your online shopping experience.', 'creative-designer-portfolio' ),
			esc_html__( 'ThemesCarts', 'creative-designer-portfolio' ),
			$creative_designer_portfolio_link
		);

		echo wp_kses_post( wpautop( $creative_designer_portfolio_text ) );
	}

	/**
	 * Render action buttons.
	 */
	protected function creative_designer_portfolio_render_actions() {
		// Show plugin update notice (global function, so call with \ )
		$update = \creative_designer_portfolio_plugin_update_available('siteready-coming-soon-under-construction', 'siteready-coming-soon-under-construction.php');

		if ($update) {
			echo '<div style="...">' .
				sprintf(
					/* translators: %s: New version number available for the Siteready Coming Soon Under Construction plugin */
					esc_html__( '⚠️ Siteready Coming Soon Under Construction update available (v%s).', 'creative-designer-portfolio' ),
					esc_html( $update->new_version )
				) .
				' <a href="' . esc_url(admin_url('update-core.php')) . '">' .
				esc_html__('Update now', 'creative-designer-portfolio') .
				'</a></div>';
			}
		?>
		<div class="notice-actions">
			<?php if ( creative_designer_portfolio_is_activation_completed() ) : ?>
				<a class="button-primary theme-install" id="install-activate-button" data-activated="1" href="<?php echo esc_url( admin_url( 'themes.php?page=creative-designer-portfolio-theme-info-page' ) ); ?>">
					<?php esc_html_e( 'Get Started', 'creative-designer-portfolio' ); ?>
				</a>
				<a class="button" id="visit-site-button" href="<?php echo esc_url( home_url( '/' ) ); ?>" target="_blank" rel="noopener noreferrer" style="margin-left:12px;">
					<?php esc_html_e( 'Visit Site', 'creative-designer-portfolio' ); ?>
				</a>
			<?php else : ?>
				<button type="button" class="button button-primary theme-install" id="install-activate-button">
					<?php esc_html_e( 'Start Theme Activation & Demo Import', 'creative-designer-portfolio' ); ?>
				</button>
			<?php endif; ?>

			<form class="creative-designer-portfolio-notice-dismiss-form" method="post" style="display:inline-block;margin-left:12px;">
				<button type="submit" name="notice-dismiss" value="true" id="btn-dismiss" class="button">
					<?php esc_html_e( 'Dismiss', 'creative-designer-portfolio' ); ?>
				</button>
			</form>
		</div>
		<?php
	}

	/**
	 * Handle dismiss action via POST fallback (for users without JS).
	 */
	public function handle_dismiss_notice() {
		if ( isset( $_POST['notice-dismiss'] ) ) {
			\set_transient( 'creative_designer_portfolio_notice_dismissed', true, DAY_IN_SECONDS * 3 );
			\wp_safe_redirect( esc_url_raw( $_SERVER['REQUEST_URI'] ) );
			exit;
		}
	}
}
