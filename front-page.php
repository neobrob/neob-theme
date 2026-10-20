<?php
/**
 * Page d'accueil.
 *
 * @package naipo
 */

defined( 'ABSPATH' ) || exit;

if ( 'page' !== get_option( 'show_on_front' ) ) {
	require get_index_template();
	return;
}

get_header();
?>
<div class="site-page is-active" data-page="Home" id="page-home">
	<div class="home-v12">
		<?php
		while ( have_posts() ) :
			the_post();
			the_content();
		endwhile;
		?>
	</div>
</div>
<?php
get_footer();
