<?php
/**
 * Installation du contenu NAIPO à l'activation du thème :
 * images dans la médiathèque, pages, page d'accueil, menu, catégories et News.
 *
 * Ne remplace jamais une page ou un article existant (même chemin / même slug).
 * Peut être relancé depuis Apparence → Contenu NAIPO.
 *
 * @package naipo
 */

defined( 'ABSPATH' ) || exit;

add_action( 'after_switch_theme', 'naipo_maybe_install' );

function naipo_maybe_install() {
	if ( ! get_option( 'naipo_installed' ) ) {
		naipo_install();
	}
}

/**
 * Importe une image du thème dans la médiathèque (une seule fois).
 *
 * @return int ID de la pièce jointe, 0 en cas d'échec.
 */
function naipo_import_asset( $file, $alt = '' ) {
	$assets = get_option( 'naipo_assets', array() );
	$assets = is_array( $assets ) ? $assets : array();

	if ( ! empty( $assets[ $file ] ) && get_post( $assets[ $file ] ) ) {
		return (int) $assets[ $file ];
	}

	$path = NAIPO_DIR . '/assets/' . $file;
	if ( ! is_readable( $path ) ) {
		return 0;
	}

	$name   = sanitize_file_name( str_replace( ' ', '-', $file ) );
	$upload = wp_upload_bits( $name, null, file_get_contents( $path ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions
	if ( ! empty( $upload['error'] ) ) {
		return 0;
	}

	$type = wp_check_filetype( $upload['file'] );
	$id   = wp_insert_attachment(
		array(
			'post_mime_type' => $type['type'],
			'post_title'     => preg_replace( '/\.[^.]+$/', '', str_replace( array( '_', '-' ), ' ', $file ) ),
			'post_status'    => 'inherit',
		),
		$upload['file']
	);
	if ( is_wp_error( $id ) || ! $id ) {
		return 0;
	}

	require_once ABSPATH . 'wp-admin/includes/image.php';
	wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $upload['file'] ) );
	if ( $alt ) {
		update_post_meta( $id, '_wp_attachment_image_alt', $alt );
	}

	$assets[ $file ] = $id;
	update_option( 'naipo_assets', $assets, false );
	return $id;
}

/**
 * Contenu d'une page : ses sections assemblées.
 */
function naipo_page_content( $sections ) {
	$assets = get_option( 'naipo_assets', array() );
	$parts  = array();
	foreach ( $sections as $slug ) {
		$parts[] = trim( naipo_section_markup( $slug, is_array( $assets ) ? $assets : array() ) );
	}
	return implode( "\n\n", $parts );
}

/**
 * Les News de la maquette.
 */
function naipo_demo_news() {
	$first_body = <<<'HTML'
<!-- wp:paragraph -->
<p>NAIPO begins its four-year program with a shared ambition: make advanced artificial intelligence useful, secure and clinically relevant across the precision-oncology journey.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading">One initiative, seven connected subprojects</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>The program is organised around seven closely connected areas spanning data and computing infrastructure, multimodal modelling, clinical decision support and privacy-preserving methods. Rather than developing isolated tools, the teams work toward a common technical foundation that can be evaluated across institutions.</p>
<!-- /wp:paragraph -->

<!-- wp:group {"className":"news-article-callout reveal","layout":{"type":"default"}} -->
<div class="wp-block-group news-article-callout reveal"><!-- wp:paragraph {"className":"home-kicker"} -->
<p class="home-kicker">Shared model suite</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Clinical, imaging, genomic and other biomedical data sources are brought together through a common model suite designed to support analysis, prediction and future clinical applications.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:heading -->
<h2 class="wp-block-heading">Built through national collaboration</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>The initiative connects universities, hospitals, research centres, industry partners and technology providers. This multidisciplinary structure is intended to keep scientific development close to real clinical needs while maintaining strong standards for data protection and responsible AI.</p>
<!-- /wp:paragraph -->
HTML;

	return array(
		array(
			'slug'    => 'naipo-kicks-off',
			'title'   => 'NAIPO kicks off: seven subprojects, one shared model suite',
			'cat'     => 'Initiative',
			'date'    => '2026-07-01 09:00:00',
			'image'   => 'news-naipo-kicks-off.jpeg',
			'excerpt' => 'The national initiative officially launches its four-year program under the leadership of the EPFL AI Center and ETH AI Center.',
			'lead'    => 'A national collaboration brings together research, clinical expertise and secure computing to accelerate AI-enabled precision oncology across Switzerland.',
			'body'    => $first_body,
		),
		array(
			'slug'    => 'multimodal-foundation-models-first-training-milestone',
			'title'   => 'Multimodal foundation models for oncology: first training milestone',
			'cat'     => 'Research',
			'date'    => '2026-06-01 09:00:00',
			'image'   => 'news-multimodal-foundation-models.jpg',
			'excerpt' => 'The shared model suite integrates clinical, imaging and genomic data sources for analysis and prediction.',
		),
		array(
			'slug'    => 'first-hospital-partners-join-tumorboard-pilot',
			'title'   => 'First hospital partners join the AI-powered tumorboard pilot',
			'cat'     => 'Clinical',
			'date'    => '2026-05-01 09:00:00',
			'image'   => 'news-first-hospital-partners.jpeg',
			'excerpt' => 'University and cantonal hospitals begin real-world testing of decision-support tools in multidisciplinary boards.',
		),
		array(
			'slug'    => 'national-network-precision-oncology',
			'title'   => 'A national network takes shape around precision oncology',
			'cat'     => 'Collaboration',
			'date'    => '2026-04-01 09:00:00',
			'image'   => 'news-national-network.jpeg',
			'excerpt' => 'Clinical, academic and technology partners align around shared infrastructure, interoperable data and responsible AI deployment.',
		),
		array(
			'slug'    => 'naipo-community-workshop',
			'title'   => 'NAIPO community workshop connects research and clinical priorities',
			'cat'     => 'Event',
			'date'    => '2026-03-01 09:00:00',
			'image'   => 'news-community-workshop.jpeg',
			'excerpt' => 'Teams from across Switzerland meet to coordinate use cases, technical requirements and the next steps for the initiative.',
		),
	);
}

