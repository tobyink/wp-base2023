<?php

namespace App;

use StoutLogic\AcfBuilder\FieldsBuilder;

function _parsley_acf_style ( $builder, $group_name='style', $group_label='Style', $paddingsize=false ) {

	$g = $builder->addGroup( $group_name, [
		'label'         => $group_label,
		'layout'        => 'row',
	] );

	$g->addSelect( 'text_colour', [
		'label'         => 'Text colour',
		'allow_null'    => 1,
		'choices'       => array(
			'primary' => 'primary',
			'secondary' => 'secondary',
			'tertiary' => 'tertiary',
			'quaternary' => 'quaternary',
			'light' => 'light',
			'dark' => 'dark',
			'success' => 'success',
			'danger' => 'danger',
			'warning' => 'warning',
			'info' => 'info',
			'white' => 'white',
		),
		'default_value' => false,
		'return_format' => 'value',
	] );

	$g->addSelect( 'background_colour', [
		'label'         => 'Background colour',
		'allow_null'    => 1,
		'choices'       => array(
			'primary' => 'primary',
			'secondary' => 'secondary',
			'tertiary' => 'tertiary',
			'quaternary' => 'quaternary',
			'light' => 'light',
			'dark' => 'dark',
			'gradient-primary' => 'gradient-primary',
			'gradient-secondary' => 'gradient-secondary',
			'gradient-tertiary' => 'gradient-tertiary',
			'gradient-quaternary' => 'gradient-quaternary',
			'gradient-light' => 'gradient-light',
			'gradient-dark' => 'gradient-dark',
			'success' => 'success',
			'danger' => 'danger',
			'warning' => 'warning',
			'info' => 'info',
			'white' => 'white',
			'custom' => 'custom',
		),
		'default_value' => false,
		'return_format' => 'value',
	] );

	$g->addColorPicker( 'background_colour_custom', [
		'label'         => 'Custom background colour',
		'allow_null'    => 0,
		'default_value' => \App\theme_get_option('colour-primary'),
	] )->conditional( 'background_colour', '==', 'custom' );

	$g->addSelect( 'background_effect', [
		'label'         => 'Background effect',
		'choices'       => [ 'none', 'image', 'gradient' ],
		'default_value' => 'none',
		'return_format' => 'value',
	] );

	$g->addText( 'background_gradient', [
		'label'         => 'Linear gradient (CSS)',
		'default_value' => '$secondary, white',
		'required'      => 1,
	] )->conditional( 'background_effect', '==', 'gradient' );

	$g->addImage( 'background_image', [ 'required' => 1, 'return_format' => 'array' ] )->conditional( 'background_effect', '==', 'image' );
	$g->addSelect( 'background_attachment', [ 'required' => 1, 'choices' => [ 'scroll', 'fixed', 'local' ], 'default_value' => 'scroll' ] )->conditional( 'background_effect', '==', 'image' );
	$g->addSelect( 'background_position', [ 'required' => 1, 'choices' => [ 'top left', 'top', 'top right', 'left', 'center', 'right', 'bottom left', 'bottom', 'bottom right' ], 'default_value' => 'top left' ] )->conditional( 'background_effect', '==', 'image' );
	$g->addSelect( 'background_repeat', [ 'required' => 1, 'choices' => [ 'repeat', 'repeat-x', 'repeat-y', 'no-repeat' ], 'default_value' => 'repeat' ] )->conditional( 'background_effect', '==', 'image' );
	$g->addText( 'background_size', [ 'required' => 1, 'default_value' => 'auto' ] )->conditional( 'background_effect', '==', 'image' );

	$g->addSelect( 'border_colour', [
		'label'         => 'Border colour',
		'allow_null'    => 1,
		'choices'       => array(
			'primary' => 'primary',
			'secondary' => 'secondary',
			'tertiary' => 'tertiary',
			'quaternary' => 'quaternary',
			'light' => 'light',
			'dark' => 'dark',
			'success' => 'success',
			'danger' => 'danger',
			'warning' => 'warning',
			'info' => 'info',
			'white' => 'white',
		),
		'default_value' => false,
		'return_format' => 'value',
	] );

	$g->addNumber( 'padding', [
		'label'         => 'Padding',
		'instructions'  => 'A value from 0 (none) to 5 (most)',
		'required'      => 0,
		'allow_null'    => 1,
		'default_value' => $paddingsize,
		'min'           => 0,
		'max'           => 5,
		'step'          => 1,
	] );

	$g->addSelect( 'text_alignment', [
		'choices'       => [ 'left', 'center', 'right' ],
		'default_value' => null,
		'allow_null'    => 1,
	] );

	$g->addText( 'additional_classes', [
		'label'         => 'Additional classes',
	] );

	$g->endGroup();
}

