<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'local' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

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


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



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
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}


define('AUTH_KEY',         'qNpBGUVmmEECk14HWJjmOnR25l1MKPOHHc+eXScDgWAw8uB/LGpebrIjWZ0oRs5pcPj4JtbBbT2+6uZsPyPzZA==');
define('SECURE_AUTH_KEY',  'wyUCpwAt+kE2i2Yb/PLVUOflJIzKAg3jPCTiflIh4UwuxsgwcWtHIgWntEeYvC0nWNjVYIzvng1QgtiMQoxSaA==');
define('LOGGED_IN_KEY',    'EBvFFHFhgTVUdDibCfZ9I+qASlAve8WFTtB0RyIkXyN1orNR4PBEbxvu6N6u0fy+P/lggefMUhBXJOMQKvbLYA==');
define('NONCE_KEY',        'U3xhxN3d/m4IhC0UqezCTlCEBnR7gDXldwN39wUhV+zpI9BJ9ZZSiR2YIMudWzBSGEd0i4U4MlNKsEZpnkD4hw==');
define('AUTH_SALT',        'h3/T6Thotdah8af0v6iCSNE3IpXwleVCBAc1xOwDz/K88/K1jgieOfd/uHdWLHZnZmwOUY/Wcwjm18WK8JlNhw==');
define('SECURE_AUTH_SALT', 'dy2bSNFc0FullYK8tiShP1EeSSBd4JaFT6x7BscgFY+T6iy1XD9alJWQEJnlNW/13N/Iqs/RiLdDGaBgsaipeQ==');
define('LOGGED_IN_SALT',   'HsSdWjrycyhuQTue5uZromOKUCFuUVZkpvQXBWzmkwJb9uZm/G1tynczmNLSy0BCqfPQ83lSgKUwiaKlDulp8Q==');
define('NONCE_SALT',       'FBGRFk8DmxpObVryZGY8xOJdVkGtlvP7E6V1cPNbAFPMpRAtvCmve/0YUmEFoxmOOu5UthBCPENU0wuFQlEcag==');
define( 'WP_ENVIRONMENT_TYPE', 'local' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
