<?php

if ( is_admin() ) {
  add_action( 'admin_menu', function () {
    add_submenu_page(
      'index.php',
      'Theme Help',
      'Theme Help',
      'manage_options',
      'theme-help',
      function () {
        global $parsley_help_shortcodes, $parsley_help_classes;

        echo '<h1>Help for Parsley Theme</h1>';
        echo '<p>This theme provides a number of shortcodes and CSS classes that can be used on pages.</p>';
        if ( is_array($parsley_help_shortcodes) ) {
          ksort( $parsley_help_shortcodes );
          echo '<h2><big>Shortcode</big></h2>';
          foreach ( $parsley_help_shortcodes as $code => $s ) {
            echo "<h3>[$code]</h3>";
            echo $s;
          }
        }
        if ( is_array($parsley_help_classes) ) {
          ksort( $parsley_help_classes );
          echo '<h2><big>Built-in Classes</big></h2>';
          echo '<p>The following classes are available to use in HTML, in Design Sections, and in shortcodes.</p>';
          echo '<p>Example: <code>&lt;p class="text-large bg-primary text-white rounded-lg shadow">This is a special paragraph.&lt;/p></code></p>';
          foreach ( $parsley_help_classes as $section => $data ) {
            echo "<h3>$section</h3>";
            echo '<dl>';
            foreach ( $data as $k => $v ) {
              echo "<dt><code>.$k</code></dt>";
              echo "<dd>$v</dd>";
            }
            echo '</dl>';
          }
        }
      }
    );
  } );

  add_action( 'init', function () {
    global $parsley_help_classes;

    foreach ( [ 'left', 'right', 'center', 'justify' ] as $alignment ) {
      $parsley_help_classes['Text Alignment']["text-$alignment"] = "Sets text alignment to <code>$alignment</code>.";
    }

    $parsley_help_classes['Text Style'] = [
      'text-large'      => 'Increases text size by 50% on large screens and 25% on smaller screens.',
      'text-muted'      => 'Muted text, usually a lighter colour.',
      'text-lowercase'  => 'Presents text in lowercase.',
      'text-uppercase'  => 'Presents text in uppercase.',
      'text-monospace'  => 'Presents text in <tt>monospace font</tt>.',
    ];

    foreach ( App\theme_colours() as $colour ) {
      $parsley_help_classes['Colours']["text-$colour"] = "Sets the text colour to '$colour' as defined in the theme.";
    }
    foreach ( App\theme_colours() as $colour ) {
      $parsley_help_classes['Colours']["bg-$colour"] = "Sets the background colour to '$colour' as defined in the theme.";
    }
    foreach ( App\theme_colours() as $colour ) {
      $parsley_help_classes['Colours']["border-$colour"] = "Sets the border colour to '$colour' as defined in the theme, but does not switch on an element's border if it does not already have a border. (See <code>.border</code> for that.)";
    }

    $parsley_help_classes['Borders']['border'] = "Switches an element's border on.";
    foreach ( [ 'top', 'bottom', 'left', 'right' ] as $side ) {
      $parsley_help_classes['Borders']["border-$side"] = "Switches an element's $side border on only.";
    }
    $parsley_help_classes['Borders']['rounded'] = "Rounded corners.";
    $parsley_help_classes['Borders']['rounded-lg'] = "Larger than usual rounded corners.";
    $parsley_help_classes['Borders']['rounded-sm'] = "Smaller than usual rounded corners.";

    $sides = [
      'l' => 'left',
      'r' => 'right',
      't' => 'top',
      'b' => 'bottom',
      'x' => 'left and right',
      'y' => 'top and bottom',
    ];
    $parsley_help_classes['Spacing']['p-0'] = "Sets an element's padding to zero. <code>.p-1</code> to <code>.p-5</code> classes can be used to set increasingly large padding. Padding is the spacing <em>inside</em> an element's border.";
    foreach ( $sides as $s => $sl ) {
      $parsley_help_classes['Spacing']["p${s}-0"] = "Sets an element's $sl padding to zero. <code>.p${s}-1</code> to <code>.p${s}-5</code> classes can be used to set increasingly large padding.";
    }
    $parsley_help_classes['Spacing']['m-0'] = "Sets an element's margin to zero. <code>.m-1</code> to <code>.m-5</code> classes can be used to set increasingly large margin. Margin is the spacing <em>outside</em> the element's border.";
    foreach ( $sides as $s => $sl ) {
      $parsley_help_classes['Spacing']["m${s}-0"] = "Sets an element's $sl margin to zero. <code>.m${s}-1</code> to <code>.m${s}-5</code> classes can be used to set increasingly large margin.";
    }

    foreach ( [ 'left', 'right' ] as $side ) {
      $parsley_help_classes['Floating Elements']["float-$side"] = "Floats an element to the $side. It is often a good idea to set a width for floated elements.";
      $parsley_help_classes['Floating Elements']["float-sm-$side"] = "Floats an element to the $side, but only on small (not extra small!) screens - that is, larger phones, tablets, laptops, and desktops.";
      $parsley_help_classes['Floating Elements']["float-md-$side"] = "Floats an element to the $side, but only on medium screens - that is most tablets, laptops, and desktops.";
      $parsley_help_classes['Floating Elements']["float-lg-$side"] = "Floats an element to the $side, but only on large screens - that is most laptops and desktops.";
      $parsley_help_classes['Floating Elements']["float-xl-$side"] = "Floats an element to the $side, but only on extra large screens - that is some laptops and desktops.";
    }
    $parsley_help_classes['Floating Elements']['clearfix'] = 'Pushes an element down far enough to be clear of any floated elements.';

    $sizes = [
      '25'   => "25% of its parent's",
      '50'   => "50% of its parent's",
      '75'   => "75% of its parent's",
      '100'  => "100% of its parent's",
      'auto' => 'automatic',
    ];
    $dims = [
      'w' => 'width',
      'h' => 'height',
    ];
    foreach ( $dims as $d => $dl ) {
      foreach ( $sizes as $s => $sl ) {
        $parsley_help_classes['Sizing']["${d}-${s}"] = "Sets an element's $dl to $sl $dl.";
      }
      $parsley_help_classes['Sizing']["m${d}-100"]     = "Sets an element's maximum $dl to 100% of its parent's $dl, but allows it to be smaller.";
      $parsley_help_classes['Sizing']["v${d}-100"]     = "Sets an element's $dl to 100% of the viewport $dl.";
      $parsley_help_classes['Sizing']["min-v${d}-100"] = "Sets an element's $dl to at least 100% of the viewport $dl.";
    }

    $parsley_help_classes['FontAwesome']['fa'] = "This theme includes support for <a href='https://fontawesome.com/v4.7.0/icons/'>FontAwesome 4.7.0</a> so you can use, for example, <code>&lt;i class=\"fa fa-hourglass text-warning\">&lt;/i></code> to insert an hourglass icon in the warning colour.";

    $parsley_help_classes['Link Buttons']['btn']    = "Adding this class to a link makes it look like a button. If using any of the other button link classes, you must also include this one.";
    $parsley_help_classes['Link Buttons']['btn-sm'] = "A smaller button. For example, <code>&lt;a href=\"#\" class=\"btn btn-sm\">Click here&lt;/a></code>.";
    $parsley_help_classes['Link Buttons']['btn-lg'] = "A larger button. For example, <code>&lt;a href=\"#\" class=\"btn btn-lg\">Click here&lt;/a></code>.";
    foreach ( App\theme_colours() as $colour ) {
      $parsley_help_classes['Link Buttons']["btn-$colour"] = "A button using the $colour colour background. You should usually also use a text colour class.";
    }
  } );
}
