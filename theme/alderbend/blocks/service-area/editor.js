( function ( blocks, element, blockEditor, components, serverSideRender, i18n ) {
	'use strict';

	var el = element.createElement;
	var __ = i18n.__;
	var DAYS = [ '', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday' ];

	blocks.registerBlockType( 'alderbend/service-area', {
		edit: function ( props ) {
			var towns = props.attributes.towns || [];

			function update( index, key, value ) {
				var next = towns.map( function ( t, i ) {
					if ( i !== index ) {
						return t;
					}
					var copy = Object.assign( {}, t );
					copy[ key ] = value;
					return copy;
				} );
				props.setAttributes( { towns: next } );
			}

			var rows = towns.map( function ( town, index ) {
				return el(
					'div',
					{ key: index, style: { display: 'grid', gridTemplateColumns: '1fr 1fr auto', gap: '8px', alignItems: 'end', marginBottom: '8px' } },
					el( components.TextControl, {
						label: __( 'Town', 'alderbend' ),
						value: town.name,
						onChange: function ( v ) { update( index, 'name', v ); },
						__nextHasNoMarginBottom: true,
						__next40pxDefaultSize: true
					} ),
					el( components.SelectControl, {
						label: __( 'Route day', 'alderbend' ),
						value: town.day || '',
						options: DAYS.map( function ( d ) { return { label: d || __( 'Not set', 'alderbend' ), value: d }; } ),
						onChange: function ( v ) { update( index, 'day', v ); },
						__nextHasNoMarginBottom: true,
						__next40pxDefaultSize: true
					} ),
					el( components.Button, {
						isDestructive: true,
						variant: 'tertiary',
						onClick: function () { props.setAttributes( { towns: towns.filter( function ( t, i ) { return i !== index; } ) } ); }
					}, __( 'Remove', 'alderbend' ) )
				);
			} );

			return el(
				'div',
				blockEditor.useBlockProps(),
				el(
					blockEditor.InspectorControls,
					null,
					el(
						components.PanelBody,
						{ title: __( 'Towns', 'alderbend' ), initialOpen: true },
						rows,
						el( components.Button, {
							variant: 'secondary',
							onClick: function () { props.setAttributes( { towns: towns.concat( [ { name: '', day: '' } ] ) } ); }
						}, __( 'Add a town', 'alderbend' ) )
					)
				),
				el( serverSideRender, { block: 'alderbend/service-area', attributes: props.attributes } )
			);
		},
		save: function () {
			return null;
		}
	} );
}( window.wp.blocks, window.wp.element, window.wp.blockEditor, window.wp.components, window.wp.serverSideRender, window.wp.i18n ) );
