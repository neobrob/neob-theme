<?php
/**
 * Article News & Insights (modèle news-example.html).
 *
 * Chapeau : champ personnalisé "naipo_lead", sinon l'extrait de l'article.
 *
 * @package naipo
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	$naipo_cats = get_the_category();
	$naipo_lead = get_post_meta( get_the_ID(), 'naipo_lead', true );
	if ( ! $naipo_lead && has_excerpt() ) {
		$naipo_lead = get_the_excerpt();
	}
	?>
	<article <?php post_class( 'news-article-page' ); ?>>
		<?php if ( has_post_thumbnail() ) : ?>
			<header class="news-article-hero" aria-label="<?php echo esc_attr( get_the_title() ); ?>">
				<?php the_post_thumbnail( 'full', array( 'class' => 'news-article-hero-image', 'alt' => get_the_title() ) ); ?>
			</header>
		<?php endif; ?>

		<section class="news-article-intro">
			<div class="news-article-shell">
				<div class="news-article-meta reveal">
					<?php if ( $naipo_cats ) : ?>
						<span class="u-100"><?php echo esc_html( $naipo_cats[0]->name ); ?></span>
					<?php endif; ?>
					<span class="u-101"><?php echo esc_html( get_the_date( 'F Y' ) ); ?></span>
				</div>
				<h1 class="news-article-title reveal"><?php the_title(); ?></h1>
				<?php if ( $naipo_lead ) : ?>
					<p class="news-article-lead reveal"><?php echo esc_html( $naipo_lead ); ?></p>
				<?php endif; ?>
				<div class="news-article-divider" aria-hidden="true"></div>
			</div>
		</section>

		<section class="news-article-body">
			<div class="news-article-shell news-article-content">
				<?php the_content(); ?>
				<a class="news-article-back" href="<?php echo esc_url( naipo_page_url( 'news-insights' ) ); ?>">← <?php esc_html_e( 'Back to News & Insights', 'naipo' ); ?></a>
			</div>
		</section>
	</article>
	<?php
endwhile;

get_footer();
