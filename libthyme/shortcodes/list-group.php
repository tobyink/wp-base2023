<?php

global $parsley_help_shortcodes;

$parsley_help_shortcodes['list-group'] = <<<'HELP'
<p>Shows a list group. (Like a one-column table.)</p>
<p>Note you need to use a blank line to separate list items.</p>
<p>This shortcode can be used for simple to moderately complex list groups. For anything complex, you may need to
use <a href="https://getbootstrap.com/docs/4.5/components/list-group/">Bootstrap's list-group classes</a> in HTML.</p>
<h4>Examples</h4>
<pre><code style="display:block;padding:1rem">[list-group]
Foo

Bar

Baz
[/list-group]</code></pre>
<pre><code style="display:block;padding:1rem">[list-group primary
 title="Favourite Fruits"
 header="Survey results"]
Banana [badge]8 votes[/badge]

Apple [badge]4 votes[/badge]

Grapes [badge]5 votes[/badge]

Strawberry [badge]9 votes[/badge] [badge success icon=shield]winner[/badge]
[/list-group]</code></pre>
<h4>Attributes</h4>
<p><code>id</code> is an id for styling or scripting the list-group. If there's a title, header, or footer, sets the id for the wrapper div instead, and <code>list-group-id</code> separately sets the id for the list-group itself.</p>
<p><code>class</code> is a list of classes, separated by spaces. Similarly to <code>id</code>, may be applied to the wrapper instead, with <code>list-group-class</code> being applied to the list-group itself in these cases.</p>
<p><code>item-class</code> is a list of classes to be applied to every item in the list-group, separated by spaces.</p>
<p><code>header</code> text to display above the list group. <code>header-tag</code> and <code>header-class</code> can be used to tweak it.</p>
<p><code>title</code> title text to display within a header. <code>title-tag</code> and <code>title-class</code> can be used to tweak it.</p>
<p><code>icon</code> is fontawesome icon; ignored unless there's a title or header.</p>
<p><code>footer</code> text to display below the list group. <code>footer-tag</code> and <code>footer-class</code> can be used to tweak it.</p>
<p><code>tag</code> sets the HTML tag name for the wrapper surrounding the list; ignored unless there's a title, header, or footer; defaults to div.</p>
HELP;

$parsley_help_shortcodes['card'] = <<<'HELP'
<p>Shows a card. (A text box with optional header and footer.)</p>
<p>This shortcode can be used for simple to moderately complex list groups. For anything complex, you may need to
use <a href="https://getbootstrap.com/docs/4.5/components/card/">Bootstrap's card classes</a> in HTML.</p>
<h4>Examples</h4>
<pre><code style="display:block;padding:1rem">[card title="Greetings"]Hello world[/card]</code></pre>
<pre><code style="display:block;padding:1rem">[card secondary
 title="Greetings" title-tag="h4" title-class="m-0"
 icon="hand-spock-o"
 footer="from the aliens"]
    Hello world.
[/card]</code></pre>
<h4>Attributes</h4>
<p><code>id</code> is an id for styling or scripting the card.</p>
<p><code>class</code> is a list of classes, separated by spaces.</p>
<p><code>header</code> text to display at the top of the card. <code>header-tag</code> and <code>header-class</code> can be used to tweak it.</p>
<p><code>title</code> title text to display within a header. <code>title-tag</code> and <code>title-class</code> can be used to tweak it.</p>
<p><code>icon</code> is fontawesome icon; ignored unless there's a title or header.</p>
<p><code>footer</code> text to display at the base of the card. <code>footer-tag</code> and <code>footer-class</code> can be used to tweak it.</p>
<p><code>tag</code> sets the HTML tag name for the card; defaults to div.</p>
<p><code>body</code> sets the HTML tag name for the card body; defaults to div.</p>
<p><code>body-class</code> sets additional classes for the card body</p>
HELP;

add_shortcode( 'list-group', function ( $arg, $content='' ) {
  $atts = [];
  foreach ( $arg as $k => $v ) {
    if ( is_numeric($k) ) { $atts[strtolower($v)] = true; }
    else                  { $atts[strtolower($k)] = $v;   }
  }

  $items = preg_split( '/\n\n+/', preg_replace( '/\R/u', "\n", trim($content) ) );

  $is_complex = false;
  foreach ( [ 'title', 'header', 'footer' ] as $complex_key ) {
    if ( array_key_exists( $complex_key, $atts ) ) {
      $is_complex = true;
    }
  }

  if ( $is_complex ) {
    $atts['list-group-items'] = $items;
    return parsley_shortcode_bs_card( $atts );
  }
  else {
    $atts['items'] = $items;
    return parsley_simple_list_group( $atts );
  }
} );

function parsley_simple_list_group ( $atts ) {
  $ul_class = 'list-group';
  $li_class = 'list-group-item';

  if ( array_key_exists( 'class', $atts ) ) {
    $ul_class .= ' ' . $atts['class'];
  }
  if ( array_key_exists( 'list-group-class', $atts ) ) {
    $ul_class .= ' ' . $atts['list-group-class'];
  }

  if ( array_key_exists( 'item-class', $atts ) ) {
    $li_class .= ' ' . $atts['item-class'];
  }

  foreach ( App\theme_colours() as $colour ) {
    if ( array_key_exists( $colour, $atts ) && $atts[$colour] ) {
      $li_class .= " list-group-item-$colour";
      $found = true;
      break;
    }
  }

  $ul_attrs = '';
  if ( array_key_exists( 'list-group-id', $atts) ) {
    $ul_attrs .= sprintf( ' id="%s"', htmlspecialchars($atts['list-group-id']) );
  }
  elseif ( array_key_exists( 'id', $atts) ) {
    $ul_attrs .= sprintf( ' id="%s"', htmlspecialchars($atts['id']) );
  }

  $html = sprintf( '<ul class="%s"%s>', htmlspecialchars($ul_class), $ul_attrs );
  foreach ( $atts['items'] as $i ) {
    $html .= sprintf( '<li class="%s">%s</li>', htmlspecialchars($li_class), do_shortcode( nl2br( $i ) ) );
  }
  $html .= '</ul>';
  return $html;
}

