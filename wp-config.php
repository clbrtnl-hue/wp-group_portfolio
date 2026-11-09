<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'websitedb' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         ']K`D/{aq!?e/;Orz,]znx`D[MC2I%j(g$^;?/)%~42ba7lV&4~rg^0>2B4WIoaww' );
define( 'SECURE_AUTH_KEY',  'xrZjrw}B9iTEGt/$.8Wgpt?K,Gn7ZXP6Q`wRT>TtH)IATq[![L>n7:f981hp[6e=' );
define( 'LOGGED_IN_KEY',    '05S]v#7[/[dL6{7i4c$#Qm+J;<>ZcRo6[OKEFvDk.e hmCaK>6yc;nAB?xa|O#SX' );
define( 'NONCE_KEY',        'eNQCexWlj*TMr`h ]@i&*vJ=2nl|3T]w~J^#vNH(bos#98l(yV{?Mc*J{Q8!1WW@' );
define( 'AUTH_SALT',        'P$v;l%K[g`GO!Q<7b))eLj;j/q1OD3#@|n1f~kNy( :n0Dc!ebV(%$4t2mCN8vGL' );
define( 'SECURE_AUTH_SALT', 'AtROknS-V{/Pr;C.z*zr[2#$z4=5J}I#Qfz~w;|${g+a7`%8P~`Ax36$bF2=[I*L' );
define( 'LOGGED_IN_SALT',   '+zmh:jkly4ShqD-9|Tx^C}+PPZo}pX6P9+~j~2PQquX>N|G&W8}bnCo]?:Iw4ttl' );
define( 'NONCE_SALT',       '-zZ3K8cm))N>e5|H/H4`en#a1tG$Afv*IdZU&WK,M&uonf[r70-gx}-lA}]SwtEe' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