function _parsley_acf_heading ( $builder, $group_name='heading', $group_label='Heading', $default_level='h2' ) {

	$builder->addText( $group_name, [
		'label'         => $group_label,
	] );

	$g = $builder->addGroup( $group_name . '_level', [
		'label'  => $group_label . ' Style',
		'layout' => 'row',
	] )->conditional( $group_name, '!=empty', '' );

	$g->addSelect( 'real', [
		'label'         => 'Real tag',
		'choices'       => [
			'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
			'div', 'p',
			'none',
		],
		'default_value' => $default_level,
		'allow_null'    => 0,
		'return_format' => 'value',
	] );

	$g->addSelect( 'visual', [
		'label'         => 'Displayed as',
		'choices'       => [
			'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
			'display-1', 'display-2', 'display-3', 'display-4', 'display-5', 'display-6',
			'd-none',
		],
		'default_value' => false,
		'allow_null'    => 1,
		'return_format' => 'value',
	] )->conditional( 'real', '!=', 'none' );

	$g->addNumber( 'padding', [
		'label'         => 'Padding',
		'instructions'  => 'A value from 0 (none) to 5 (most)',
		'required'      => 0,
		'allow_null'    => 1,
		'default_value' => false,
		'min'           => 0,
		'max'           => 5,
		'step'          => 1,
	] )->conditional( 'real', '!=', 'none' );

	$g->addSelect( 'text_alignment', [
		'choices'       => [ 'left', 'center', 'right' ],
		'default_value' => null,
		'allow_null'    => 1,
	] )->conditional( 'real', '!=', 'none' );

	$g->addText( 'additional_classes', [
		'label'         => 'Additional classes',
	] )->conditional( 'real', '!=', 'none' );

	$g->endGroup();
}

