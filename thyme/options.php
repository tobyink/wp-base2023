<?php

namespace App;
use StoutLogic\AcfBuilder\FieldsBuilder;

function theme_colours () {
	return [ 'primary', 'secondary', 'tertiary', 'quaternary', 'dark', 'light', 'success', 'danger', 'warning', 'info' ];
}

function theme_get_option ( $id ) {
	$id = str_replace( '-', '_', $id );
	return get_field( 'parsley_' . $id, 'option' );
}

function theme_write_sass () {
	$sass = [];

	$urls = theme_get_option( 'font-imports' );
	foreach ( explode( "\n", $urls ) as $l ) {
		if ( preg_match( '/^http/', $l ) ) {
			$sass []= sprintf( '@import url(%s);', str_replace( ';', '%3B', trim($l) ) );
		}
	}

	foreach ( [ 'serif', 'sans', 'monospace', 'display' ] as $f ) {
		$sass []= sprintf( '$wp-theme-font-%s: %s;', $f, theme_get_option( "font-$f" ) );
	}

	foreach ( [ 'base', 'headings' ] as $f ) {
		$sass []= sprintf('$wp-theme-font-%s: $wp-theme-font-%s;', $f, theme_get_option( "font-$f" ) );
	}

	foreach ( theme_colours() as $c ) {
		$sass [] = sprintf( '$wp-theme-colour-%s: %s;', $c, theme_get_option( "colour-$c" ) );
	}

	foreach ( [
			'page-bg', 'page-text', 'page-link', 'page-muted',
			'header-bg', 'header-text', 'header-hover', 'header-dropdown-bg', 'header-dropdown-text',
			'floating-icons-bg', 'floating-icons-text', 'floating-icons-hover', 'floating-icons-class',
		] as $opt ) {
		$sass [] = sprintf( '$wp-theme-%s: %s;', $opt, theme_get_option( $opt ) );
	}

	$final = '';
	foreach ( $sass as $l ) {
		$final .= "$l\n";
	}

	$file = __DIR__ . '/../resources/assets/styles/common/_wp_theme.scss';

	$orig = file_get_contents( $file );

	if ( $final != $orig ) {
		file_put_contents( $file, $final );
		system( "chmod g+rwX '$file'" );
		return true; // SASS was changed
	}

	// SASS is unchanged, so doesn't need recompiling
	return false;
}

function theme_compile_sass () {
	$olddir = getcwd();
	chdir( __DIR__ . '/..'  );
	system( './yarn build' );
	system( 'chmod -R g+rwX ./dist' );
	chdir( $olddir );
}

add_action( 'acf/save_post', function () {
	$screen = get_current_screen();
	if ( strpos( $screen->id, "parsley-" ) == true ) {
		if ( theme_write_sass() ) {
			theme_compile_sass();
		}
	}
}, 99 );

