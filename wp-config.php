<?php
/** 
 * The base configurations of the WordPress.
 *
 **************************************************************************
 * Do not try to create this file manually. Read the README.txt and run the 
 * web installer.
 **************************************************************************
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information by
 * visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'fortyeightbottles');

/** MySQL database username */
define('DB_USER', 'tgl');

/** MySQL database password */
define('DB_PASSWORD', 'tenazurs');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');
define('VHOST', 'yes'); 
$base = '/';
define('DOMAIN_CURRENT_SITE', '48bottles.com' );
define('PATH_CURRENT_SITE', '/' );
define('SITE_ID_CURRENT_SITE', 1);
define('BLOGID_CURRENT_SITE', '1' );

/* Uncomment to allow blog admins to edit their users. See http://trac.mu.wordpress.org/ticket/1169 */
//define( "EDIT_ANY_USER", true );
/* Uncomment to enable post by email options. See http://trac.mu.wordpress.org/ticket/1084 */
//define( "POST_BY_EMAIL", true );

/**#@+
 * Authentication Unique Keys.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link http://api.wordpress.org/secret-key/1.1/wpmu/salt WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY', '0fb9bca390353ddc6b991aae98c36b585f89d050d127589be68dfabee61a026e');
define('SECURE_AUTH_KEY', '1f6de300a68222b36a2e1dec070f8a16274523e68830ff25602fe377cd1e57aa');
define('LOGGED_IN_KEY', 'c90860efbc0393de0cc32b80926a4efda4a116103098825335f3f675c3ef7366');
define('NONCE_KEY', '05f3a60a05393d38ef33181f8450765b0b4169a0bc142678c92efdc9b1fd802b');
define('AUTH_SALT', '672250567a003da1ecb3e6effbe4c953a85e8807f7a4d38b2027e53fa72bc308');
define('LOGGED_IN_SALT', 'bb5a608dedcb017f0daccc6263589bb90a6c182f22757b843428d07f9688014a');
define('SECURE_AUTH_SALT', 'fa4d128c93b3e2d34dc72eda2ad809e549e9b285d40ff96f74263390dcc7fb86');
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
 * Change this to localize WordPress.  A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de.mo to wp-content/languages and set WPLANG to 'de' to enable German
 * language support.
 */
define ('WPLANG', '');

// double check $base
if( $base == 'BASE' )
	die( 'Problem in wp-config.php - $base is set to BASE when it should be the path like "/" or "/blogs/"! Please fix it!' );

// uncomment this to enable WP_CONTENT_DIR/sunrise.php support
//define( 'SUNRISE', 'on' );

// uncomment to move wp-content/blogs.dir to another relative path
// remember to change WP_CONTENT too.
// define( "UPLOADBLOGSDIR", "fileserver" );

// If VHOST is 'yes' uncomment and set this to a URL to redirect if a blog does not exist or is a 404 on the main blog. (Useful if signup is disabled)
// For example, the browser will redirect to http://examples.com/ for the following: define( 'NOBLOGREDIRECT', 'http://example.com/' );
// Set this value to %siteurl% to redirect to the root of the site
// define( 'NOBLOGREDIRECT', '' );
// On a directory based install you must use the theme 404 handler.

// Location of mu-plugins
// define( 'WPMU_PLUGIN_DIR', '' );
// define( 'WPMU_PLUGIN_URL', '' );
// define( 'MUPLUGINDIR', 'wp-content/mu-plugins' );

define( "WP_USE_MULTIPLE_DB", false );

define( 'NONCE_SALT', 'G4%C]=n(d3#-0;J:-0@k5<|mb=t?L782Y,,&63ac8x&Kd]5 LHrC3lf]-ILDfj@K' );

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