function parsley_acf_section_definition ( $builder, $opts=array(), $callback=false ) {
	
	if ( ! array_key_exists( 'vary_width', $opts ) ) {
		$opts['vary_width'] = true;
	}
	
	if ( ! array_key_exists( 'styling', $opts ) ) {
		$opts['styling'] = true;
	}
	
	if ( ! array_key_exists( 'heading', $opts ) ) {
		$opts['heading'] = true;
	}

	if ( ! array_key_exists( 'heading_level', $opts ) ) {
		$opts['heading_level'] = 'h2';
	}

	$ACF_containment = array(
		'container'       => 'Contained',
		'container-sm'    => 'Contained from S up',
		'container-md'    => 'Contained from M up',
		'container-lg'    => 'Contained from L up',
		'container-xl'    => 'Contained from XL up',
		'container-fluid' => 'Fluid',
		'wide'            => 'Full width',
	);

	$ACF_containment_fw = array(
		'wide'            => 'Full width',
	);

	if ( $callback ) {
		call_user_func( $callback, $builder );
	}

	if ( $opts['heading'] ) {

		$builder->addTab( 'Heading' );

		_parsley_acf_heading( $builder, 'heading', 'Heading', $opts['heading_level'] );

		if ( array_key_exists( 'heading_callback', $opts ) ) {
			call_user_func( $opts['heading_callback'], $builder );
		}
	}

	$builder->addTab( 'Section Options' );

	$builder->addSelect( 'tag', [
		'instructions'  => 'HTML tag',
		'choices'       => [ 'section', 'main', 'article', 'aside', 'nav', 'header', 'footer', 'div' ],
		'default_value' => 'section',
		'required'      => 1,
		'wrapper'       => [ 'width' => '25', 'class' => '', 'id' => '' ],
	] );

	$builder->addText( 'id', [
		'label'         => 'HTML ID',
		'instructions'  => 'HTML `id` attribute for styling and scripting',
		'wrapper'       => [ 'width' => '25', 'class' => '', 'id' => '' ],
	] );

	$builder->addText( 'extra_classes', [
		'instructions'  => 'Space-separated list of classes',
		'wrapper'       => [ 'width' => '25', 'class' => '', 'id' => '' ],
	] );

	$builder->addText( 'aria_role', [
		'label'         => 'ARIA Role',
		'instructions'  => 'Role (accessibility)',
		'wrapper'       => [ 'width' => '25', 'class' => '', 'id' => '' ],
	] );

	$builder->addSelect( 'full_width', [
		'label'         => 'Width',
		'instructions'  => 'Full width or contained',
		'wrapper'       => [ 'width' => '25', 'class' => '', 'id' => '' ],
		'allow_null'    => 0,
		'required'      => 1,
		'choices'       => ( $opts['vary_width'] ? $ACF_containment : $ACF_containment_fw ),
		'default_value' => ( $opts['vary_width'] ? 'container'      : 'wide' ),
		'return_format' => 'value',
	] );

	$builder->addTrueFalse( 'hidden', [
		'label'         => 'Hidden',
		'instructions'  => 'Do not show this section',
		'wrapper'       => [ 'width' => '25', 'class' => '', 'id' => '' ],
		'default_value' => 0,
		'ui'            => 1,
		'ui_on_text'    => 'Hidden',
		'ui_off_text'   => 'Shown',
	] );

	$builder->addTrueFalse( 'exact_html', [
		'label'         => 'Exact HTML',
		'instructions'  => 'Avoid WP paragraph munging.',
		'wrapper'       => [ 'width' => '25', 'class' => '', 'id' => '' ],
		'default_value' => 0,
		'ui'            => 1,
		'ui_on_text'    => 'Exact',
		'ui_off_text'   => 'Munge',
	] );

	if ( $opts['styling'] ) {
		_parsley_acf_style( $builder, 'style', 'Style', 3 );
	}

	$builder->addText( 'link_class', [
		'instructions'  => 'Class for links to this section (used in tab sets)',
		'wrapper'       => [ 'width' => '50', 'class' => '', 'id' => '' ],
	] );

	$builder->addText( 'icon', [
		'instructions'  => 'FontAwesome icon for links to this section (used in tab set)',
		'wrapper'       => [ 'width' => '50', 'class' => '', 'id' => '' ],
	] );

	if ( array_key_exists( 'options_callback', $opts ) ) {
		call_user_func( $opts['options_callback'], $builder );
	}

	$builder->addTextArea( 'extra_css', [
		'label'         => 'Extra CSS',
		'rows'          => 8,
		'instructions'  => '<code>#this</code> will refer to the section element, <code>#heading</code> refers to the main heading for the section, <code>$primary</code>, etc can be used to refer to colours, <code>$serif</code>, etc refer to fonts, and <code>SM-UP</code>, <code>MD-ONLY</code>, <code>LG-DOWN</code>, etc can be used to refer to media breakpoints.',
	] );

	return $builder;
}

function parsley_acf_column_definition ( $builder, $opts=array(), $callback=false ) {

	if ( $callback ) {
		call_user_func( $callback, $builder );
	}

	$builder->addTab( 'Column Options' );

	$g = $builder->addGroup( 'options', [
		'label'         => 'Basic Options',
		'layout'        => 'row',
	] );

	$g->addText( 'classes', [
		'label'         => 'Classes',
		'instructions'  => 'Classes to apply to the column; requires knowledge of the Bootstrap grid system.',
		'default_value' => 'col',
		'wrapper'       => [ 'width' => '25', 'class' => '', 'id' => '' ],
	] );

	$g->addTrueFalse( 'exact_html', [
		'label'         => 'Exact HTML',
		'instructions'  => 'If exact HTML, will avoid Wordpress paragraph munging.',
		'default_value' => 0,
		'ui'            => 1,
		'ui_on_text'    => 'Exact',
		'ui_off_text'   => 'Munge',
		'wrapper'       => [ 'width' => '25', 'class' => '', 'id' => '' ],
	] );

	$g->endGroup();

	if ( array_key_exists( 'options_callback', $opts ) ) {
		call_user_func( $opts['options_callback'], $builder );
	}

	return $builder;
}

$SEC = [];

$SEC[] = parsley_acf_section_definition(
	new FieldsBuilder( 'html_content', [
		'label'   => 'HTML Content',
		'display' => 'block',
	] ),
	[],
	function ( $builder ) {

		$builder->addTab( 'Content' );

		$builder->addWysiwyg( 'content', [
			'label'         => 'Content',
			'tabs'          => 'all',
			'toolbar'       => 'full',
			'media_upload'  => 1,
		] );

	}
);