add_action( 'acf/init', function () {

	acf_add_options_page( [
		'page_title' => 'Theme Options',
		'menu_slug'  => 'parsley-options',
		'capability' => 'edit_theme_options',
		'position'   => '61',
		'autoload'   => true,
		'icon_url'   => 'dashicons-admin-generic',
	] );

	acf_add_options_page( [
		'page_title' => 'Sections',
		'menu_slug'  => 'parsley-sections',
		'capability' => 'edit_theme_options',
		'position'   => 61,
		'autoload'   => true,
		'icon_url'   => 'dashicons-table-row-before',
	] );

	$opts = new FieldsBuilder( 'colours', [ 'menu_order' => 10 ] );
	$opts->setLocation( 'options_page', '==', 'parsley-options' );

	$colour_defaults = [
		'primary'     => '#007bff',
		'secondary'   => '#6c757d',
		'tertiary'    => '#fd7e14',
		'quaternary'  => '#20c997',
		'dark'        => '#343a40',
		'light'       => '#f8f9fa',
		'success'     => '#28a745',
		'danger'      => '#dc3545',
		'warning'     => '#ffc107',
		'info'        => '#17a2b8',
	];

	foreach ( $colour_defaults as $label => $default ) {
		$opts->addColorPicker( "parsley_colour_$label", [
			'label'          => ucfirst($label),
			'default_value'  => $default,
			'wrapper'        => [ 'width' => '50%', 'id' => '', 'class' => '' ],
		] );
	}

	$opts->addText( 'parsley_page_bg', [
		'label'          => 'Body Background Colour',
		'default_value'  => 'white',
		'wrapper'        => [ 'width' => '50%', 'id' => '', 'class' => '' ],
	] );

	$opts->addText( 'parsley_page_text', [
		'label'          => 'Text Colour',
		'default_value'  => '$wp-theme-colour-dark',
		'wrapper'        => [ 'width' => '50%', 'id' => '', 'class' => '' ],
	] );

	$opts->addText( 'parsley_page_link', [
		'label'          => 'Link Colour',
		'default_value'  => 'lighten($wp-theme-colour-primary, 10%)',
		'wrapper'        => [ 'width' => '50%', 'id' => '', 'class' => '' ],
	] );

	$opts->addText( 'parsley_page_muted', [
		'label'          => 'Muted Text Colour',
		'default_value'  => 'lighten($wp-theme-colour-dark, 30%)',
		'wrapper'        => [ 'width' => '50%', 'id' => '', 'class' => '' ],
	] );

	acf_add_local_field_group( $opts->build() );

	$opts = new FieldsBuilder( 'fonts', [ 'menu_order' => 20 ] );
	$opts->setLocation( 'options_page', '==', 'parsley-options' );

	$font_defaults = [
		'serif'     => "Georgia, 'Times New Roman', Times, serif",
		'sans'      => "'Helvetica Neue', Helvetica, Arial, sans-serif",
		'monospace' => "Menlo, Monaco, Consolas, 'Courier New', monospace",
		'display'   => "Techno, Impact, sans-serif",
	];

	foreach ( $font_defaults as $label => $default ) {
		$opts->addText( "parsley_font_$label", [
			'label'          => ucfirst($label),
			'default_value'  => $default,
			'wrapper'        => [ 'width' => '50%', 'id' => '', 'class' => '' ],
		] );
	}

	$opts->addSelect( 'parsley_font_base', [
		'label'          => 'Base font',
		'default_value'  => 'serif',
		'choices'        => array_keys( $font_defaults ),
		'wrapper'        => [ 'width' => '50%', 'id' => '', 'class' => '' ],
	] );

	$opts->addSelect( 'parsley_font_headings', [
		'label'          => 'Headings font',
		'default_value'  => 'display',
		'choices'        => array_keys( $font_defaults ),
		'wrapper'        => [ 'width' => '50%', 'id' => '', 'class' => '' ],
	] );

	$opts->addTextarea( 'parsley_font_imports', [
		'label'          => 'Import URLs',
	] );

	acf_add_local_field_group( $opts->build() );

	$opts = new FieldsBuilder( 'footer', [ 'menu_order' => 100, 'seamless' => 1 ] );
	$opts->setLocation( 'options_page', '==', 'parsley-sections' );

	$opts->addTrueFalse( 'parsley_scroll_to_top', [ 'label' => 'Show "Scroll to Top"', 'default_value' => true ] );

	$opts->addText( 'parsley_floating_icons_bg', [
		'label'          => 'Floating Menu Background Colour',
		'default_value'  => '$wp-theme-colour-primary',
		'wrapper'        => [ 'width' => '50%', 'id' => '', 'class' => '' ],
	] );

	$opts->addText( 'parsley_floating_icons_text', [
		'label'          => 'Floating Menu Foreground Colour',
		'default_value'  => 'white',
		'wrapper'        => [ 'width' => '50%', 'id' => '', 'class' => '' ],
	] );

	$opts->addText( 'parsley_floating_icons_hover', [
		'label'          => 'Floating Menu Hover Background Colour',
		'default_value'  => 'lighten($wp-theme-colour-primary, 10%)',
		'wrapper'        => [ 'width' => '50%', 'id' => '', 'class' => '' ],
	] );

	$opts->addSelect( 'parsley_floating_icons_class', [
		'label'          => 'Floating Menu Class',
		'default_value'  => 'floating-icons-left',
		'choices'        => [ 'floating-icons-left', 'floating-icons-right' ],
		'wrapper'        => [ 'width' => '50%', 'id' => '', 'class' => '' ],
	] );

	acf_add_local_field_group( $opts->build() );


	$opts = new FieldsBuilder( 'blog', [ 'menu_order' => 50 ] );
	$opts->setLocation( 'options_page', '==', 'parsley-options' );
	$opts->addSelect( 'parsley_blog_style', [ 'default_value' => 'text', 'choices' => [ 'text', 'thumbnails', 'grid' ] ] );
	acf_add_local_field_group( $opts->build() );

	$opts = new FieldsBuilder( 'header', [ 'menu_order' => 10 ] );
	$opts->setLocation( 'options_page', '==', 'parsley-sections' );
	$opts->addTextarea( 'parsley_header_html', [ 'label' => 'HTML Header', 'instructions' => 'Can use the <code>[common_section]</code> shortcode (and other shortcodes).' ] );
	$opts->addTrueFalse( 'parsley_header_menubar', [ 'label' => 'Show Menu Bar', 'default_value' => 1, 'wrapper' => [ 'width' => '50%', 'id' => '', 'class' => '' ] ] );
	$opts->addSelect( 'parsley_header_style', [ 'label' => 'Menu Bar Branding Style', 'wrapper' => [ 'width' => '50%', 'id' => '', 'class' => '' ], 'default_value' => 'text', 'choices' => [ 'text', 'image', 'both', 'none' ] ] )->conditional( 'parsley_header_menubar', '==', 1 );
	$opts->addImage( 'parsley_header_image', [ 'label' => 'Menu Bar Icon', 'wrapper' => [ 'width' => '50%', 'id' => '', 'class' => '' ], 'return_format' => 'id', 'instructions' => 'A maximum size of 220 by 60 pixels is recommended.' ] )->conditional( 'parsley_header_menubar', '==', 1 )->and( 'parsley_header_style', '!=', 'none' );
	$opts->addText( 'parsley_header_title', [ 'label' => 'Menu Bar Title', 'wrapper' => [ 'width' => '50%', 'id' => '', 'class' => '' ] ] )->conditional( 'parsley_header_menubar', '==', 1 )->and( 'parsley_header_style', '!=', 'none' );
	$opts->addText( 'parsley_header_bg', [ 'label' => 'Menu Bar Background Colour', 'wrapper' => [ 'width' => '50%', 'id' => '', 'class' => '' ], 'default_value' => '$wp-theme-colour-primary' ] )->conditional( 'parsley_header_menubar', '==', 1 );
	$opts->addText( 'parsley_header_text', [ 'label' => 'Menu Bar Foreground Colour', 'wrapper' => [ 'width' => '50%', 'id' => '', 'class' => '' ], 'default_value' => '$wp-theme-colour-light' ] )->conditional( 'parsley_header_menubar', '==', 1 );
	$opts->addText( 'parsley_header_hover', [ 'label' => 'Menu Bar Hover/Active Colour', 'wrapper' => [ 'width' => '50%', 'id' => '', 'class' => '' ], 'default_value' => 'white' ] )->conditional( 'parsley_header_menubar', '==', 1 );
	$opts->addText( 'parsley_header_menu_class', [ 'label' => 'Menu Extra Classes', 'wrapper' => [ 'width' => '50%', 'id' => '', 'class' => '' ], 'default_value' => 'ml-auto', 'instructions' => 'Recommended <code>ml-auto</code>' ] )->conditional( 'parsley_header_menubar', '==', 1 );
	$opts->addText( 'parsley_header_dropdown_bg', [ 'label' => 'Dropdown Background Colour', 'wrapper' => [ 'width' => '50%', 'id' => '', 'class' => '' ], 'default_value' => '$wp-theme-colour-secondary' ] )->conditional( 'parsley_header_menubar', '==', 1 );
	$opts->addText( 'parsley_header_dropdown_text', [ 'label' => 'Dropdown Foreground Colour', 'wrapper' => [ 'width' => '50%', 'id' => '', 'class' => '' ], 'default_value' => '$wp-theme-colour-light' ] )->conditional( 'parsley_header_menubar', '==', 1 );
	acf_add_local_field_group( $opts->build() );
} );
