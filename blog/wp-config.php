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
define('DB_NAME', 'edkblogdb');

/** MySQL database username */
define('DB_USER', 'edkblogusr');

/** MySQL database password */
define('DB_PASSWORD', 'J!kTGhj5sY');

/** MySQL hostname */
define('DB_HOST', 'edukartblogdb.cshxspwfrnd7.ap-southeast-1.rds.amazonaws.com');

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
define('AUTH_KEY',         'kTa|1wdDtDG1[iAL$DY0]m%NvIm:l<WqHF9EN@h,HO(%}qf<RY^ENgd`TBjhV|q~');
define('SECURE_AUTH_KEY',  '9ue|[_5,30FeGOt7ASKBE7lLoJ)pi*~(=Ag1^m)n26tw1aXp1iQ_Bn|C>K3!z+Ic');
define('LOGGED_IN_KEY',    'J p3q*|w-*:Q)/Q81vf.|8clbCg22/Ch)g3KNw4O<P9KAic|e3|t)5b`8d]+@xtD');
define('NONCE_KEY',        '4a)P?h2#T<|(C..uFq(Si-nMUR]3:|v8,X3-EGhljyN/twk8}t]s5>H&{QHsmd$|');
define('AUTH_SALT',        'rh- qqu~5xE>Hjl#D`V,U&u5@{.zX8b}|5X{[dCKs!V+wzgjqS-JO7w1bBwBoFs~');
define('SECURE_AUTH_SALT', 'B,?-|3>iR!?L{eJbt}DdpR|aO-1rnU6l7M+%;ozApA3b91Eo^aiC+(F1?>x_`k7R');
define('LOGGED_IN_SALT',   'Ie:D%W;W-~o8a1SmG&Nj]Iec3bvsV^-SBU^G+6ewzRZ/$Fwq(+E|TyzQII|jlbGh');
define('NONCE_SALT',       'Bbguc>sHxN|~e: 26~U&q`0[HtI|?p7^q:&j;)`)bP|bYGyyLjsc042USDFWO[!+');

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
