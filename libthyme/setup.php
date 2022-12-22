<?php

/**
 * Initialize ACF Builder
 */
add_action('init', function () {
    collect(glob(__DIR__.'/fields/'.'*.php' ))->map(function ($field) {
        return require_once($field);
    })->map(function ($field) {
        if ($field instanceof FieldsBuilder) {
            acf_add_local_field_group($field->build());
        }
    });
});

/**
 * Initialize Shortcodes
 */
add_action('init', function () {
    collect(glob(__DIR__.'/shortcodes/'.'*.php'))->map(function ($field) {
        return require_once($field);
    });
});

require_once( __DIR__ . '/filters.php' );
require_once( __DIR__ . '/helpers.php' );
require_once( __DIR__ . '/options.php' );
require_once( __DIR__ . '/meta.php' );
require_once( __DIR__ . '/acf-tweaks.php' );
require_once( __DIR__ . '/page-sections.php' );
require_once( __DIR__ . '/floating-icons.php' );
require_once( __DIR__ . '/pagination.php' );
require_once( __DIR__ . '/help-text.php' );
