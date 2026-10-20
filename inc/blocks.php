<?php
/**
 * Blocs NAIPO : chaque composant de la maquette devient un bloc dynamique
 * (rendu en PHP avec le HTML d'origine) éditable depuis la barre latérale.
 *
 * Types de champs (clé "control") : text, textarea, url, number, select, image, gallery.
 *
 * @package naipo
 */

defined( 'ABSPATH' ) || exit;

/**
 * Définition des blocs.
 */
function naipo_block_definitions() {
	$text = function ( $label, $default = '', $control = 'text' ) {
		return array(
			'type'    => 'string',
			'default' => $default,
			'label'   => $label,
			'control' => $control,
		);
	};
	$image = function ( $label ) {
		return array(
			'type'    => 'object',
			'default' => array(),
			'label'   => $label,
			'control' => 'image',
		);
	};

	return array(

		'home-hero'        => array(
			'title'      => 'Accueil — Hero',
			'icon'       => 'cover-image',
			'attributes' => array(
				'kicker'         => $text( 'Surtitre', 'National AI Initiative for Precision Oncology' ),
				'line1'          => $text( 'Titre — ligne 1', 'Transforming' ),
				'line2'          => $text( 'Titre — ligne 2 (accent)', 'cancer care' ),
				'line3'          => $text( 'Titre — ligne 3', 'with AI' ),
				'description'    => $text( 'Texte', '', 'textarea' ),
				'primaryLabel'   => $text( 'Bouton 1 — texte', 'About the initiative' ),
				'primaryUrl'     => $text( 'Bouton 1 — lien', '', 'url' ),
				'secondaryLabel' => $text( 'Bouton 2 — texte', 'Research' ),
				'secondaryUrl'   => $text( 'Bouton 2 — lien', '', 'url' ),
			),
		),

		'home-impact'      => array(
			'title'      => 'Accueil — Impact',
			'icon'       => 'align-pull-left',
			'attributes' => array(
				'kicker'      => $text( 'Surtitre', 'Partners' ),
				'title'       => $text( 'Titre (un retour à la ligne = une ligne)', '', 'textarea' ),
				'text'        => $text( 'Texte', '', 'textarea' ),
				'buttonLabel' => $text( 'Bouton — texte', 'Our partners' ),
				'buttonUrl'   => $text( 'Bouton — lien', '', 'url' ),
			),
		),

		'partner-marquee'  => array(
			'title'      => 'Accueil — Bandeau de logos',
			'icon'       => 'images-alt2',
			'attributes' => array(
				'logos' => array(
					'type'    => 'array',
					'default' => array(),
					'label'   => 'Logos',
					'control' => 'gallery',
				),
			),
		),

		'latest-news'      => array(
			'title'      => 'Accueil — Dernières news',
			'icon'       => 'megaphone',
			'attributes' => array(
				'kicker' => $text( 'Surtitre', 'News & Insights' ),
				'title'  => $text( 'Titre', 'Latest news' ),
				'count'  => array(
					'type'    => 'number',
					'default' => 3,
					'label'   => 'Nombre de news',
					'control' => 'number',
				),
			),
		),

		'news-list'        => array(
			'title'      => 'News — Liste des articles',
			'icon'       => 'grid-view',
			'attributes' => array(
				'perPage' => array(
					'type'    => 'number',
					'default' => 9,
					'label'   => 'News par page',
					'control' => 'number',
				),
			),
		),

		'page-hero'        => array(
			'title'      => 'Page — Hero',
			'icon'       => 'heading',
			'attributes' => array(
				'variant'     => array(
					'type'    => 'string',
					'default' => 'default',
					'label'   => 'Style',
					'control' => 'select',
					'options' => array(
						'default'   => 'Standard',
						'about'     => 'About us (titre en capitales sur plusieurs lignes)',
						'core-team' => 'Core team',
					),
				),
				'label'       => $text( 'Surtitre' ),
				'title'       => $text( 'Titre (un retour à la ligne = une ligne)', '', 'textarea' ),
				'description' => $text( 'Texte', '', 'textarea' ),
			),
		),

		'section'          => array(
			'title'      => 'Section',
			'icon'       => 'editor-table',
			'inner'      => true,
			'attributes' => array(
				'tag'       => array(
					'type'    => 'string',
					'default' => 'section',
					'label'   => 'Balise',
					'control' => 'select',
					'options' => array(
						'section' => 'section',
						'div'     => 'div',
					),
				),
				'cssClass'  => $text( 'Classe CSS de la section' ),
				'sectionId' => $text( 'Ancre (id)' ),
				'shell'     => $text( 'Classe du conteneur intérieur (vide = aucun)' ),
			),
		),

		'heading'          => array(
			'title'      => 'Titre de section',
			'icon'       => 'editor-textcolor',
			'attributes' => array(
				'variant' => array(
					'type'    => 'string',
					'default' => 'about',
					'label'   => 'Style',
					'control' => 'select',
					'options' => array(
						'about'     => 'About us',
						'research'  => 'Research',
						'core-team' => 'Core team',
						'partner'   => 'Partners',
					),
				),
				'kicker'  => $text( 'Surtitre (optionnel)' ),
				'title'   => $text( 'Titre' ),
			),
		),

		'figure'           => array(
			'title'      => 'Image avec légende',
			'icon'       => 'format-image',
			'attributes' => array(
				'variant' => array(
					'type'    => 'string',
					'default' => 'about-project',
					'label'   => 'Style',
					'control' => 'select',
					'options' => array(
						'about-project'  => 'About us — schéma',
						'about-partners' => 'About us — carte des partenaires',
						'research-model' => 'Research — model suite',
					),
				),
				'image'   => $image( 'Image' ),
				'caption' => $text( 'Légende' ),
			),
		),

		'cta-link'         => array(
			'title'      => 'Bouton contour',
			'icon'       => 'button',
			'attributes' => array(
				'label' => $text( 'Texte', 'OUR PARTNERS' ),
				'url'   => $text( 'Lien', '', 'url' ),
			),
		),

		'governance-card'  => array(
			'title'      => 'Carte gouvernance',
			'icon'       => 'groups',
			'attributes' => array(
				'title' => $text( 'Titre' ),
				'text'  => $text( 'Texte', '', 'textarea' ),
			),
		),

		'team-feature'     => array(
			'title'      => 'Carte « Core Team »',
			'icon'       => 'id-alt',
			'attributes' => array(
				'photo'     => $image( 'Photo de groupe' ),
				'title'     => $text( 'Titre', 'The Core Team' ),
				'linkLabel' => $text( 'Texte du lien', 'View the full team →' ),
				'url'       => $text( 'Lien', '', 'url' ),
			),
		),

		'team-member'      => array(
			'title'      => 'Membre de l\'équipe',
			'icon'       => 'admin-users',
			'attributes' => array(
				'photo'       => $image( 'Portrait' ),
				'name'        => $text( 'Nom', 'Name' ),
				'role'        => $text( 'Fonction', 'Role' ),
				'institution' => $text( 'Institution', 'Institution' ),
			),
		),

		'partner-card'     => array(
			'title'      => 'Carte partenaire',
			'icon'       => 'building',
			'attributes' => array(
				'logo'     => $image( 'Logo' ),
				'name'     => $text( 'Nom' ),
				'type'     => $text( 'Type (University, Startup…)' ),
				'url'      => $text( 'Site web', '', 'url' ),
				'anchorId' => $text( 'Ancre (id)' ),
			),
		),

		'research-project' => array(
			'title'      => 'Sous-projet',
			'icon'       => 'clipboard',
			'attributes' => array(
				'number'      => $text( 'Numéro', '01' ),
				'title'       => $text( 'Titre' ),
				'description' => $text( 'Description', '', 'textarea' ),
			),
		),

		'contact-cta'      => array(
			'title'      => 'Contact — appel à l\'action',
			'icon'       => 'email',
			'attributes' => array(
				'kicker'  => $text( 'Surtitre', 'Get in touch' ),
				'title'   => $text( 'Titre', 'Contact us to collaborate' ),
				'text'    => $text( 'Texte', 'Interested in collaborating with NAIPO? Get in touch with our team.', 'textarea' ),
				'email'   => $text( 'E-mail', 'info@naipo.ai' ),
				'subject' => $text( 'Objet de l\'e-mail', 'Partnership inquiry — NAIPO' ),
			),
		),
	);
}