$SEC[] = parsley_acf_section_definition(
	new FieldsBuilder( 'primary_content', [
		'label'   => 'Primary Content',
		'display' => 'block',
	] )
);

$SEC[] = parsley_acf_section_definition(
	new FieldsBuilder( 'post', [
		'label'   => 'External Post',
		'display' => 'block',
	] ),
	[],
	function ( $builder ) {

		$builder->addTab( 'Post' );

		$builder->addPostObject( 'post_id', [
			'label'         => 'Post',
			'required'      => 1,
			'return_format' => 'id',
			'ui'            => 1,
		] );

	}
);

$ext_section = new FieldsBuilder( 'post_section', [
	'label'   => 'External Section',
	'display' => 'block',
] );
$ext_section->addTab( 'Section' );
$ext_section->addSelect( 'section_source', [
	'label'         => 'Section',
	'required'      => 1,
	'choices'       => [ 'post', 'common' ],
	'default_value' => 'post',
	'wrapper'       => [ 'width' => '34', 'class' => '', 'id' => '' ],
] );
$ext_section->addPostObject( 'post_id', [
	'label'         => 'Post',
	'required'      => 1,
	'return_format' => 'id',
	'ui'            => 1,
	'wrapper'       => [ 'width' => '33', 'class' => '', 'id' => '' ],
] )->conditional( 'section_source', '==', 'post' );
$ext_section->addText( 'section_id', [
	'label'         => 'Section',
	'required'      => 1,
	'wrapper'       => [ 'width' => '33', 'class' => '', 'id' => '' ],
	'instructions'  => 'Section ID or numeric index',
] );
$SEC[] = $ext_section;

