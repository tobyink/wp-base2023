<?php

class FloatingIconsWalker extends Walker_Nav_Menu {
  function start_lvl ( &$output, $depth = 0, $args = array() ) {
    $output .= "<ul>";
  }
  function end_lvl ( &$output, $depth = 0, $args = array() ) {
    $output .= "</ul>";
  }
  public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
    $output .= sprintf(
      "<li><a href=\"%s\" target=\"%s\" title=\"%s\" data-bs-content=\"%s\" data-bs-trigger=\"hover\" data-bs-toggle=\"popover\"><i class=\"%s\"></i> <span>%s</span></a></li>",
      htmlspecialchars( $item->url ),
      htmlspecialchars( $item->target ? $item->target : '_self' ),
      htmlspecialchars( $item->attr_title ? $item->attr_title : $item->post_title ),
      htmlspecialchars( $item->description ? $item->description : 'No description.' ),
      htmlspecialchars( implode( " ", $item->classes ) ),
      htmlspecialchars( $item->post_title )
    );
  }
  public function end_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
    $output .= "</li>";
  }
}

add_action( 'wp_footer', function () {
  $locations = get_nav_menu_locations();
  if ( ! empty($locations['floating_icons']) ) {
    $name = wp_get_nav_menu_name( 'floating_icons' );
    echo '<nav id="floating-icons" class="' . App\theme_get_option('floating-icons-class') . '">';
    echo '<h2><i class="fa fa-bars"></i><span>' . htmlspecialchars($name) . '</span></h2>';
    wp_nav_menu( [
      'fallback_cb'     => false,
      'depth'           => 1,
      'theme_location'  => 'floating_icons',
      'walker'          => new FloatingIconsWalker(),
      'items_wrap'      => '<ul>%3$s</ul>'
    ] );
    echo '</nav>';
  }
} );