/**
 * Enregistrement des blocs et du script d'édition.
 */
add_action(
	'init',
	function () {
		wp_register_script(
			'naipo-blocks',
			NAIPO_URI . '/js/blocks-editor.js',
			array( 'wp-blocks', 'wp-block-editor', 'wp-components', 'wp-element', 'wp-server-side-render', 'wp-i18n' ),
			NAIPO_VERSION,
			true
		);

		$defs = naipo_block_definitions();
		$js   = array();

		foreach ( $defs as $slug => $def ) {
			$attributes = array();
			foreach ( $def['attributes'] as $key => $attr ) {
				$attributes[ $key ] = array(
					'type'    => $attr['type'],
					'default' => $attr['default'],
				);
			}
			$js[ 'naipo/' . $slug ] = array(
				'title'      => $def['title'],
				'icon'       => $def['icon'],
				'inner'      => ! empty( $def['inner'] ),
				'attributes' => $def['attributes'],
			);

			register_block_type(
				'naipo/' . $slug,
				array(
					'api_version'     => 3,
					'title'           => $def['title'],
					'category'        => 'naipo',
					'attributes'      => $attributes,
					'editor_script'   => 'naipo-blocks',
					'supports'        => array(
						'html'            => false,
						'customClassName' => false,
					),
					'render_callback' => function ( $attrs, $content = '' ) use ( $slug ) {
						$fn = 'naipo_render_' . str_replace( '-', '_', $slug );
						return function_exists( $fn ) ? $fn( $attrs, $content ) : '';
					},
				)
			);
		}

		wp_add_inline_script( 'naipo-blocks', 'window.naipoBlocks = ' . wp_json_encode( $js ) . ';', 'before' );
	}
);