$SEC[] = parsley_acf_section_definition(
	new FieldsBuilder( 'columns2', [
		'label'   => 'Advanced Columns',
		'display' => 'block',
	] ),
	[
		'heading_callback' => function ( $builder ) {

			$builder->addTrueFalse( 'heading_in_column', [
				'label'         => 'Heading in First Column',
				'instructions'  => 'Inserts the heading into the first column instead of before the columns.',
				'ui'            => 1,
				'ui_on_text'    => 'Yes',
				'ui_off_text'   => 'No',
			] )->conditional( 'heading', '!=empty', '' );

		},
	],
	function ( $builder ) {

		$builder->addTab( 'Columns' );

		$f = $builder->addFlexibleContent( 'columns', [
			'label'         => 'Columns',
			'required'      => 1,
			'min'           => 1,
			'max'           => 120,
			'button_label'  => 'Add Column',
		] );

		$f->addLayout( parsley_acf_column_definition(
			new FieldsBuilder( 'col_html', [
				'label'   => 'HTML',
				'display' => 'block',
			] ),
			[ ],
			function ( $builder ) {
				$builder->addTab( 'Content' );
				$builder->addWysiwyg( 'content', [
					'label'         => 'Content',
					'tabs'          => 'all',
					'toolbar'       => 'full',
					'media_upload'  => 1,
				]);
			}
		) );

		$f->addLayout( parsley_acf_column_definition(
			new FieldsBuilder( 'col_image', [
				'label'   => 'Image',
				'display' => 'block',
			] ),
			[ ],
			function ( $builder ) {
				$builder->addTab( 'Image' );
				$builder->addImage( 'image', [
					'label'         => 'Image',
					'return_format' => 'id',
					'preview_size'  => 'medium',
					'required'      => 1,
				] );
				$builder->addText( 'alt_text', [ 'wrapper' => [ 'width' => '25', 'class' => '', 'id' => '' ] ] );
				$builder->addText( 'title',    [ 'wrapper' => [ 'width' => '25', 'class' => '', 'id' => '' ] ] );
				$builder->addText( 'caption',  [ 'wrapper' => [ 'width' => '25', 'class' => '', 'id' => '' ] ] );
				$builder->addSelect( 'caption_placement', [
					'wrapper' => [ 'width' => '25', 'class' => '', 'id' => '' ],
					'choices' => [ 'below' => 'Below image', 'above' => 'Above image' ],
					'default_choice' => 'below',
					'return_format' => 'value',
				 ] )->conditional( 'caption', '!=empty', '' );
				$builder->addTrueFalse( 'rounded', [
					'label'         => 'Rounded corners',
					'ui'            => 1,
					'ui_on_text'    => 'Yes',
					'ui_off_text'   => 'No',
				] );
				$builder->addTrueFalse( 'shadow', [
					'label'         => 'Shadow',
					'ui'            => 1,
					'ui_on_text'    => 'Yes',
					'ui_off_text'   => 'No',
				] );
				$builder->addText( 'img_id', [
					'label'         => 'Image ID',
					'instructions'  => 'HTML `id` attribute for styling and scripting (img tag)',
					'wrapper'       => [ 'width' => '25', 'class' => '', 'id' => '' ],
				] );
				$builder->addText( 'img_class', [
					'label'         => 'Image Classes',
					'instructions'  => 'HTML `class` attribute for styling and scripting (img tag)',
					'wrapper'       => [ 'width' => '25', 'class' => '', 'id' => '' ],
					'default_value' => 'img-fluid',
				] );
				$builder->addText( 'img_attrs', [
					'label'         => 'Image Additional Attributes',
					'instructions'  => 'Additional HTML attributes (img tag)',
					'wrapper'       => [ 'width' => '50', 'class' => '', 'id' => '' ],
				] );
			}
		) );

		$f->addLayout( parsley_acf_column_definition(
			new FieldsBuilder( 'col_lottie', [
				'label'   => 'Lottie',
				'display' => 'block',
			] ),
			[ ],
			function ( $builder ) {
				$builder->addTab( 'Animation' );
				$builder->addFile( 'lottie', [ 'label' => 'Lottie File (JSON)', 'return_format' => 'url', 'required' => 1 ] );
				$builder->addTrueFalse( 'loop', [ 'default_value' => 1 ] );
				$builder->addTrueFalse( 'controls', [ 'default_value' => 0 ] );
				$builder->addTrueFalse( 'autoplay', [ 'default_value' => 1 ] );
				$builder->addTrueFalse( 'hover', [ 'default_value' => 0 ] );
				$builder->addText( 'speed', [ 'default_value' => '1', 'required' => 1 ] );
				$builder->addText( 'style', [ 'default_value' => 'height: 300px; width: 300px;' ] );
			}
		) );

		$f->addLayout( parsley_acf_column_definition(
			new FieldsBuilder( 'col_card', [
				'label'   => 'Card',
				'display' => 'block',
			] ),
			[
				'options_callback' => function ( $builder ) {
					$builder->addSelect( 'card_border_colour', [
						'label'         => 'Card border colour',
						'allow_null'    => 1,
						'choices'       => array(
							'primary' => 'primary',
							'secondary' => 'secondary',
							'tertiary' => 'tertiary',
							'quaternary' => 'quaternary',
							'light' => 'light',
							'dark' => 'dark',
							'success' => 'success',
							'danger' => 'danger',
							'warning' => 'warning',
							'info' => 'info',
							'white' => 'white',
						),
						'default_value' => false,
						'return_format' => 'value',
					] );
					$builder->addText( 'card_id', [
						'label'         => 'ID',
					] );
					$builder->addText( 'card_class', [
						'label'         => 'Additional classes',
					] );
				}
			],
			function ( $builder ) {
				$builder->addTab( 'Header' );
				_parsley_acf_heading( $builder, 'header_title', 'Header Title', 'h3' );
				$builder->addWysiwyg( 'header_content', [
					'label'         => 'Content',
					'tabs'          => 'all',
					'toolbar'       => 'full',
					'media_upload'  => 1,
				]);
				_parsley_acf_style( $builder, 'header_style' );
				$builder->addTab( 'Body' );
				$builder->addWysiwyg( 'body_content', [
					'label'         => 'Content',
					'tabs'          => 'all',
					'toolbar'       => 'full',
					'media_upload'  => 1,
				]);
				_parsley_acf_style( $builder, 'body_style' );
				$builder->addTab( 'Footer' );
				$builder->addWysiwyg( 'footer_content', [
					'label'         => 'Content',
					'tabs'          => 'all',
					'toolbar'       => 'full',
					'media_upload'  => 1,
				]);
				_parsley_acf_style( $builder, 'footer_style' );
			}
		) );

		$f->addLayout( parsley_acf_column_definition(
			new FieldsBuilder( 'col_listg', [
				'label'   => 'List Group',
				'display' => 'block',
			] ),
			[],
			function ( $builder ) {
				$builder->addTab( 'List Items' );
				$r = $builder->addRepeater( 'item', [
					'label'            => 'Items',
					'required'         => 1,
					'min'              => 1,
					'max'              => 0,
					'layout'           => 'table',
					'button_label'     => 'Add Item',
				] );
				$r->addText('html', [ 'wrapper' => [ 'width' => '50', 'class' => '', 'id' => '' ] ]);
				$r->addSelect( 'nugget', [
					'allow_null'    => 1,
					'choices'       => array(
						'fa-check' => 'tick',
						'fa-times' => 'cross',
						'icon'     => 'icon',
						'text'     => 'text',
					),
					'default_value' => false,
					'return_format' => 'value',
				]);
				$r->addText('nugget_detail')->conditional('nugget', '==', 'icon')->or('nugget', '==', 'text');
				$r->addText('class', [ 'wrapper' => [ 'width' => '25', 'class' => '', 'id' => '' ] ]);
				$r->endRepeater();
				$builder->addText( 'lg_item_class', [
					'label'         => 'Default item classes',
				] );
				_parsley_acf_style( $builder, 'lg_style' );
			}
		) );

		$f->addLayout( parsley_acf_column_definition(
			new FieldsBuilder( 'col_listgcard', [
				'label'   => 'List Group + Card',
				'display' => 'block',
			] ),
			[
				'options_callback' => function ( $builder ) {
					$builder->addSelect( 'card_border_colour', [
						'label'         => 'Card border colour',
						'allow_null'    => 1,
						'choices'       => array(
							'primary' => 'primary',
							'secondary' => 'secondary',
							'tertiary' => 'tertiary',
							'quaternary' => 'quaternary',
							'light' => 'light',
							'dark' => 'dark',
							'success' => 'success',
							'danger' => 'danger',
							'warning' => 'warning',
							'info' => 'info',
							'white' => 'white',
						),
						'default_value' => false,
						'return_format' => 'value',
					] );
					$builder->addText( 'card_id', [
						'label'         => 'ID',
					] );
					$builder->addText( 'card_class', [
						'label'         => 'Additional classes',
					] );
				}
			],
			function ( $builder ) {
				$builder->addTab( 'Header' );
				_parsley_acf_heading( $builder, 'header_title', 'Header Title', 'h3' );
				$builder->addWysiwyg( 'header_content', [
					'label'         => 'Content',
					'tabs'          => 'all',
					'toolbar'       => 'full',
					'media_upload'  => 1,
				]);
				_parsley_acf_style( $builder, 'header_style' );
				$builder->addTab( 'List Items' );
				$r = $builder->addRepeater( 'item', [
					'label'            => 'Items',
					'required'         => 1,
					'min'              => 1,
					'max'              => 0,
					'layout'           => 'table',
					'button_label'     => 'Add Item',					
				] );
				$r->addText('html', [ 'wrapper' => [ 'width' => '50', 'class' => '', 'id' => '' ] ]);
				$r->addSelect( 'nugget', [
					'allow_null'    => 1,
					'choices'       => array(
						'fa-check' => 'tick',
						'fa-times' => 'cross',
						'icon'     => 'icon',
						'text'     => 'text',
					),
					'default_value' => false,
					'return_format' => 'value',
				]);
				$r->addText('nugget_detail')->conditional('nugget', '==', 'icon')->or('nugget', '==', 'text');
				$r->addText('class', [ 'wrapper' => [ 'width' => '25', 'class' => '', 'id' => '' ] ]);
				$r->endRepeater();
				$builder->addText( 'lg_item_class', [
					'label'         => 'Default item classes',
				] );
				_parsley_acf_style( $builder, 'lg_style' );
				$builder->addTab( 'Footer' );
				$builder->addWysiwyg( 'footer_content', [
					'label'         => 'Content',
					'tabs'          => 'all',
					'toolbar'       => 'full',
					'media_upload'  => 1,
				]);
				_parsley_acf_style( $builder, 'footer_style' );
			}
		) );

		$f->addLayout( new FieldsBuilder( 'col_break', [
			'label'   => 'Row Break',
			'display' => 'block',
		] ) );

		$f->endFlexibleContent();

		$builder->addTab( 'Extras' );

		$builder->addWysiwyg( 'before_columns', [
			'label'         => 'Before Columns',
			'tabs'          => 'all',
			'toolbar'       => 'full',
			'media_upload'  => 1,
		] );

		$builder->addWysiwyg( 'after_columns', [
			'label'         => 'After Columns',
			'tabs'          => 'all',
			'toolbar'       => 'full',
			'media_upload'  => 1,
		] );
	}
);

