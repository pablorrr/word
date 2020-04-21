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
//allows WooCommerce csv import
define('ALLOW_UNFILTERED_UPLOADS', true);
// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'word' );

/** MySQL database username */
define( 'DB_USER', 'word' );

/** MySQL database password */
define( 'DB_PASSWORD', 'word' );

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
define( 'AUTH_KEY',         'AA.&Qp7LmOf4NXK5s4tH@6Q).+n)DuiSlFFQb:/~,>heH!yt-yAa. ?S^,Cq-Rm.' );
define( 'SECURE_AUTH_KEY',  '^W$$sx]91@*j#3Wh:LKO|l5E?1o#1Dl;TMBBE.Y_:Q;,wMB2&3Fr5C0lki?>iB#x' );
define( 'LOGGED_IN_KEY',    '8ZssSXJcUP`_Il,~aB^[VMeulj]w2M#{#|,D _V8AMhwxhm<gyWbuxF|_+FY}|S@' );
define( 'NONCE_KEY',        'OFs3~+[GMug-?@`%}9#.ns>*91bO.9)odku|n#QwcA3gv,R4{vzH)kiA$.FtWE{T' );
define( 'AUTH_SALT',        'vUk!8/$>8A-hc|xe8rK{#*#fE:*KQk*;Fd 7orZ#zEn}5JVgG-X/kKpbYtXS/<7!' );
define( 'SECURE_AUTH_SALT', '|n%*ec~:61Qk]4n+O=)24J,4&VI$T86?zwgUgx([g8iMrI3^M+rz7oTwgiX|E=5n' );
define( 'LOGGED_IN_SALT',   'Mm?c5&8x|-4A^nESjyZ}3%~6w.nF%[urgf}H6921H8|+.4akRRvPXQB}1)5jp~)y' );
define( 'NONCE_SALT',       '&~_y*DfTyhgSiZ(S> !24$;82@1;mW&$a$2q0P):lLI7u?_]2`.Zuvw>,z=s)V7_' );

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
define('MULTISITE', true);
define('SUBDOMAIN_INSTALL', false);
define('DOMAIN_CURRENT_SITE', 'localhost');
define('PATH_CURRENT_SITE', '/word/');
define('SITE_ID_CURRENT_SITE', 1);
define('BLOG_ID_CURRENT_SITE', 1);

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