/**
 * Catégorie "NAIPO" dans l'inserteur de blocs.
 */
add_filter(
	'block_categories_all',
	function ( $categories ) {
		array_unshift(
			$categories,
			array(
				'slug'  => 'naipo',
				'title' => 'NAIPO',
				'icon'  => null,
			)
		);
		return $categories;
	}
);

/* -------------------------------------------------------------------------
 * Rendus (HTML identique à la maquette NAIPO v16.4)
 * ---------------------------------------------------------------------- */

function naipo_render_home_hero( $a ) {
	ob_start();
	?>
	<section aria-labelledby="home-title" class="home-hero">
		<div aria-hidden="true" class="home-hero-bg"></div>
		<div class="hero-stagger home-hero-content">
			<span class="home-hero-kicker"><?php echo esc_html( $a['kicker'] ); ?></span>
			<h1 class="home-hero-title hero-title" id="home-title">
				<span class="line"><span><?php echo esc_html( $a['line1'] ); ?></span></span>
				<span class="line"><span class="home-hero-accent-dark"><?php echo esc_html( $a['line2'] ); ?></span></span>
				<span class="line"><span><?php echo esc_html( $a['line3'] ); ?> <span class="home-hero-dot">.</span></span></span>
			</h1>
			<div class="home-hero-bottom">
				<p class="home-hero-description"><?php echo esc_html( $a['description'] ); ?></p>
				<div class="home-hero-actions">
					<?php if ( $a['primaryLabel'] ) : ?>
						<a class="home-hero-primary" href="<?php echo esc_url( $a['primaryUrl'] ? $a['primaryUrl'] : naipo_page_url( 'about-us' ) ); ?>"><?php echo esc_html( $a['primaryLabel'] ); ?></a>
					<?php endif; ?>
					<?php if ( $a['secondaryLabel'] ) : ?>
						<a class="home-hero-secondary" href="<?php echo esc_url( $a['secondaryUrl'] ? $a['secondaryUrl'] : naipo_page_url( 'research' ) ); ?>"><?php echo esc_html( $a['secondaryLabel'] ); ?></a>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}

