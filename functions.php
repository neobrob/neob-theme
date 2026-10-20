<?php
/**
 * NAIPO — fonctions du thème.
 *
 * @package naipo
 */

defined( 'ABSPATH' ) || exit;

define( 'NAIPO_VERSION', '1.0.0' );
define( 'NAIPO_DIR', get_template_directory() );
define( 'NAIPO_URI', get_template_directory_uri() );

require NAIPO_DIR . '/inc/helpers.php';
require NAIPO_DIR . '/inc/setup.php';
require NAIPO_DIR . '/inc/blocks.php';
require NAIPO_DIR . '/inc/patterns.php';
require NAIPO_DIR . '/inc/install.php';
