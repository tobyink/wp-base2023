<?php

global $parsley_help_shortcodes;

$parsley_help_shortcodes['alert'] = <<<'HELP'
<p>Shows an alert.</p>
<p>If you include a link in your alert text, include <code>class="alert-link"</code>.</p>
<h4>Examples</h4>
<pre><code style="display:block;padding:1rem">[alert warning]Your text here.[/alert]</code></pre>
<pre><code style="display:block;padding:1rem">[alert success icon=check tag=div]Everything OK![/alert]</code></pre>
<h4>Attributes</h4>
<p><code>class</code> is a list of classes, separated by spaces.</p>
<p><code>icon</code> is fontawesome icon.</p>
<p><code>tag</code> sets the HTML tag name for the alert, defaulting to <code>p</code>.</p>
<p><code>success</code>, <code>warning</code>, <code>danger</code>, <code>info</code>, and other theme colours may be used to indicate the alert type. Defaults to <code>info</code>.</p>
HELP;

add_shortcode( 'alert', function ( $arg, $content='Attention!' ) {
  $atts = [];
  foreach ( $arg as $k => $v ) {
    if ( is_numeric($k) ) { $atts[strtolower($v)] = true; }
    else                  { $atts[strtolower($k)] = $v;   }
  }

  $content = do_shortcode( $content );

  $tag = array_key_exists( 'tag', $atts ) ? $atts['tag'] : 'p';

  $class = array_key_exists( 'class', $atts ) ? $atts['class'] : '';
  $class .= ' alert';

  $found = false;
  foreach ( App\theme_colours() as $colour ) {
    if ( array_key_exists( $colour, $atts ) && $atts[$colour] ) {
      $class .= " alert-$colour";
      $found = true;
      break;
    }
  }
  if ( ! $found ) {
    $class .= ' alert-info';
  }

  if ( array_key_exists( 'icon', $atts ) && strlen( $atts['icon'] ) ) {
    $class .= ' alert-with-icon';
    $content = sprintf( '<i class="float-end hvr-icon fa fa-%s"></i> %s', $atts['icon'], $content );
  }

  return sprintf( '<%s class="%s" role="alert">%s</%s>', $tag, trim($class), $content, $tag );
} );