$SEC[] = parsley_acf_section_definition(
	new FieldsBuilder( 'foogallery', [
		'label'   => 'FooGallery',
		'display' => 'block',
	] ),
	[],
	function ( $builder ) {

		$builder->addTab( 'Gallery' );

		$builder->addNumber( 'gallery_id', [
			'label' => 'Gallery Id',
			'instructions' => 'Numeric identifier for gallery, found in FooGallery as part of the shortcode.',
			'required' => 1,
		] );

	}
);

$SEC[] = parsley_acf_section_definition(
	new FieldsBuilder( 'image', [
		'label'   => 'Image (Full-Width)',
		'display' => 'block',
	] ),
	[
		'vary_width' => false,
		'styling'    => false,
		'heading_level' => 'none',
	],
	function ( $builder ) {

		$builder->addTab( 'Image' );

		$builder->addImage( 'image', [
			'label'         => 'Image',
			'return_format' => 'id',
			'preview_size'  => 'medium',
			'required'      => 1,
		] );

		$builder->addText( 'img_id', [
			'label'         => 'Image ID',
			'instructions'  => 'HTML `id` attribute for styling and scripting (img tag)',
			'wrapper'       => [ 'width' => '50', 'class' => '', 'id' => '' ],
		] );

		$builder->addText( 'img_class', [
			'label'         => 'Image Classes',
			'instructions'  => 'HTML `class` attribute for styling and scripting (img tag)',
			'wrapper'       => [ 'width' => '50', 'class' => '', 'id' => '' ],
			'default_value' => 'w-100 h-auto',
		] );
	}
);

