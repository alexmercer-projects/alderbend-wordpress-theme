( function ( blocks, element, blockEditor, components, serverSideRender, i18n ) {
	'use strict';

	var el = element.createElement;
	var __ = i18n.__;

	blocks.registerBlockType( 'alderbend/quote-form', {
		edit: function ( props ) {
			var services = props.attributes.services || [];

			return el(
				'div',
				blockEditor.useBlockProps(),
				el(
					blockEditor.InspectorControls,
					null,
					el(
						components.PanelBody,
						{ title: __( 'Form settings', 'alderbend' ), initialOpen: true },
						el( components.TextControl, {
							label: __( 'Button label', 'alderbend' ),
							value: props.attributes.submitLabel,
							onChange: function ( v ) { props.setAttributes( { submitLabel: v } ); },
							__nextHasNoMarginBottom: true,
							__next40pxDefaultSize: true
						} ),
						el( components.TextareaControl, {
							label: __( 'Services (one per line)', 'alderbend' ),
							value: services.join( '\n' ),
							onChange: function ( v ) {
								props.setAttributes( {
									services: v.split( '\n' ).map( function ( s ) { return s.trim(); } ).filter( Boolean )
								} );
							},
							__nextHasNoMarginBottom: true
						} )
					)
				),
				el( serverSideRender, { block: 'alderbend/quote-form', attributes: props.attributes } )
			);
		},
		save: function () {
			return null;
		}
	} );
}( window.wp.blocks, window.wp.element, window.wp.blockEditor, window.wp.components, window.wp.serverSideRender, window.wp.i18n ) );
