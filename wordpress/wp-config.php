<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'geostor-dev');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'agioroot');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'nz50-I8;Efpf /~UhRgD3v2]8Ebg cCBh;hi;BHG/KNS]a;327[FS:F&+VMzihD^');
define('SECURE_AUTH_KEY',  'P{A--/nWGTobV@ex<7][|]4b>h#PjOH^%R}B02+TrE)5uxyS+8#a0jq;5O|tghb.');
define('LOGGED_IN_KEY',    '+./!KWy#r5+#iN%fKOG.7N,2CMSmr0ngz;MN11 3b|{!0EIOw-2X=V[;}(zSLcKZ');
define('NONCE_KEY',        'MQ;a,&CHU^Z^EQP_q||ipm<RycQd+O1g7@(4,a}ve#mEwYb0M-Uc)zSpd03*lPSk');
define('AUTH_SALT',        '}%(`2fuNiyyuzB~7Oi*];@PxD/oNtW+o00z)+2Jtf%EK}~S*V{VzJsWIMAqCi0lV');
define('SECURE_AUTH_SALT', 'hAH.]guqy1zo) UMhN2Fh8-5f3NK&L%8#AN&yu4|?o;A]<hd5_J0ep(dFWp<Z+e0');
define('LOGGED_IN_SALT',   'W&[&+#%)m5{Pdk+]&z@i&- 3&J>IDN2edL,`HzCnYgIwCD0UXT6k|&!h<VCSbOx.');
define('NONCE_SALT',       'm<V<^85fWzH)yU[+SgI3+)S!Z[UFt&V7T=DUd6hl~?c!&StZbU i+T TkqgVSX77');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
