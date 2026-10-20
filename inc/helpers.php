<?php
/**
 * Fonctions utilitaires.
 *
 * @package naipo
 */

defined( 'ABSPATH' ) || exit;

/**
 * URL d'un fichier du dossier assets du thème.
 */
function naipo_asset_uri( $file ) {
	return NAIPO_URI . '/assets/' . rawurlencode( $file );
}

/**
 * URL d'une page à partir de son chemin (ex. "about-us" ou "about-us/core-team").
 * Si la page n'existe pas, renvoie l'URL "jolie" attendue.
 */
function naipo_page_url( $path, $anchor = '' ) {
	$page = get_page_by_path( $path );
	$url  = $page ? get_permalink( $page ) : home_url( '/' . trim( $path, '/' ) . '/' );
	return $anchor ? $url . '#' . $anchor : $url;
}

/**
 * Découpe un texte multiligne en lignes non vides.
 */
function naipo_lines( $text ) {
	$lines = preg_split( '/\r\n|\r|\n/', (string) $text );
	return array_values( array_filter( array_map( 'trim', $lines ), 'strlen' ) );
}

/**
 * Normalise un attribut image ({id, url, alt}) et renvoie [src, alt].
 */
function naipo_image( $img, $size = 'full' ) {
	$img = is_array( $img ) ? $img : array();
	$src = '';
	$alt = isset( $img['alt'] ) ? $img['alt'] : '';
	if ( ! empty( $img['id'] ) ) {
		$src = wp_get_attachment_image_url( (int) $img['id'], $size );
		if ( '' === $alt ) {
			$alt = (string) get_post_meta( (int) $img['id'], '_wp_attachment_image_alt', true );
		}
	}
	if ( ! $src && ! empty( $img['url'] ) ) {
		$src = $img['url'];
	}
	return array( $src, $alt );
}

/**
 * Attribut data-reveal-delay (vide si 0).
 */
function naipo_delay( $ms ) {
	$ms = (int) $ms;
	return $ms > 0 ? ' data-reveal-delay="' . $ms . '"' : '';
}

/**
 * Carte News (accueil, page News, archives).
 */
function naipo_news_card( $post, $index = 0 ) {
	$post     = get_post( $post );
	$variants = array(
		array( 'u-095', 'u-096' ),
		array( 'u-104', 'u-105' ),
		array( 'u-106', 'u-107' ),
	);
	$v        = $variants[ $index % 3 ];
	$cats     = get_the_category( $post->ID );
	$cat      = $cats ? $cats[0]->name : '';
	$title    = get_the_title( $post );
	$thumb    = get_the_post_thumbnail_url( $post, 'large' );

	ob_start();
	?>
	<article class="pillar reveal <?php echo esc_attr( $v[0] ); ?> news-card">
		<div class="<?php echo esc_attr( $v[1] ); ?> news-card-media">
			<?php if ( $thumb ) : ?>
				<img alt="<?php echo esc_attr( $title ); ?>" class="u-097 news-card-image" src="<?php echo esc_url( $thumb ); ?>" loading="lazy"/>
			<?php endif; ?>
		</div>
		<div class="u-098 news-card-content">
			<div class="u-099">
				<?php if ( $cat ) : ?><span class="u-100"><?php echo esc_html( $cat ); ?></span><?php endif; ?>
				<span class="u-101"><?php echo esc_html( get_the_date( 'F Y', $post ) ); ?></span>
			</div>
			<h3 class="u-102"><?php echo esc_html( $title ); ?></h3>
			<p class="u-039"><?php echo esc_html( get_the_excerpt( $post ) ); ?></p>
			<a class="u-103" href="<?php echo esc_url( get_permalink( $post ) ); ?>"><?php esc_html_e( 'Read more →', 'naipo' ); ?></a>
		</div>
	</article>
	<?php
	return ob_get_clean();
}

/**
 * Lit une section du dossier /sections et remplace les jetons :
 *   {{asset:fichier}}     URL de l'image (médiathèque si importée, sinon thème)
 *   {{asset_id:fichier}}  ID de la pièce jointe (0 si non importée)
 *   {{page:chemin}}       URL d'une page du site
 *   {{home}}              URL d'accueil
 *
 * @param string $slug    Nom du fichier sans extension.
 * @param array  $assets  Correspondance fichier => ID de pièce jointe.
 */
function naipo_section_markup( $slug, $assets = array() ) {
	$file = NAIPO_DIR . '/sections/' . $slug . '.html';
	if ( ! is_readable( $file ) ) {
		return '';
	}
	$markup = file_get_contents( $file ); // phpcs:ignore WordPress.WP.AlternativeFunctions

	$markup = preg_replace_callback(
		'/\{\{(asset|asset_id|page):([^}]+)\}\}/',
		function ( $m ) use ( $assets ) {
			$key = trim( $m[2] );
			if ( 'page' === $m[1] ) {
				$parts = explode( '#', $key, 2 );
				return esc_url( naipo_page_url( $parts[0], isset( $parts[1] ) ? $parts[1] : '' ) );
			}
			$id = isset( $assets[ $key ] ) ? (int) $assets[ $key ] : 0;
			if ( 'asset_id' === $m[1] ) {
				return (string) $id;
			}
			$url = $id ? wp_get_attachment_url( $id ) : '';
			return esc_url( $url ? $url : naipo_asset_uri( $key ) );
		},
		$markup
	);

	return str_replace( '{{home}}', esc_url( home_url( '/' ) ), $markup );
}
