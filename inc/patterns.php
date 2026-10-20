<?php
/**
 * Sections NAIPO enregistrées comme compositions (patterns) réutilisables.
 * Chaque section vit dans /sections/<slug>.html.
 *
 * @package naipo
 */

defined( 'ABSPATH' ) || exit;

/**
 * Sections disponibles : slug => titre affiché dans l'inserteur.
 */
function naipo_sections() {
	return array(
		'home-hero'            => 'Accueil — Hero',
		'home-impact'          => 'Accueil — Impact',
		'home-partners'        => 'Accueil — Bandeau de logos',
		'home-latest-news'     => 'Accueil — Dernières news',
		'about-hero'           => 'About us — Hero',
		'about-glance'         => 'About us — At a glance',
		'about-partners'       => 'About us — Partners',
		'about-governance'     => 'About us — Governance',
		'about-team'           => 'About us — Core team',
		'core-team-hero'       => 'Core team — Hero',
		'core-team-directory'  => 'Core team — Annuaire',
		'partners-hero'        => 'Partners — Hero',
		'partners-groups'      => 'Partners — Groupes de partenaires',
		'research-hero'        => 'Research — Hero',
		'research-model-suite' => 'Research — Model suite',
		'research-subprojects' => 'Research — Sous-projets',
		'news-hero'            => 'News — Hero',
		'news-list'            => 'News — Liste des articles',
		'contact-cta'          => 'Contact — Appel à l\'action',
	);
}

/**
 * Pages du site : chemin => [titre, parent, sections].
 */
function naipo_pages() {
	return array(
		'home'          => array( 'Home', '', array( 'home-hero', 'home-impact', 'home-partners', 'home-latest-news' ) ),
		'about-us'      => array( 'About us', '', array( 'about-hero', 'about-glance', 'about-partners', 'about-governance', 'about-team' ) ),
		'core-team'     => array( 'Core Team', 'about-us', array( 'core-team-hero', 'core-team-directory' ) ),
		'partners'      => array( 'Partners', '', array( 'partners-hero', 'partners-groups', 'contact-cta' ) ),
		'research'      => array( 'Research', '', array( 'research-hero', 'research-model-suite', 'research-subprojects' ) ),
		'news-insights' => array( 'News & Insights', '', array( 'news-hero', 'news-list', 'contact-cta' ) ),
	);
}

add_action(
	'init',
	function () {
		register_block_pattern_category( 'naipo', array( 'label' => 'NAIPO' ) );

		$assets = get_option( 'naipo_assets', array() );
		foreach ( naipo_sections() as $slug => $title ) {
			register_block_pattern(
				'naipo/' . $slug,
				array(
					'title'      => $title,
					'categories' => array( 'naipo' ),
					'content'    => naipo_section_markup( $slug, is_array( $assets ) ? $assets : array() ),
				)
			);
		}
	}
);
