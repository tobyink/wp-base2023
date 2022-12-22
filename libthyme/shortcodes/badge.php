<?php

global $parsley_help_shortcodes;

$parsley_help_shortcodes['badge'] = <<<'HELP'
<p>Shows a small badge.</p>
<h4>Examples</h4>
<pre><code style="display:block;padding:1rem">[badge]123[/badge]</code></pre>
<pre><code style="display:block;padding:1rem">[badge success pill tag=strong icon=check]OK[/badge]</code></pre>
<h4>Attributes</h4>
<p><code>class</code> is a list of classes, separated by spaces.</p>
<p><code>icon</code> is fontawesome icon.</p>
<p><code>tag</code> sets the HTML tag name for the badge, defaulting to <code>span</code>.</p>
<p><code>success</code>, <code>warning</code>, <code>danger</code>, <code>info</code>, and other theme colours may be used to indicate the badge type. Defaults to <code>primary</code>.</p>
<p><code>pill</code> makes the badge have rounded ends.</p>
HELP;

add_shortcode( 'badge', function ( $arg, $content='0' ) {
  $atts = [];
  foreach ( $arg as $k => $v ) {
    if ( is_numeric($k) ) { $atts[strtolower($v)] = true; }
    else                  { $atts[strtolower($k)] = $v;   }
  }

  $tag = array_key_exists( 'tag', $atts ) ? $atts['tag'] : 'span';

  $class = array_key_exists( 'class', $atts ) ? $atts['class'] : '';
  $class .= ' badge';

  $found = false;
  foreach ( App\theme_colours() as $colour ) {
    if ( array_key_exists( $colour, $atts ) && $atts[$colour] ) {
      $class .= " bg-$colour";
      $found = true;
      break;
    }
  }
  if ( ! $found ) {
    $class .= ' bg-primary';
  }

  if ( array_key_exists( 'pill', $atts ) && $atts['pill'] ) {
    $class .= ' badge-pill';
  }

  if ( array_key_exists( 'icon', $atts ) && strlen( $atts['icon'] ) ) {
    $class .= ' badge-with-icon';
    $content = sprintf( '<i class="hvr-icon fa fa-%s"></i> %s', $atts['icon'], $content );
  }

  return sprintf( '<%s class="%s">%s</%s>', $tag, trim($class), $content, $tag );
} );
