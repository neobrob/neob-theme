<?php
/**
 * Configuration du thème, scripts et styles.
 *
 * @package naipo
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'after_setup_theme',
	function () {
		load_theme_textdomain( 'naipo', NAIPO_DIR . '/languages' );

		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );
		add_theme_support( 'editor-styles' );
		add_theme_support( 'custom-logo', array( 'flex-width' => true, 'flex-height' => true ) );

		register_nav_menus(
			array(
				'primary' => __( 'Menu principal', 'naipo' ),
			)
		);
	}
);

/**
 * Styles et scripts du site.
 */
add_action(
	'wp_enqueue_scripts',
	function () {
		wp_enqueue_style( 'naipo', get_stylesheet_uri(), array(), NAIPO_VERSION );
		wp_enqueue_style( 'naipo-wp', NAIPO_URI . '/css/wp.css', array( 'naipo' ), NAIPO_VERSION );
		wp_enqueue_script( 'naipo', NAIPO_URI . '/js/naipo.js', array(), NAIPO_VERSION, array( 'in_footer' => true ) );
	}
);

/**
 * Le bloc "Classic theme styles" ajoute des marges aux boutons ; inutile ici.
 */
add_action(
	'wp_enqueue_scripts',
	function () {
		wp_dequeue_style( 'classic-theme-styles' );
	},
	20
);

/**
 * Classe "is-home" sur la page d'accueil (utilisée par le CSS d'origine).
 */
add_filter(
	'body_class',
	function ( $classes ) {
		if ( is_front_page() ) {
			$classes[] = 'is-home';
		}
		return $classes;
	}
);

/**
 * Styles de l'éditeur : le CSS du site, sans les préfixes #page-xxx
 * (dans l'éditeur, le contenu n'est pas entouré par ces conteneurs),
 * et avec les animations désactivées pour que tout soit visible.
 */
add_filter(
	'block_editor_settings_all',
	function ( $settings ) {
		$css = file_get_contents( NAIPO_DIR . '/style.css' ); // phpcs:ignore WordPress.WP.AlternativeFunctions
		$css = preg_replace( '/#page-[a-z0-9-]+\s+/i', '', $css );
		$css = preg_replace( '/#page-[a-z0-9-]+(?=[\s,{.:])/i', '', $css );
		$css .= "\n" . file_get_contents( NAIPO_DIR . '/css/wp.css' ); // phpcs:ignore WordPress.WP.AlternativeFunctions
		$css .= "\n" . file_get_contents( NAIPO_DIR . '/css/editor.css' ); // phpcs:ignore WordPress.WP.AlternativeFunctions

		$settings['styles'][] = array(
			'css'     => $css,
			'baseURL' => NAIPO_URI . '/',
		);
		return $settings;
	}
);

/**
 * Extraits : pas de "[…]".
 */
add_filter(
	'excerpt_more',
	function () {
		return '…';
	}
);

/**
 * Lien de navigation actif : renvoie true si l'élément de menu correspond
 * à la page courante ou à l'un de ses parents (et "News" pour les articles).
 */
function naipo_is_current_item( $item ) {
	$classes = (array) $item->classes;
	if ( array_intersect( $classes, array( 'current-menu-item', 'current-menu-ancestor', 'current-menu-parent', 'current_page_parent', 'current_page_ancestor' ) ) ) {
		return true;
	}
	if ( 'page' === $item->object && is_page() && in_array( (int) $item->object_id, array_map( 'intval', get_post_ancestors( get_queried_object_id() ) ), true ) ) {
		return true;
	}
	if ( ( is_singular( 'post' )|| is_category() || is_tag() || is_date() ) && 'page' === $item->object ) {
		$news = get_page_by_path( 'news-insights' );
		return $news && (int) $item->object_id === (int) $news->ID;
	}
	return false;
}

/**
 * Liens de la navigation principale (menu WordPress, ou pages par défaut).
 */
function naipo_nav_links() {
	$locations = get_nav_menu_locations();
	$links     = array();

	if ( ! empty( $locations['primary'] ) ) {
		$items = wp_get_nav_menu_items( $locations['primary'] );
		if ( $items ) {
			_wp_menu_item_classes_by_context( $items );
			foreach ( $items as $item ) {
				if ( (int) $item->menu_item_parent ) {
					continue;
				}
				$links[] = array(
					'label'  => $item->title,
					'url'    => $item->url,
					'active' => naipo_is_current_item( $item ),
					'target' => $item->target,
				);
			}
			return $links;
		}
	}

	$defaults = array(
		'about-us'      => 'About us',
		'partners'      => 'Partners',
		'research'      => 'Research',
		'news-insights' => 'News & Insights',
	);
	foreach ( $defaults as $path => $label ) {
		$page    = get_page_by_path( $path );
		$active  = $page && ( is_page( $page->ID ) || in_array( $page->ID, get_post_ancestors( get_queried_object_id() ), true ) );
		if ( 'news-insights' === $path && ( is_singular( 'post' ) || is_category() ) ) {
			$active = true;
		}
		$links[] = array(
			'label'  => $label,
			'url'    => naipo_page_url( $path ),
			'active' => $active,
			'target' => '',
		);
	}
	return $links;
}