$SEC[] = parsley_acf_section_definition(
	new FieldsBuilder( 'tabs', [
		'label'   => 'Begin Tabs',
		'display' => 'block',
	] ),
	[
		'options_callback' => function ( $builder ) {
			$builder->addSelect( 'tab_type', [
				'label'         => 'Tab type',
				'allow_null'    => 0,
				'choices'       => array(
					'tab'        => 'Tabs',
					'pill'       => 'Pill (top)',
					'pill-left'  => 'Pill (left)',
					'pill-right' => 'Pill (right)',
					'accordion'  => 'Accordion',
					'slick'      => 'Slick',
				),
				'default_value' => 'tab',
				'return_format' => 'value',
				'wrapper'       => [ 'width' => '25', 'class' => '', 'id' => '' ],
			] );
			$builder->addText( 'pill_class', [
				'label'         => 'Tab wrapper class',
				'default_value' => 'col-sm-3',
				'wrapper'       => [ 'width' => '25', 'class' => '', 'id' => '' ],
			] )->conditional( 'tab_type', '==', 'pill-left' )->or( 'tab_type', '==', 'pill-right' );
			$builder->addText( 'content_class', [
				'label'         => 'Content wrapper class',
				'default_value' => 'col-sm-9',
				'wrapper'       => [ 'width' => '25', 'class' => '', 'id' => '' ],
			] )->conditional( 'tab_type', '==', 'pill-left' )->or( 'tab_type', '==', 'pill-right' );
			$builder->addText( 'accordion_class', [
				'label'         => 'Accordion wrapper class',
				'wrapper'       => [ 'width' => '25', 'class' => '', 'id' => '' ],
			] )->conditional( 'tab_type', '==', 'accordion' );
			$builder->addText( 'slick_options', [
				'label'         => 'Slick options (JS object)',
				'default_value' => '{ arrows: true, autoplay: false, autoplaySpeed: 5000  }',
				'wrapper'       => [ 'width' => '50', 'class' => '', 'id' => '' ],
			] )->conditional( 'tab_type', '==', 'slick' );
		}
	],
	function ( $builder ) {

		$builder->addTab( 'Content' );

		$builder->addWysiwyg( 'before_tabs', [
			'label'         => 'Before Tabs',
			'tabs'          => 'all',
			'toolbar'       => 'full',
			'media_upload'  => 1,
		] );

		$builder->addWysiwyg( 'after_tabs', [
			'label'         => 'After Tabs',
			'tabs'          => 'all',
			'toolbar'       => 'full',
			'media_upload'  => 1,
		] );
	}
);

$SEC[] = new FieldsBuilder( 'end_tabs', [
	'label'   => 'End Tabs',
	'display' => 'block',
] );


