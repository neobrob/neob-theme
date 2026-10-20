/**
 * NAIPO — enregistrement des blocs dans l'éditeur.
 * Les définitions viennent de PHP (window.naipoBlocks, voir inc/blocks.php).
 * Blocs simples : aperçu rendu par le serveur + champs dans la barre latérale.
 * Bloc "Section" : conteneur dans lequel on place d'autres blocs.
 */
( function ( wp ) {
	if ( ! wp || ! window.naipoBlocks ) {
		return;
	}

	const el = wp.element.createElement;
	const Fragment = wp.element.Fragment;
	const { registerBlockType } = wp.blocks;
	const { InspectorControls, InnerBlocks, MediaUpload, MediaUploadCheck, useBlockProps } = wp.blockEditor;
	const { PanelBody, TextControl, TextareaControl, SelectControl, Button, Placeholder } = wp.components;
	const ServerSideRender = wp.serverSideRender;

	function imageValue( media ) {
		return {
			id: media.id,
			url: ( media.sizes && media.sizes.full && media.sizes.full.url ) || media.url,
			alt: media.alt || '',
		};
	}

	function ImageControl( { label, value, onChange, multiple } ) {
		const items = multiple ? value || [] : value && value.url ? [ value ] : [];
		const ids = items.map( ( i ) => i.id ).filter( Boolean );

		return el(
			'div',
			{ className: 'naipo-image-control', style: { marginBottom: '16px' } },
			el( 'p', { style: { fontSize: '11px', fontWeight: 500, textTransform: 'uppercase', margin: '0 0 8px' } }, label ),
			items.length
				? el(
						'div',
						{ style: { display: 'flex', flexWrap: 'wrap', gap: '6px', marginBottom: '8px' } },
						items.map( ( item, i ) =>
							el( 'img', {
								key: i,
								src: item.url,
								alt: '',
								style: {
									width: multiple ? '56px' : '100%',
									height: multiple ? '40px' : 'auto',
									objectFit: 'contain',
									background: '#f0f0f0',
									borderRadius: '2px',
								},
							} )
						)
				  )
				: null,
			el(
				MediaUploadCheck,
				null,
				el( MediaUpload, {
					onSelect: ( media ) =>
						onChange( multiple ? media.map( imageValue ) : imageValue( media ) ),
					allowedTypes: [ 'image' ],
					multiple: multiple ? 'add' : false,
					gallery: false,
					value: multiple ? ids : ids[ 0 ],
					render: ( { open } ) =>
						el(
							'div',
							{ style: { display: 'flex', gap: '8px' } },
							el( Button, { variant: 'secondary', onClick: open }, items.length ? 'Modifier' : 'Choisir' ),
							items.length
								? el( Button, { variant: 'link', isDestructive: true, onClick: () => onChange( multiple ? [] : {} ) }, 'Retirer' )
								: null
						),
				} )
			)
		);
	}

	function controlFor( key, def, attributes, setAttributes ) {
		const value = attributes[ key ];
		const set = ( v ) => setAttributes( { [ key ]: v } );
		const common = { key, label: def.label, __nextHasNoMarginBottom: true };

		switch ( def.control ) {
			case 'textarea':
				return el( TextareaControl, Object.assign( {}, common, { value: value || '', onChange: set, rows: 4 } ) );
			case 'url':
				return el( TextControl, Object.assign( {}, common, { type: 'url', value: value || '', onChange: set, help: 'Laisser vide pour le lien par défaut.' } ) );
			case 'number':
				return el( TextControl, Object.assign( {}, common, { type: 'number', min: 1, value: value, onChange: ( v ) => set( parseInt( v, 10 ) || 1 ) } ) );
			case 'select':
				return el(
					SelectControl,
					Object.assign( {}, common, {
						value,
						onChange: set,
						options: Object.keys( def.options ).map( ( k ) => ( { value: k, label: def.options[ k ] } ) ),
					} )
				);
			case 'image':
				return el( ImageControl, { key, label: def.label, value, onChange: set } );
			case 'gallery':
				return el( ImageControl, { key, label: def.label, value, onChange: set, multiple: true } );
			default:
				return el( TextControl, Object.assign( {}, common, { value: value || '', onChange: set } ) );
		}
	}

	function inspector( def, props ) {
		return el(
			InspectorControls,
			null,
			el(
				PanelBody,
				{ title: 'Contenu', initialOpen: true },
				el(
					'div',
					{ style: { display: 'grid', gap: '16px' } },
					Object.keys( def.attributes ).map( ( key ) =>
						controlFor( key, def.attributes[ key ], props.attributes, props.setAttributes )
					)
				)
			)
		);
	}

	Object.keys( window.naipoBlocks ).forEach( ( name ) => {
		const def = window.naipoBlocks[ name ];
		const attributes = {};
		Object.keys( def.attributes ).forEach( ( key ) => {
			attributes[ key ] = { type: def.attributes[ key ].type, default: def.attributes[ key ].default };
		} );

		if ( def.inner ) {
			registerBlockType( name, {
				apiVersion: 3,
				title: def.title,
				category: 'naipo',
				icon: def.icon,
				attributes,
				supports: { html: false, customClassName: false },
				edit( props ) {
					const a = props.attributes;
					const blockProps = useBlockProps( { className: a.cssClass || undefined } );
					const inner = el( InnerBlocks, { renderAppender: InnerBlocks.ButtonBlockAppender } );
					return el(
						Fragment,
						null,
						inspector( def, props ),
						el( 'div', blockProps, a.shell ? el( 'div', { className: a.shell }, inner ) : inner )
					);
				},
				save() {
					return el( InnerBlocks.Content );
				},
			} );
			return;
		}

		registerBlockType( name, {
			apiVersion: 3,
			title: def.title,
			category: 'naipo',
			icon: def.icon,
			attributes,
			supports: { html: false, customClassName: false },
			edit( props ) {
				const blockProps = useBlockProps( { className: 'naipo-block-preview' } );
				return el(
					Fragment,
					null,
					inspector( def, props ),
					el(
						'div',
						blockProps,
						el(
							'div',
							{ style: { pointerEvents: 'none' } },
							el( ServerSideRender, {
								block: name,
								attributes: props.attributes,
								EmptyResponsePlaceholder: () => el( Placeholder, { label: def.title, instructions: 'Remplissez les champs dans la barre latérale.' } ),
							} )
						)
					)
				);
			},
			save() {
				return null;
			},
		} );
	} );
} )( window.wp );
