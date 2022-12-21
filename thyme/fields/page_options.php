<?php

namespace App;

use StoutLogic\AcfBuilder\FieldsBuilder;

$page = new FieldsBuilder( 'page_options', [ 'position' => 'side' ] );

$page
    ->setLocation('post_type', '==', 'post')
    ->or('post_type', '==', 'page');

$page
    ->addTrueFalse('hide_header', [
        'label' => 'Hide Header',
        'ui' => 1,
        'ui_on_text' => 'Hidden',
        'ui_off_text' => 'Normal',
    ]);

$page
    ->addImage('fullwidth_banner', [
        'label' => 'Banner Image',
        'return_format' => 'array',
    ]);

$page
    ->addTrueFalse('hide_breadcrumbs', [
        'label' => 'Hide Breadcrumbs',
        'ui' => 1,
        'ui_on_text' => 'Hidden',
        'ui_off_text' => 'Normal',
    ]);

$page
    ->addTrueFalse('hide_title', [
        'label' => 'Hide Title',
        'ui' => 1,
        'ui_on_text' => 'Hidden',
        'ui_off_text' => 'Normal',
    ]);

$page
    ->addTrueFalse('hide_sidebar', [
        'label' => 'Hide Sidebar',
        'ui' => 1,
        'ui_on_text' => 'Hidden',
        'ui_off_text' => 'Normal',
    ]);

$page
    ->addTrueFalse('disable_wpautop', [
        'label' => 'Exact HTML',
        'ui' => 1,
        'ui_on_text' => 'Enabled',
        'ui_off_text' => 'Disabled',
    ]);

$page
    ->addTrueFalse('hide_footer', [
        'label' => 'Hide Footer',
        'ui' => 1,
        'ui_on_text' => 'Hidden',
        'ui_off_text' => 'Normal',
    ]);

acf_add_local_field_group( $page->build() );

$page = new FieldsBuilder( 'page_customizations', [ 'style' => 'seamless', 'menu_order' => 50 ] );

$page
    ->setLocation('post_type', '==', 'post')
    ->or('post_type', '==', 'page');

$page
    ->addTab('notes', [
        'label' => 'Notes',
    ]);

$page
    ->addTextarea('administrator_notes', [
        'label' => 'Admin Notes',
    ]);

$page
    ->addTab('style_and_script', [
        'label' => 'Style & Script',
    ]);

$page
    ->addTextarea('custom_javascript', [
        'label' => 'Custom Javascript',
    ]);

$page
    ->addTextarea('custom_css', [
        'label' => 'Custom CSS',
    ]);

$page
    ->addTab('meta', [
        'label' => 'Metadata',
    ]);

$page
    ->addText('meta_description', [
        'label' => 'Description',
    ]);

$page
    ->addText('meta_keywords', [
        'label' => 'Keywords',
    ]);

$page
    ->addTrueFalse('meta_image', [
        'label' => 'Image',
	'default_value' => 1,
    ]);

$page
    ->addImage('meta_image_override', [
        'label' => 'Override Featued Image',
    ])->conditional('meta_image', '==', 1);

$page
    ->addTextarea('extra_meta_tags');

acf_add_local_field_group( $page->build() );
