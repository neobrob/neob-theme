<?php
/**
 * Page introuvable.
 *
 * @package naipo
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<div class="site-page is-active" id="page-404">
	<section class="subpage-hero">
		<div class="subpage-hero-inner">
			<span class="subpage-hero-label">404</span>
			<h1 class="subpage-hero-title"><?php esc_html_e( 'Page not found', 'naipo' ); ?></h1>
			<p class="subpage-hero-description">
				<a class="naipo-404-link" href="<?php echo esc_url( home_url( '/' ) ); ?>">← <?php esc_html_e( 'Back to the homepage', 'naipo' ); ?></a>
			</p>
		</div>
	</section>
</div>
<?php
get_footer();