/**
 * Installation complète (idempotente).
 *
 * @return array Journal des actions effectuées.
 */
function naipo_install() {
	$log    = array();
	$author = get_current_user_id();
	if ( ! $author ) {
		$admins = get_users( array( 'role' => 'administrator', 'number' => 1, 'fields' => 'ID' ) );
		$author = $admins ? (int) $admins[0] : 0;
	}

	if ( function_exists( 'set_time_limit' ) ) {
		@set_time_limit( 300 ); // phpcs:ignore WordPress.PHP.NoSilencedErrors
	}

	// 1. Images de contenu dans la médiathèque.
	$files = glob( NAIPO_DIR . '/assets/*.{png,jpg,jpeg,gif}', GLOB_BRACE );
	foreach ( (array) $files as $path ) {
		$file = basename( $path );
		if ( in_array( $file, array( 'background_Turquoise_NAIPO.jpg', 'fond_neurones.jpg', 'paintDark.jpg', 'logoWhite.png', 'logoGreen.png', 'innosuisse.png' ), true ) ) {
			continue; // Images de décor, servies par le thème.
		}
		if ( naipo_import_asset( $file ) ) {
			$log[] = 'Image : ' . $file;
		}
	}

	// 2. Pages (créées vides d'abord, pour que les liens internes se résolvent).
	$ids = array();
	foreach ( naipo_pages() as $slug => $page ) {
		$path     = $page[1] ? $page[1] . '/' . $slug : $slug;
		$existing = get_page_by_path( $path );
		if ( $existing ) {
			$ids[ $slug ] = array( $existing->ID, false );
			continue;
		}
		$id = wp_insert_post(
			array(
				'post_type'   => 'page',
				'post_status' => 'publish',
				'post_author' => $author,
				'post_title'  => $page[0],
				'post_name'   => $slug,
				'post_parent' => $page[1] && isset( $ids[ $page[1] ] ) ? $ids[ $page[1] ][0] : 0,
				'menu_order'  => count( $ids ),
			)
		);
		if ( $id && ! is_wp_error( $id ) ) {
			$ids[ $slug ] = array( $id, true );
		}
	}

	// Permaliens "jolis" si le site est encore en mode par défaut.
	if ( '' === get_option( 'permalink_structure' ) ) {
		global $wp_rewrite;
		$wp_rewrite->set_permalink_structure( '/%postname%/' );
		$log[] = 'Permaliens : /%postname%/';
	}

	foreach ( naipo_pages() as $slug => $page ) {
		if ( empty( $ids[ $slug ] ) || ! $ids[ $slug ][1] ) {
			continue;
		}
		wp_update_post(
			array(
				'ID'           => $ids[ $slug ][0],
				'post_content' => wp_slash( naipo_page_content( $page[2] ) ),
			)
		);
		$log[] = 'Page : ' . $page[0];
	}

	// 3. Page d'accueil statique.
	if ( ! empty( $ids['home'] ) ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $ids['home'][0] );
	}

	// 4. News.
	foreach ( naipo_demo_news() as $news ) {
		if ( get_page_by_path( $news['slug'], OBJECT, 'post' ) ) {
			continue;
		}
		$term = term_exists( $news['cat'], 'category' );
		if ( ! $term ) {
			$term = wp_insert_term( $news['cat'], 'category' );
		}
		$cat_id = is_array( $term ) ? (int) $term['term_id'] : (int) $term;

		$body = isset( $news['body'] ) ? $news['body'] : "<!-- wp:paragraph -->\n<p>" . esc_html( $news['excerpt'] ) . "</p>\n<!-- /wp:paragraph -->";
		$id   = wp_insert_post(
			array(
				'post_type'     => 'post',
				'post_status'   => 'publish',
				'post_author'   => $author,
				'post_title'    => $news['title'],
				'post_name'     => $news['slug'],
				'post_excerpt'  => $news['excerpt'],
				'post_content'  => wp_slash( $body ),
				'post_date'     => $news['date'],
				'post_category' => $cat_id ? array( $cat_id ) : array(),
			)
		);
		if ( ! $id || is_wp_error( $id ) ) {
			continue;
		}
		$thumb = naipo_import_asset( $news['image'], $news['title'] );
		if ( $thumb ) {
			set_post_thumbnail( $id, $thumb );
		}
		if ( ! empty( $news['lead'] ) ) {
			update_post_meta( $id, 'naipo_lead', $news['lead'] );
		}
		$log[] = 'News : ' . $news['title'];
	}

	// L'article d'exemple de WordPress ("Hello world!"), s'il n'a jamais été modifié, va à la corbeille.
	$hello = get_post( 1 );
	if ( $hello && 'post' === $hello->post_type && 'publish' === $hello->post_status && $hello->post_date === $hello->post_modified ) {
		wp_trash_post( 1 );
		$log[] = 'Article d\'exemple WordPress mis à la corbeille';
	}

	// 5. Menu principal.
	$locations = get_theme_mod( 'nav_menu_locations', array() );
	if ( empty( $locations['primary'] ) ) {
		$menu_id = wp_create_nav_menu( 'NAIPO — Menu principal' );
		if ( is_wp_error( $menu_id ) ) {
			$menu    = wp_get_nav_menu_object( 'NAIPO — Menu principal' );
			$menu_id = $menu ? $menu->term_id : 0;
		} else {
			foreach ( array( 'about-us', 'partners', 'research', 'news-insights' ) as $i => $slug ) {
				if ( empty( $ids[ $slug ] ) ) {
					continue;
				}
				wp_update_nav_menu_item(
					$menu_id,
					0,
					array(
						'menu-item-object-id' => $ids[ $slug ][0],
						'menu-item-object'    => 'page',
						'menu-item-type'      => 'post_type',
						'menu-item-status'    => 'publish',
						'menu-item-position'  => $i + 1,
					)
				);
			}
		}
		if ( $menu_id ) {
			$locations['primary'] = $menu_id;
			set_theme_mod( 'nav_menu_locations', $locations );
			$log[] = 'Menu principal';
		}
	}

	update_option( 'blogdescription', 'National AI Initiative for Precision Oncology' );
	if ( in_array( get_option( 'blogname' ), array( '', 'WordPress', 'Mon site', 'My WordPress Website' ), true ) ) {
		update_option( 'blogname', 'NAIPO' );
	}

	// Les règles seront régénérées à la prochaine requête (avec toutes les taxonomies).
	delete_option( 'rewrite_rules' );
	update_option( 'naipo_installed', NAIPO_VERSION );

	return $log;
}

