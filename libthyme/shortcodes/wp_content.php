<?php

global $parsley_help_shortcodes;

$parsley_help_shortcodes['wp_content'] = <<<'HELP'
<p>Inserts the body content. Don't use this in body content to avoid an infinite loop.</p>
<h4>Attributes</h4>
<p><code>id</code> can be set to a post id; defaults to the current post.</p>
<p><code>wpautop</code> can be set to 0 to disable wpautop; defaults to 1.</p>
HELP;

add_shortcode( 'wp_content', function ( $atts, $content='' ) {
  $atts = shortcode_atts( [
    'id'      => null,
    'wpautop' => 1,
  ], $atts, 'wp_content');

  $id   = $atts['id'] ? $atts['id'] : get_the_ID();
  $post = get_post( $id );

  $content = do_shortcode( $post->post_content );
  
  if ( $atts['wpautop'] ) {
    $content = wpautop( $content );
  }

  return $content;
} );
