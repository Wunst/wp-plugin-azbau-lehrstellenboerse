( function( wp ) {
	var registerBlockType = wp.blocks.registerBlockType;
	var el = wp.element.createElement;
	var useBlockProps = wp.blockEditor.useBlockProps;

	registerBlockType( 'azbau-lehrstellenboerse/lsb', {
		edit: function() {
			return el(
				'p',
				useBlockProps(),
        "Lehrstellenbörse"
			);
		},
	} );
}(
	window.wp
) );