function naipo_render_home_impact( $a ) {
	$title = implode( '<br/>', array_map( 'esc_html', naipo_lines( $a['title'] ) ) );
	ob_start();
	?>
	<section class="home-impact home-v5-impact">
		<div class="home-impact-grid">
			<div>
				<span class="home-kicker reveal" data-reveal-delay="0"><?php echo esc_html( $a['kicker'] ); ?></span>
				<h2 class="reveal" data-reveal-delay="90"><?php echo $title; // phpcs:ignore WordPress.Security.EscapeOutput -- échappé ligne par ligne. ?></h2>
			</div>
			<div class="home-impact-copy reveal" data-reveal-delay="180">
				<p><?php echo esc_html( $a['text'] ); ?></p>
				<?php if ( $a['buttonLabel'] ) : ?>
					<a class="home-outline-button" href="<?php echo esc_url( $a['buttonUrl'] ? $a['buttonUrl'] : naipo_page_url( 'partners' ) ); ?>"><?php echo esc_html( $a['buttonLabel'] ); ?></a>
				<?php endif; ?>
			</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}

function naipo_render_partner_marquee( $a ) {
	$imgs   = '';
	$copies = '';
	foreach ( (array) $a['logos'] as $logo ) {
		list( $src, $alt ) = naipo_image( $logo );
		if ( ! $src ) {
			continue;
		}
		$imgs   .= '<img alt="' . esc_attr( $alt ) . '" src="' . esc_url( $src ) . '"/>';
		$copies .= '<img alt="" src="' . esc_url( $src ) . '"/>';
	}
	if ( ! $imgs ) {
		return is_admin() || wp_is_json_request() ? '<p style="padding:24px">Ajoutez des logos dans la barre latérale.</p>' : '';
	}
	return '<section aria-label="NAIPO partners" class="marquee home-partners-marquee home-v5-partners">'
		. '<div aria-hidden="true" class="home-partners-bg"></div>'
		. '<div class="marquee-track">'
		. '<div class="home-partner-row">' . $imgs . '</div>'
		. '<div aria-hidden="true" class="home-partner-row">' . $copies . '</div>'
		. '</div></section>';
}