add_shortcode( 'card', 'parsley_shortcode_bs_card' );
function parsley_shortcode_bs_card ( $arg, $content='' ) {
  $atts = [];
  $child_atts = [];
  foreach ( $arg as $k => $v ) {
    if ( is_numeric($k) ) { $atts[strtolower($v)] = true; }
    else                  { $atts[strtolower($k)] = $v;   }
  }

  $defaults = [
    'tag'            => 'div',
    'header-tag'     => 'div',
    'title-tag'      => 'h2',
    'body-tag'       => 'div',
    'footer-tag'     => 'div',
  ];
  if ( array_key_exists( 'tag', $atts ) && in_array( $atts['tag'], [ 'section', 'aside', 'article' ] ) ) {
    $defaults['header-tag'] = 'header';
    $defaults['footer-tag'] = 'footer';
  }
  foreach ( $defaults as $k => $v ) {
    if ( ! array_key_exists( $k, $atts ) ) {
      $atts[$k] = $v;
    }
  }

  $card_attrs = '';
  if ( array_key_exists( 'id', $atts) ) {
    $card_attrs .= sprintf( ' id="%s"', htmlspecialchars($atts['id']) );
  }

  $theme_colour = false;
  foreach ( App\theme_colours() as $colour ) {
    if ( array_key_exists( $colour, $atts ) && $atts[$colour] ) {
      $theme_colour = $colour;
      break;
    }
  }

  $html = '';

  $card_class = 'card';
  if ( $theme_colour ) {
    $card_class .= " border-$theme_colour";
  }
  if ( array_key_exists( 'class', $atts ) ) {
    $card_class .= ' ' . $atts['class'];
  }

  $html .= sprintf( '<%s class="%s"%s>', $atts['tag'], $card_class, $card_attrs );

  if ( array_key_exists( 'header', $atts ) || array_key_exists( 'title', $atts ) ) {
    $header_class = 'card-header';
    $title_class  = 'card-title';

    if ( $theme_colour == 'light' ) {
      $header_class .= " bg-light text-dark";
    }
    else {
      $header_class .= " bg-$theme_colour text-white";
    }
    if ( array_key_exists( 'header-class', $atts ) ) {
      $header_class .= ' ' . $atts['header-class'];
    }
    if ( array_key_exists( 'title-class', $atts ) ) {
      $title_class .= ' ' . $atts['title-class'];
    }

    $icon = '';
    if ( array_key_exists( 'icon', $atts ) ) {
      $icon = sprintf( '<i class="float-end hvr-icon fa fa-%s"></i>', $atts['icon'] );
    }

    $html .= sprintf( '<%s class="%s">', $atts['header-tag'], $header_class );
    if ( array_key_exists( 'title', $atts ) && ! empty($atts['title']) ) {
      $html .= sprintf( '<%s class="%s">', $atts['title-tag'], $title_class );
      $html .= $icon;
      $html .= htmlspecialchars( $atts['title'] );
      $html .= sprintf( '</%s>', $atts['title-tag'] );
    }
    else {
      $html .= $icon;
    }
    $html .= htmlspecialchars( $atts['header'] );
    $html .= sprintf( '</%s>', $atts['header-tag'] );
  }

  if ( ! empty($content) ) {
    $body_class = 'card-body';
    if ( array_key_exists( 'body-class', $atts ) ) {
      $body_class .= ' ' . $atts['body-class'];
    }

    $html .= sprintf( '<%s class="%s">', $atts['body-tag'], $body_class );
    $html .= wpautop( do_shortcode( $content ) );
    $html .= sprintf( '</%s>', $atts['body-tag'] );
  }

  if ( array_key_exists( 'list-group-items', $atts ) ) {
    $mapping = [
      'list-group-id'         => 'id',
      'list-group-class'      => 'class',
      'item-class'            => 'item-class',
      'list-group-items'      => 'items',
    ];
    $lg_atts = [];
    foreach ( $mapping as $long => $short ) {
      if ( array_key_exists( $long, $atts ) ) {
        $lg_atts[$short] = $atts[$long];
      }
    }
    if ( $theme_colour ) {
      $lg_atts[$theme_colour] = true;
    }
    if ( ! array_key_exists( 'class', $lg_atts ) ) {
      $lg_atts['class'] = '';
    }
    $lg_atts['class'] .= ' list-group-flush';
    $html .= parsley_simple_list_group( $lg_atts );
  }

  if ( array_key_exists( 'footer', $atts ) ) {
    $footer_class = 'card-footer';
    if ( $theme_colour == 'light' ) {
      $footer_class .= " bg-light text-dark";
    }
    else {
      $footer_class .= " bg-$theme_colour text-white";
    }
    if ( array_key_exists( 'footer-class', $atts ) ) {
      $footer_class .= ' ' . $atts['footer-class'];
    }

    $html .= sprintf( '<%s class="%s">', $atts['footer-tag'], $footer_class );
    $html .= htmlspecialchars($atts['footer']);
    $html .= sprintf( '</%s>', $atts['footer-tag'] );
  }


  $html .= sprintf( '</%s>', $atts['tag'] );
  return $html;
}

