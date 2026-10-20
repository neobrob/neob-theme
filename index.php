<?php
/**
 * Modèle par défaut : archives (catégories, dates), recherche, liste d'articles.
 *
 * @package naipo
 */

defined( 'ABSPATH' ) || exit;

get_header();

if ( is_search() ) {
	/* translators: %s: terme recherché */
	$naipo_title = sprintf( __( 'Search: %s', 'naipo' ), get_search_query() );
} elseif ( is_archive() ) {
	$naipo_title = wp_strip_all_tags( get_the_archive_title() );
} else {
	$naipo_title = __( 'News & Insights', 'naipo' );
}
?>
<div class="site-page is-active" id="page-news-insights">
	<section class="subpage-hero">
		<div class="subpage-hero-inner">
			<span class="subpage-hero-label"><?php esc_html_e( 'News & Insights', 'naipo' ); ?></span>
			<h1 class="subpage-hero-title"><?php echo esc_html( $naipo_title ); ?></h1>
		</div>
	</section>
	<section class="news-listing u-059">
		<?php if ( have_posts() ) : ?>
			<div class="u-060 news-grid">
				<?php
				$naipo_i = 0;
				while ( have_posts() ) :
					the_post();
					echo naipo_news_card( get_post(), $naipo_i++ ); // phpcs:ignore WordPress.Security.EscapeOutput
				endwhile;
				?>
			</div>
			<?php
			the_posts_pagination(
				array(
					'class'     => 'naipo-pagination',
					'prev_text' => '←',
					'next_text' => '→',
				)
			);
			?>
		<?php else : ?>
			<p><?php esc_html_e( 'Nothing found.', 'naipo' ); ?></p>
		<?php endif; ?>
	</section>
</div>
<?php
get_footer();
