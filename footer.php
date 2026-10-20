<?php
/**
 * Pied de page du site.
 *
 * @package naipo
 */

defined( 'ABSPATH' ) || exit;
?>
</main>
<footer class="home-footer">
	<div class="home-footer-main">
		<div class="home-footer-brand">
			<img alt="NAIPO" class="home-footer-logo" src="<?php echo esc_url( naipo_asset_uri( 'logoGreen.png' ) ); ?>"/>
			<img alt="<?php esc_attr_e( 'Flagship supported by Innosuisse', 'naipo' ); ?>" class="home-footer-support" src="<?php echo esc_url( naipo_asset_uri( 'innosuisse.png' ) ); ?>"/>
		</div>
		<nav aria-label="Footer" class="home-footer-nav">
			<div>
				<strong>About us</strong>
				<a href="<?php echo esc_url( naipo_page_url( 'about-us', 'about-glance' ) ); ?>">At a glance</a>
				<a href="<?php echo esc_url( naipo_page_url( 'about-us', 'about-partners' ) ); ?>">Partners</a>
				<a href="<?php echo esc_url( naipo_page_url( 'about-us', 'about-governance' ) ); ?>">Governance</a>
				<a href="<?php echo esc_url( naipo_page_url( 'about-us', 'about-core-team' ) ); ?>">Core Team</a>
			</div>
			<div>
				<strong>Partners</strong>
				<a href="<?php echo esc_url( naipo_page_url( 'partners', 'research-partners' ) ); ?>">Research partners</a>
				<a href="<?php echo esc_url( naipo_page_url( 'partners', 'hospital-partners' ) ); ?>">Hospital partners</a>
				<a href="<?php echo esc_url( naipo_page_url( 'partners', 'industry-partners' ) ); ?>">Industry partners</a>
			</div>
			<div>
				<strong>Research</strong>
				<a href="<?php echo esc_url( naipo_page_url( 'research', 'subprojects' ) ); ?>">Subprojects</a>
				<a href="<?php echo esc_url( naipo_page_url( 'research', 'model-suite' ) ); ?>">Model Suite</a>
			</div>
			<div>
				<strong>News &amp; Insights</strong>
				<a href="<?php echo esc_url( naipo_page_url( 'news-insights' ) ); ?>">News</a>
				<a href="<?php echo esc_url( naipo_page_url( 'news-insights' ) ); ?>">Insights</a>
			</div>
		</nav>
	</div>
	<div class="home-footer-bottom">Copyright© <?php echo esc_html( wp_date( 'Y' ) ); ?> NAIPO - All rights reserved.</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
