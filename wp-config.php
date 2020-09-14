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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'bitumish_wp393' );

/** MySQL database username */
define( 'DB_USER', 'bitumish_wp393' );

/** MySQL database password */
define( 'DB_PASSWORD', 'SG!79(z8p6' );

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
define( 'AUTH_KEY',         'h.S//E[~hHJ%rUlqi-=rhSgj=nAZn#U/^L1tiaI(v0l5Iq4OQZR.upztms%Nu]-d' );
define( 'SECURE_AUTH_KEY',  '+Ep3Zn/H;^h&@8+G6mEk8!|/R:]LAN&TdG5m3#IeMg-/}o*XSa7F=Eo7Q?mI3{j;' );
define( 'LOGGED_IN_KEY',    '|dS[Xe.GaC>;zq-?IGn[;+Vz3Kd4m3835Z~!Z@KB&IkSRns.HYu@=i<2|_;=u*6G' );
define( 'NONCE_KEY',        'a=$w:_VM/y#Y=oPzD#V<e}bW>7D[w~G=*fiDmN~6i]&=Lm0I[iF&ZY<$}v7Fo.<2' );
define( 'AUTH_SALT',        'z-<)i8X9<f Bx.hDa}~ZVp<HRwJeQC8f$4x/N&m;7:i}>jb+Zl5+I]ul8~.z{G)#' );
define( 'SECURE_AUTH_SALT', 'mAb(py6Ca==[Nw2<(tUex3R<9[ }hnRQnYEP:gH.+VU]Ytd:5H|.C5#*frbL8v7j' );
define( 'LOGGED_IN_SALT',   'q4v-1H%R`t<U1.kuPS?5m-5j:NR4kHL`S$AK#sQGN]gcjE<_!+.ps2#L&jF4V{*c' );
define( 'NONCE_SALT',       'qY#^;mVvAt)NW$7Ai2CM+O|@Q;zh;<P~W0u6{(Y I#<mv0NTZ4)]~]RLaE_whu_D' );

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
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
