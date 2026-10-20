<?php
/**
 * En-tête du site.
 *
 * @package naipo
 */

defined( 'ABSPATH' ) || exit;
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="screen-reader-text skip-link" href="#main-content"><?php esc_html_e( 'Skip to content', 'naipo' ); ?></a>
<header class="site-header theme-dark">
	<a class="site-logo-link" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php esc_attr_e( 'NAIPO home', 'naipo' ); ?>">
		<?php
		$naipo_logo = has_custom_logo() ? wp_get_attachment_image_url( get_theme_mod( 'custom_logo' ), 'full' ) : naipo_asset_uri( 'logoGreen.png' );
		?>
		<img alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" class="site-logo" src="<?php echo esc_url( $naipo_logo ); ?>"/>
	</a>

	<nav class="site-nav" id="site-navigation" aria-label="<?php esc_attr_e( 'Main navigation', 'naipo' ); ?>">
		<?php foreach ( naipo_nav_links() as $naipo_link ) : ?>
			<a class="site-nav-link<?php echo $naipo_link['active'] ? ' is-active' : ''; ?>" href="<?php echo esc_url( $naipo_link['url'] ); ?>"<?php echo $naipo_link['target'] ? ' target="' . esc_attr( $naipo_link['target'] ) . '" rel="noopener"' : ''; ?><?php echo $naipo_link['active'] ? ' aria-current="page"' : ''; ?>><?php echo esc_html( $naipo_link['label'] ); ?></a>
		<?php endforeach; ?>
	</nav>

	<div class="site-header-actions">
		<div class="language-selector">
			<button aria-expanded="false" class="language-toggle" id="language-toggle" type="button">
				EN <span class="language-chevron" aria-hidden="true">▼</span>
			</button>
			<div class="language-menu" hidden id="language-menu">
				<button data-lang="EN" type="button">EN</button>
				<button data-lang="DE" type="button">DE</button>
				<button data-lang="FR" type="button">FR</button>
				<button data-lang="IT" type="button">IT</button>
			</div>
		</div>

		<button class="site-menu-toggle" id="site-menu-toggle" type="button" aria-expanded="false" aria-controls="site-navigation" aria-label="<?php esc_attr_e( 'Open navigation menu', 'naipo' ); ?>">
			<span></span><span></span><span></span>
		</button>
	</div>
</header>
<main id="main-content">