/* -------------------------------------------------------------------------
 * Page d'administration : Apparence → Contenu NAIPO
 * ---------------------------------------------------------------------- */

add_action(
	'admin_menu',
	function () {
		add_theme_page( 'Contenu NAIPO', 'Contenu NAIPO', 'manage_options', 'naipo-content', 'naipo_admin_page' );
	}
);

add_action(
	'admin_post_naipo_install',
	function () {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( 'Accès refusé.' );
		}
		check_admin_referer( 'naipo_install' );
		$log = naipo_install();
		set_transient( 'naipo_install_log', $log, 300 );
		wp_safe_redirect( admin_url( 'themes.php?page=naipo-content&done=1' ) );
		exit;
	}
);

function naipo_admin_page() {
	$log = get_transient( 'naipo_install_log' );
	delete_transient( 'naipo_install_log' );
	?>
	<div class="wrap">
		<h1>Contenu NAIPO</h1>
		<p>Crée les pages (Home, About us, Core Team, Partners, Research, News &amp; Insights), les News de démonstration, le menu principal, et importe les images dans la médiathèque.</p>
		<p><strong>Les pages et articles qui existent déjà ne sont jamais modifiés.</strong> Pour régénérer une page, mettez-la d'abord à la corbeille (et videz la corbeille), puis relancez l'installation.</p>
		<?php if ( isset( $_GET['done'] ) ) : // phpcs:ignore WordPress.Security.NonceVerification ?>
			<div class="notice notice-success"><p>
				<?php echo $log ? esc_html( count( $log ) ) . ' élément(s) créé(s) :<br>' . implode( '<br>', array_map( 'esc_html', $log ) ) : 'Rien à créer : tout est déjà en place.'; // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</p></div>
		<?php endif; ?>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="naipo_install"/>
			<?php wp_nonce_field( 'naipo_install' ); ?>
			<?php submit_button( 'Installer le contenu manquant' ); ?>
		</form>
	</div>
	<?php
}
