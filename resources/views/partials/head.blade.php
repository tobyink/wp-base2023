<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  @php
    wp_head();

    $custom_javascript = get_field( 'custom_javascript', App\acf_page_id() );
    $custom_css        = get_field( 'custom_css', App\acf_page_id() );
    if ( ! empty($custom_css) ) {
      echo "\n";
      echo '<style id="custom_css" type="text/css">' . $custom_css . '</style>';
    }
    if ( ! empty($custom_javascript) ) {
      echo "\n";
      echo '<script id="custom_javascript" type="text/javascript">' . $custom_javascript . '</script>';
    }
  @endphp
</head>