$SEC[] = parsley_acf_section_definition(
	new FieldsBuilder( 'columns', [
		'label'   => 'Columns [legacy]',
		'display' => 'block',
	] ),
	[
		'heading_callback' => function ( $builder ) {

			$builder->addTrueFalse( 'heading_in_column', [
				'label'         => 'Heading in First Column',
				'instructions'  => 'Inserts the heading into the first column instead of before the columns.',
				'ui'            => 1,
				'ui_on_text'    => 'Yes',
				'ui_off_text'   => 'No',
			] )->conditional( 'heading', '!=empty', '' );
			
		},
	],
	function ( $builder ) {

		$builder->addTab( 'Columns' );

		$r = $builder->addRepeater( 'columns', [
			'label'         => 'Columns',
			'required'      => 1,
			'min'           => 1,
			'max'           => 0,
			'layout'        => 'table',
			'button_label'  => 'Add Column',
		] );

		$r->addWysiwyg( 'content', [
			'label'         => 'Content',
			'tabs'          => 'all',
			'toolbar'       => 'full',
			'media_upload'  => 1,
			'wrapper'       => [ 'width' => '75', 'class' => '', 'id' => '' ],
		]);

		$g = $r->addGroup( 'options', [
			'label'         => 'Options',
			'layout'        => 'block',
		] );

		$g->addText( 'classes', [
			'label'         => 'Classes',
			'instructions'  => 'Classes to apply to the column; requires knowledge of the Bootstrap grid system.',
			'default_value' => 'col',
		] );

		$g->addTrueFalse( 'exact_html', [
			'label'         => 'Exact HTML',
			'instructions'  => 'If exact HTML, will avoid Wordpress paragraph munging.',
			'default_value' => 0,
			'ui'            => 1,
			'ui_on_text'    => 'Exact',
			'ui_off_text'   => 'Munge',
		] );

		$g->endGroup();

		$r->endRepeater();

		$builder->addTab( 'Extras' );

		$builder->addWysiwyg( 'before_columns', [
			'label'         => 'Before Columns',
			'tabs'          => 'all',
			'toolbar'       => 'full',
			'media_upload'  => 1,
		] );

		$builder->addWysiwyg( 'after_columns', [
			'label'         => 'After Columns',
			'tabs'          => 'all',
			'toolbar'       => 'full',
			'media_upload'  => 1,
		] );
	}
);

$TOP = new FieldsBuilder( 'page_sections', [
	'position'      => 'normal',
	'style'         => 'seamless',
	'menu_order'    => 40,
	'title'         => 'Advanced Page Layout',
] );

$TOP->setLocation( 'post_type', '==', 'page' )->or( 'options_page', '==', 'parsley-sections' );

$l = $TOP->addFlexibleContent( 'design_sections', [
	'title'         => 'Design Sections',
	'instructions'  => 'Sections of content to display instead of main page content.',
	'button_label'  => 'Add Section',
	'min'           => 0,
	'max'           => 50,
] );
foreach ( $SEC as $s ) {
	$l->addLayout($s);
}
$l->endFlexibleContent();

acf_add_local_field_group( $TOP->build() );

function parsley_acf_get_label ( $title, $field, $layout, $i ) {
  if ( $layout['name'] == 'primary_content' ) {
    return $title;
  }
  if ( $layout['name'] == 'col_break' || $layout['name'] == 'end_tabs' ) {
    return "<i>$title</i>";
  }
  if ( $heading = get_sub_field('heading') ) {
    $title = "<b>$heading</b> [$title]";
  }
  elseif ( $heading = get_sub_field('header_title') ) {
    $title = "<b>$heading</b> [$title]";
  }
  elseif ( $image = get_sub_field('image') ) {
    $data = wp_get_attachment_metadata( $image );
    $title =  '<b>' . $data['file'] . "</b> [$title]";
  }
  elseif ( $content = get_sub_field('content') ) {
    $summary = htmlspecialchars( substr($content, 0, 100) );
    $title = "$title: <small style='color:#999'>$summary</small>";
  }
  return $title;
}

add_filter( 'acf/fields/flexible_content/layout_title', function ( $title, $field, $layout, $i ) {
  $got = parsley_acf_get_label( $title, $field, $layout, $i );
  if ( $got ) {
    return $got;
  }
  return $title;
}, 10, 4 );