function naipo_render_latest_news( $a ) {
	$posts = get_posts(
		array(
			'numberposts'      => max( 1, (int) $a['count'] ),
			'post_status'      => 'publish',
			'suppress_filters' => false,
		)
	);
	$cards = '';
	foreach ( $posts as $i => $p ) {
		$cards .= naipo_news_card( $p, $i );
	}
	ob_start();
	?>
	<section class="home-latest-news" aria-labelledby="home-latest-news-title">
		<div class="home-latest-news-inner">
			<div class="home-latest-news-heading reveal">
				<span class="home-kicker"><?php echo esc_html( $a['kicker'] ); ?></span>
				<h2 id="home-latest-news-title"><?php echo esc_html( $a['title'] ); ?></h2>
			</div>
			<div class="news-grid home-news-grid"><?php echo $cards; // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}

function naipo_render_news_list( $a ) {
	$paged = max( 1, (int) get_query_var( 'paged' ), (int) get_query_var( 'page' ) );
	$query = new WP_Query(
		array(
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'posts_per_page' => max( 1, (int) $a['perPage'] ),
			'paged'          => $paged,
		)
	);
	$cards = '';
	foreach ( $query->posts as $i => $p ) {
		$cards .= naipo_news_card( $p, $i );
	}
	$pagination = '';
	if ( $query->max_num_pages > 1 && ! is_admin() && ! wp_is_json_request() ) {
		$pagination = paginate_links(
			array(
				'total'     => $query->max_num_pages,
				'current'   => $paged,
				'type'      => 'list',
				'prev_text' => '←',
				'next_text' => '→',
			)
		);
		$pagination = '<nav class="naipo-pagination" aria-label="Pagination">' . $pagination . '</nav>';
	}
	return '<section class="news-listing u-059"><div class="u-060 news-grid">' . $cards . '</div>' . $pagination . '</section>';
}

function naipo_render_page_hero( $a ) {
	$variant = $a['variant'];
	$lines   = naipo_lines( $a['title'] );

	$section = 'subpage-hero';
	$h1      = 'subpage-hero-title';
	if ( 'about' === $variant ) {
		$section = 'about-hero subpage-hero';
		$h1     .= ' about-hero-title';
	} elseif ( 'core-team' === $variant ) {
		$section = 'subpage-hero core-team-hero';
		$h1     .= ' core-team-hero-title';
	}

	if ( count( $lines ) > 1 ) {
		$title = '';
		foreach ( $lines as $line ) {
			$title .= '<span>' . esc_html( $line ) . '</span>';
		}
	} else {
		$title = esc_html( implode( ' ', $lines ) );
	}

	$html  = '<section class="' . esc_attr( $section ) . '"><div class="subpage-hero-inner">';
	$html .= '<span class="subpage-hero-label">' . esc_html( $a['label'] ) . '</span>';
	$html .= '<h1 class="' . esc_attr( $h1 ) . '">' . $title . '</h1>';
	if ( '' !== trim( $a['description'] ) ) {
		$html .= '<p class="subpage-hero-description">' . esc_html( $a['description'] ) . '</p>';
	}
	return $html . '</div></section>';
}

function naipo_render_section( $a, $content ) {
	$tag   = 'div' === $a['tag'] ? 'div' : 'section';
	$attrs = '';
	if ( $a['cssClass'] ) {
		$attrs .= ' class="' . esc_attr( $a['cssClass'] ) . '"';
	}
	if ( $a['sectionId'] ) {
		$attrs .= ' id="' . esc_attr( $a['sectionId'] ) . '"';
	}
	if ( $a['shell'] ) {
		$content = '<div class="' . esc_attr( $a['shell'] ) . '">' . $content . '</div>';
	}
	return '<' . $tag . $attrs . '>' . $content . '</' . $tag . '>';
}

function naipo_render_heading( $a ) {
	$kicker = esc_html( $a['kicker'] );
	$title  = esc_html( $a['title'] );

	switch ( $a['variant'] ) {
		case 'partner':
			$html = '<div class="partner-group-heading">';
			if ( $kicker ) {
				$html .= '<span class="partners-kicker reveal">' . $kicker . '</span>';
			}
			return $html . '<h2 class="partner-group-title reveal">' . $title . '</h2></div>';

		case 'research':
		case 'core-team':
			$p = $a['variant'];
			$html = '<div class="' . $p . '-heading reveal">';
			if ( $kicker ) {
				$html .= '<span class="' . $p . '-kicker">' . $kicker . '</span>';
			}
			return $html . '<h2 class="' . $p . '-section-title">' . $title . '</h2></div>';

		default: // about.
			if ( ! $kicker ) {
				return '<h2 class="about-section-title reveal">' . $title . '</h2>';
			}
			return '<div class="about-heading-block reveal"><span class="about-kicker">' . $kicker . '</span><h2 class="about-section-title">' . $title . '</h2></div>';
	}
}

function naipo_render_figure( $a ) {
	list( $src, $alt ) = naipo_image( $a['image'] );
	if ( ! $src ) {
		return is_admin() || wp_is_json_request() ? '<p style="padding:24px">Choisissez une image dans la barre latérale.</p>' : '';
	}
	$map = array(
		'about-project'  => array( 'about-content-figure reveal reveal-zoom', 'about-project-image', '' ),
		'about-partners' => array( 'about-content-figure reveal reveal-zoom about-partners-figure', 'about-partners-image', '' ),
		'research-model' => array( 'research-model-image reveal reveal-zoom', '', ' data-reveal-delay="160"' ),
	);
	$v    = isset( $map[ $a['variant'] ] ) ? $map[ $a['variant'] ] : $map['about-project'];
	$html = '<figure class="' . $v[0] . '"' . $v[2] . '><img alt="' . esc_attr( $alt ) . '"' . ( $v[1] ? ' class="' . $v[1] . '"' : '' ) . ' src="' . esc_url( $src ) . '" loading="lazy"/>';
	if ( '' !== trim( $a['caption'] ) ) {
		$html .= '<figcaption class="media-caption">' . esc_html( $a['caption'] ) . '</figcaption>';
	}
	return $html . '</figure>';
}

function naipo_render_cta_link( $a ) {
	return '<div class="about-partners-cta reveal"><a class="about-outline-button" href="' . esc_url( $a['url'] ? $a['url'] : naipo_page_url( 'partners' ) ) . '">' . esc_html( $a['label'] ) . '</a></div>';
}

function naipo_render_governance_card( $a ) {
	return '<article class="about-governance-card reveal"><h3>' . esc_html( $a['title'] ) . '</h3><p>' . esc_html( $a['text'] ) . '</p></article>';
}

function naipo_render_team_feature( $a ) {
	list( $src, $alt ) = naipo_image( $a['photo'], 'large' );
	$photo = $src ? '<img src="' . esc_url( $src ) . '" alt="' . esc_attr( $alt ) . '" loading="lazy"/>' : 'Group photo';
	$url   = $a['url'] ? $a['url'] : naipo_page_url( 'about-us/core-team' );
	return '<a class="about-team-feature-card reveal" href="' . esc_url( $url ) . '" aria-label="' . esc_attr__( 'View the full NAIPO core team', 'naipo' ) . '">'
		. '<div aria-hidden="true" class="about-team-group-photo' . ( $src ? ' has-photo' : '' ) . '">' . $photo . '</div>'
		. '<div class="about-team-feature-copy"><h3>' . esc_html( $a['title'] ) . '</h3><span class="about-team-feature-link">' . esc_html( $a['linkLabel'] ) . '</span></div></a>';
}

function naipo_render_team_member( $a ) {
	list( $src, $alt ) = naipo_image( $a['photo'], 'medium_large' );
	$photo = $src ? '<img src="' . esc_url( $src ) . '" alt="' . esc_attr( $alt ? $alt : $a['name'] ) . '" loading="lazy"/>' : 'Photo';
	return '<article class="core-team-member reveal">'
		. '<div aria-hidden="true" class="core-team-photo' . ( $src ? ' has-photo' : '' ) . '">' . $photo . '</div>'
		. '<div class="core-team-copy"><strong>' . esc_html( $a['name'] ) . '</strong><span>' . esc_html( $a['role'] ) . '</span><small>' . esc_html( $a['institution'] ) . '</small></div></article>';
}

function naipo_render_partner_card( $a ) {
	list( $src, $alt ) = naipo_image( $a['logo'] );
	$name = $a['name'];
	$id   = $a['anchorId'] ? ' id="' . esc_attr( $a['anchorId'] ) . '"' : '';
	$logo = $src ? '<img alt="' . esc_attr( $alt ? $alt : $name ) . '" src="' . esc_url( $src ) . '"/>' : '';

	if ( $a['url'] ) {
		/* translators: %s: nom du partenaire */
		$label = sprintf( __( 'Visit %s website (opens in a new tab)', 'naipo' ), $name );
		$open  = '<a class="partner-card reveal"' . $id . ' href="' . esc_url( $a['url'] ) . '" target="_blank" rel="noopener noreferrer" aria-label="' . esc_attr( $label ) . '">';
		$close = '<span class="partner-external" aria-hidden="true">↗</span></a>';
	} else {
		$open  = '<div class="partner-card reveal"' . $id . '>';
		$close = '</div>';
	}

	return $open
		. '<span class="partner-logo">' . $logo . '</span>'
		. '<span class="partner-info"><strong class="partner-name">' . esc_html( $name ) . '</strong><span class="partner-type">' . esc_html( $a['type'] ) . '</span></span>'
		. $close;
}

function naipo_render_research_project( $a ) {
	return '<article class="research-project reveal">'
		. '<span class="research-project-number">' . esc_html( $a['number'] ) . '</span>'
		. '<h3 class="research-project-title">' . esc_html( $a['title'] ) . '</h3>'
		. '<p class="research-project-description">' . esc_html( $a['description'] ) . '</p></article>';
}

function naipo_render_contact_cta( $a ) {
	$id     = wp_unique_id( 'contact-title-' );
	$mailto = 'mailto:' . $a['email'] . ( $a['subject'] ? '?subject=' . rawurlencode( $a['subject'] ) : '' );
	return '<section class="contact-cta-section" aria-labelledby="' . esc_attr( $id ) . '"><div class="contact-cta-shell"><div class="contact-cta-card reveal">'
		. '<span class="contact-cta-kicker">' . esc_html( $a['kicker'] ) . '</span>'
		. '<h2 class="contact-cta-title" id="' . esc_attr( $id ) . '">' . esc_html( $a['title'] ) . '</h2>'
		. '<p class="contact-cta-copy">' . esc_html( $a['text'] ) . '</p>'
		. '<a class="contact-cta-mail" href="' . esc_url( $mailto, array( 'mailto' ) ) . '">' . esc_html( $a['email'] ) . ' <span aria-hidden="true">→</span></a>'
		. '</div></div></section>';
}
