var el = wp.element.createElement,
	registerBlockType = wp.blocks.registerBlockType,
	ServerSideRender = wp.components.ServerSideRender,
	TextControl = wp.components.TextControl,
	RadioControl = wp.components.RadioControl,
    SelectControl = wp.components.SelectControl,
	TextareaControl = wp.components.TextareaControl,
	CheckboxControl = wp.components.CheckboxControl,
	InspectorControls = wp.editor.InspectorControls;

registerBlockType( 'takethelead/homepage', {
	title: 'Take the Lead Homepage',
    description: 'Displays the homepage form',
	icon: 'email',
	category: 'widgets',
    edit: function( props ) {		
        return [
            el( 'h2', // Tag type.
               {
                className: props.className,
                },
				'Take the Lead'
              ),
		];
	},

	// We're going to be rendering in PHP, so save() can just return null.
	save: function() {
		return null;
	},
} );

registerBlockType( 'takethelead/standalone', {
	title: 'Take the Lead',
    description: 'Displays the form',
	icon: 'email',
	category: 'widgets',
    edit: function( props ) {		
        return [
            el( 'h2', // Tag type.
               {
                className: props.className,
                },
				'Take the Lead'
              ),
		];
	},

	// We're going to be rendering in PHP, so save() can just return null.
	save: function() {
		return null;
	},
} );