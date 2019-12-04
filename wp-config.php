<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'stock_management' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         's2`RRT>{Lw_6,uPSd2Vg9$5-O4tN5^;.=O^#k$SSg[4*a4U<8tOvQ%/7Xt-v:Qx6' );
define( 'SECURE_AUTH_KEY',  'GM~u|:/-ch+kEqy5= ?I[9SmN-zuHd>WBk/QttESYD^?Y45!oQU;|wo-t~j0qn)J' );
define( 'LOGGED_IN_KEY',    '?l)dvKZ~^sQM/NDdgxeJ6E`!x>fs$Ws~YpX5*}16B!oJb&sxjj-[2n*VGHwUD~ZY' );
define( 'NONCE_KEY',        '2.5?$WL%+n;Sw.njN?U%hV%`7cKsjdA,0:!J..&jnk5$3P<l.p]OfwU0ep+6XzL.' );
define( 'AUTH_SALT',        'IOF<!RX5:u&#fKL^Uwxg8TFSv^Ge6K-yj6e8b<}CY/UxoU9&G/w<jCy`#/7vw,FR' );
define( 'SECURE_AUTH_SALT', 'YW`DJ]V,,X%oDZ]LM`@&QB|h,&8clm{9YFO-E<HEa)v`%HVF5m@2F32HkIAb}H}r' );
define( 'LOGGED_IN_SALT',   't[V5uJO_h6nU6$;D)x?$]*?1w$|m|u(hwE)>?{8Neh9 lw,<}.+<<i-A*_Ck[C0B' );
define( 'NONCE_SALT',       'rj/}=m!~Cfy*jL_lE/5(DJBfZ56RZ@T{M3W#^CfOu>iSP&g<r5kq/ao_3%U{D`*J' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
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
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
