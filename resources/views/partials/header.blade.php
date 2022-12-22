@if ( ! get_field( 'hide_header', \App\acf_page_id() ) )

@php
  echo do_shortcode( \App\theme_get_option( 'header_html' ) );
@endphp

@if ( \App\theme_get_option( 'header_menubar' ) )

<header class="banner sticky-top">
  <div class="container">
    <div class="navbar navbar-expand-md">
      @php
        $title = \App\theme_get_option( 'header_title' );
        if ( empty($title) ) {
          $title = get_bloginfo( 'name', 'display' );
        }
        $style = \App\theme_get_option( 'header_style' );
        if ( $style == 'image' ) {
          $img = \App\theme_get_option( 'header_image' );
          printf(
            '<a class="navbar-brand" href="%s">%s</a>',
            esc_html( home_url( '/' ) ),
            wp_get_attachment_image( $img, 'full', false, [ 'alt' => $title ] )
          );
        }
        elseif ( $style == 'text' ) {
          printf(
            '<a class="navbar-brand" href="%s">%s</a>',
            esc_html( home_url( '/' ) ),
            esc_html( $title )
          );
        }
        elseif ( $style == 'both' ) {
          $img = \App\theme_get_option( 'header_image' );
          printf(
            '<a class="navbar-brand" href="%s">%s <span>%s</span></a>',
            esc_html( home_url( '/' ) ),
            wp_get_attachment_image( $img, 'full', false, [ 'alt' => '' ] ),
            esc_html( $title )
          );
        }
      @endphp
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-primary" aria-controls="navbar-primary" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"><i class="fa fa-bars"></i></span>
      </button>
      @if (has_nav_menu('primary_navigation'))
        @php
          wp_nav_menu([
            'theme_location'    => 'primary_navigation',
            'depth'             => 2,
            'container'         => 'div',
            'container_class'   => 'collapse navbar-collapse',
            'container_id'      => 'navbar-primary',
            'menu_class'        => 'navbar-nav ' . \App\theme_get_option( 'header-menu-class' ),
            'fallback_cb'       => 'wp_bootstrap4_navwalker::fallback',
            'walker'            => new \App\wp_bootstrap4_navwalker(),
          ])
        @endphp
      @endif
    </div>
  </div>
</header>

@endif

@endif

@php
  $bannerimg = get_field( 'fullwidth_banner', \App\acf_page_id() );
  if ( is_array($bannerimg) ) {
    printf( '<div class="fullwidth-banner"><img src="%s" alt="" class="fullwidth"></div>', htmlspecialchars($bannerimg['url']) );
  }
  elseif ( is_numeric($bannerimg) ) {
    printf( '<div class="fullwidth-banner"><img src="%s" alt="" class="fullwidth"></div>', htmlspecialchars(wp_get_attachment_image_url($bannerimg, 'full')) );
  }
@endphp

@php
  if( function_exists('bcn_display') && ! get_field( 'hide_breadcrumbs', \App\acf_page_id() ) ) {
    echo '<div class="container breadcrumbs" typeof="BreadcrumbList" vocab="https://schema.org/">';
    bcn_display();
    echo '</div>';
  }
@endphp
