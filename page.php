<?php
/**
 * Pages (About us, Partners, Research, News & Insights, Core team…).
 * Le conteneur reçoit l'id "page-{slug}" utilisé par le CSS d'origine.
 *
 * @package naipo
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	$naipo_slug = get_post_field( 'post_name', get_the_ID() );
	?>
	<div class="site-page is-active" data-page="<?php echo esc_attr( get_the_title() ); ?>" id="page-<?php echo esc_attr( $naipo_slug ); ?>">
		<?php
		if ( ! has_blocks() ) :
			// Page sans blocs NAIPO : on affiche un hero standard + le contenu.
			?>
			<section class="subpage-hero">
				<div class="subpage-hero-inner">
					<h1 class="subpage-hero-title"><?php the_title(); ?></h1>
				</div>
			</section>
			<div class="naipo-prose"><?php the_content(); ?></div>
			<?php
		else :
			the_content();
		endif;
		?>
	</div>
	<?php
endwhile;

get_footer();
