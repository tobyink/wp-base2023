<?php

global $parsley_help_shortcodes;

$parsley_help_shortcodes['common_section'] = <<<'HELP'
<p>Inserts a section from the theme's common sections or from another page.</p>
<h4>Attributes</h4>
<p><code>id</code> section ID or number.</p>
<p><code>post</code> post ID; defaults to common sections</p>
<p><code>container</code> override section container class; defaults to "wide".</p>
HELP;

add_shortcode( 'common_section', function ( $atts, $content='' ) {

  static $count = 1000;

  $atts = shortcode_atts( [
    'id'        => null,
    'post'      => null,
    'container' => null,
  ], $atts, 'common_section');

  $id      = 1;
  $source  = 'option';
  $post_id = get_the_ID();
  if ( ! empty($atts['post']) ) {
    $source  = $atts['post'];
    $post_id = $atts['post'];
  }
  if ( ! empty($atts['id']) ) {
    $id = $atts['id'];
  }

  $found    = false;
  $sections = \App\parsley_get_sections_data( $source );
  foreach ( $sections as $s ) {
    if ( $s['id'] == $id ) {
      $found = $s;
    }
    elseif ( $s['real_index'] == $id ) {
      $found = $s;
    }
    elseif ( count($s['tabs']) ) {
      foreach ( $s['tabs'] as $s2 ) {
        if ( $s2['id'] == $id )              { $found = $s2; }
        elseif ( $s2['real_index'] == $id )  { $found = $s2; }
      }
    }

    if ( $found ) break;
  }

  if ( ! $found ) {
    return '<!-- error loading external section -->';
  }

  if ( empty($atts['container']) ) {
    $found['full_width'] = 'wide';
  }
  else {
    $found['full_width'] = $atts['container'];
  }

  return \App\parsley_render_section ( $post_id, ++$count, $found );
} );
