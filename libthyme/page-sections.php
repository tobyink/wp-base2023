<?php

namespace App;

function _parsley_align ( $align ) {
	static $class = [
		'left'   => 'text-start',
		'center' => 'text-center',
		'right'  => 'text-right',
	];
	return $class[$align];
}

function _parsley_render_styles ( $style, $padding_type='p', &$css ) {
	$classes = '';
	if ( empty($css) ) $css = '';

	if ( is_array($style) ) {
		if ( ! empty($style['text_colour']) ) {
			$classes .= ' text-' . $style['text_colour'];
		}

		if ( $style['background_colour'] == 'custom' ) {
			$css .= sprintf( 'background-color: %s !important; ', $style['background_colour_custom'] );
		}
		elseif ( ! empty($style['background_colour']) ) {
			$classes .= ' bg-' . $style['background_colour'];
		}

		if ( $style['background_effect'] == 'gradient' ) {
			$css .= sprintf( 'background-image: linear-gradient(%s); ', parsley_render_extra_css( 'XXX', $style['background_gradient'], false ) );
		}
		elseif ( $style['background_effect'] == 'image' ) {
			$css .= sprintf( 'background-image: url(%s); ', $style['background_image']['url'] );
			$css .= sprintf( 'background-attachment: %s; ', $style['background_attachment'] );
			$css .= sprintf( 'background-position: %s;', $style['background_position'] );
			$css .= sprintf( 'background-repeat: %s; ', $style['background_repeat'] );
			$css .= sprintf( 'background-size: %s; ', $style['background_size'] );
		}

		if ( ! empty($style['border_colour']) ) {
			$classes .= ' border border-' . $style['border_colour'];
		}
		if ( ! empty($style['padding']) ) {
			$classes .= ' ' . $padding_type . '-' . $style['padding'];
		}
		if ( ! empty($style['text_alignment']) ) {
			$classes .= ' ' . _parsley_align( $style['text_alignment'] );
		}
		if ( ! empty($style['additional_classes']) ) {
			$classes .= ' ' . $style['additional_classes'];
		}
	}

	$css = trim( $css );

	return $classes;
}

function _parsley_render_heading ( $heading_level, $heading_tag ) {
	$classes = '';

	if ( is_array($heading_level) ) {
		if ( $heading_level['visual'] ) {
			if ( $heading_level['visual'] != $heading_tag ) {
				$classes .= ' ' . $heading_level['visual'];
			}
		}
		if ( ! empty($heading_level['padding']) ) {
			$classes .= ' p-0 my-' . $heading_level['padding'];
		}
		if ( ! empty($heading_level['text_alignment']) ) {
			$classes .= ' ' . _parsley_align( $heading_level['text_alignment'] );
		}
		if ( ! empty($heading_level['additional_classes']) ) {
			$classes .= ' ' . $heading_level['additional_classes'];
		}
	}

	return $classes;
}

function parsley_render_col_html ( $fields, &$classes, &$heading_in_column, &$heading_tag, &$heading_classes, &$heading ) {

	$opts = $fields['options'];
	$col_wpautop = ! $opts['exact_html'];
	$col_classes =   $opts['classes'];

	$col_content = $fields['content'];
	if ( $col_wpautop ) {
		$col_classes .= ' column-wpautop';
		$col_content  = wpautop( $col_content );
	}

	if ( $heading_in_column ) {
		$classes .= ' section-with-heading-in-column';
		$col_classes .= ' column-containing-the-heading';
		if ( $heading_tag != 'none' ) {
			$heading_classes .= ' heading-in-column';
			$col_content = sprintf( '<%s class="%s"><span>%s</span></%s>', $heading_tag, $heading_classes, $heading, $heading_tag ) . $col_content;
			$heading_tag = 'none'; // prevent duplicate heading
		}
	}

	return sprintf( '<div class="col-type-html %s">%s</div>', $col_classes, do_shortcode($col_content) );
}

function parsley_render_col_sidebar ( $fields, &$classes, &$heading_in_column, &$heading_tag, &$heading_classes, &$heading ) {
	$opts = $fields['options'];
	$col_classes = $opts['classes'];
	$sidebar_name = $fields['sidebar_name'];
	if ( $heading_in_column ) {
		$classes .= ' section-with-heading-in-column';
		$col_classes .= ' column-containing-the-heading';
		if ( $heading_tag != 'none' ) {
			$heading_classes .= ' heading-in-column';
			$col_content = sprintf( '<%s class="%s"><span>%s</span></%s>', $heading_tag, $heading_classes, $heading, $heading_tag ) . $col_content;
			$heading_tag = 'none'; // prevent duplicate heading
		}
	}
	ob_start();
	dynamic_sidebar( $sidebar_name );
	$col_content = ob_get_clean();
	return sprintf( '<div class="col-type-sidebar %s">%s</div>', $col_classes, $col_content );
}

function parsley_render_col_lottie ( $fields, &$classes, &$heading_in_column, &$heading_tag, &$heading_classes, &$heading ) {

	$opts = $fields['options'];
	$col_classes =  $opts['classes'];

	$col_content = sprintf(
		'<script src="%s"></script><lottie-player src="%s" background="transparent" speed="%d" style="%s"%s%s%s%s></lottie-player>',
		'https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js',
		$fields['lottie'],
		$fields['speed'],
		$fields['style'],
		( $fields['controls'] ? ' controls' : '' ),
		( $fields['autoplay'] ? ' autoplay' : '' ),
		( $fields['hover']    ? ' hover'    : '' ),
		( $fields['loop']     ? ' loop'     : '' )
	);

	if ( $heading_in_column ) {
		$classes .= ' section-with-heading-in-column';
		$col_classes .= ' column-containing-the-heading';
		if ( $heading_tag != 'none' ) {
			$heading_classes .= ' heading-in-column';
			$col_content = sprintf( '<%s class="%s"><span>%s</span></%s>', $heading_tag, $heading_classes, $heading, $heading_tag ) . $col_content;
			$heading_tag = 'none'; // prevent duplicate heading
		}
	}

	return sprintf( '<div class="col-type-lottie %s">%s</div>', $col_classes, do_shortcode($col_content) );
}

function parsley_render_col_break ( $fields, &$classes, &$heading_in_column, &$heading_tag, &$heading_classes, &$heading ) {
	return '</div><div class="row mt-3">';
}

function parsley_render_col_image ( $fields, &$classes, &$heading_in_column, &$heading_tag, &$heading_classes, &$heading ) {
	$opts = $fields['options'];
	$col_classes = $opts['classes'];

	$iatts = [ 'loading' => 'lazy', 'class' => '' ];
	if ( ! empty($fields['img_class']) ) {
		$iatts['class'] = $fields['img_class'];
	}
	if ( ! empty($fields['img_id']) ) {
		$iatts['id'] = $fields['img_id'];
	}
	if ( ! empty($fields['alt_text']) ) {
		$iatts['alt'] = $fields['alt_text'];
	}
	if ( ! empty($fields['title']) ) {
		$iatts['alt'] = $fields['title'];
	}
	if ( ! empty($fields['caption']) ) {
		$caption = sprintf( '<figcaption class="figure-caption">%s</figcaption>', esc_html($fields['caption']) );
		$col_classes .= ' figure';
		$iatts['class'] .= ' figure-img';
	}

	if ( $fields['rounded'] ) {
		$iatts['class'] .= ' rounded';
	}
	if ( $fields['shadow'] ) {
		$iatts['class'] .= ' shadow';
	}
	$col_content = wp_get_attachment_image( $fields['image'], 'large', false, $iatts );

	if ( ! empty($fields['img_attrs']) ) {
		$col_content = str_replace( '<img', "<img ${fields['img_attrs']} ", $col_content );
	}

	if ( $caption ) {
		if ( $fields['caption_placement'] == 'above' ) {
			$col_content = $caption . $col_content;
		}
		else {
			$col_content .= $caption;
		}
	}

	return sprintf( '<figure class="col-type-image %s">%s</figure>', $col_classes, $col_content);
}

function _parsley_render_col_listg ( $fields, $starting_class='list-group' ) {

	$opts = $fields['options'];
	$col_classes =  $opts['classes'];

	$S = $fields['lg_style'];
	$lg_style = [
		'border_colour'      => $S['border_colour'],
		'additional_classes' => $S['additional_classes'],
	];
	unset( $S['border_colour'] );
	unset( $S['additional_classes'] );
	$lg_item_style = $S;

	$lg_css = ' ';
	$lg_item_css = ' ';
	$lg_classes = $starting_class ;
	$lg_classes .= _parsley_render_styles( $lg_style, 'p', $lg_css );
	$lg_item_style = _parsley_render_styles( $lg_item_style, 'p', $lg_item_css );

	$default_item_class = $fields['lg_item_class'];

	$col_content = sprintf(
		'<ul class="%s"%s>',
		$lg_classes,
		( empty($lg_css) ? '' : sprintf( ' style="%s"', $lg_css  ) )
	);
	foreach ( $fields['item'] as $itemdata ) {

		$item_classes = $itemdata['class'];
		if ( ! $item_classes ) {
			$item_classes = $default_item_class;
		}
		$item_classes .= $lg_item_style;

		$col_content .= sprintf(
			'<li class="list-group-item %s"%s>',
			esc_html( $item_classes ),
			( empty($lg_item_css) ? '' : sprintf( ' style="%s"', $lg_item_css  ) )
		);
		$nugget = $itemdata['nugget'];
		if ( $nugget ) {
			if ( $nugget === 'text' ) {
				$col_content .= sprintf( '<span class="float-end hvr-icon">%s</span>', $itemdata['nugget_detail'] );
			}
			elseif ( $nugget === 'icon' ) {
				$col_content .= sprintf( '<i class="fa %s float-end hvr-icon"></i>', $itemdata['nugget_detail'] );
			}
			else {
				$col_content .= sprintf( '<i class="fa %s float-end hvr-icon"></i>', $nugget );
			}
		}
		$col_content .= $itemdata['html'];
		$col_content .= '</li>';
	}
	$col_content .= "</ul>";

	return [ $col_classes, $col_content ];
}

function _parsley_render_col_card ( $fields, $chunklist ) {

	$opts = $fields['options'];
	$col_wpautop = ! $opts['exact_html'];
	$col_classes =   $opts['classes'];
	if ( $col_wpautop ) {
		$col_classes .= ' column-wpautop';
	}

	$card_classes = 'card';
	if ( $fields['card_border_colour'] ) {
		$card_classes .= ' border border-' . $fields['card_border_colour'];
	}
	$card_classes .= ' ' . $fields['card_class'];

	$col_content = '<div class="' .trim($card_classes) . '"';
	if ( ! empty($fields['card_id']) ) {
		$col_content .= sprintf( ' id="%s"', esc_html( $fields['card_id'] ) );
	}
	$col_content .= '>';

	foreach ( $chunklist as $chunk ) {

		if ( $chunk == 'list-group' ) {
			$content = '';
		}
		else {
			$content = $fields[ $chunk . '_content' ];
			if ( $col_wpautop ) {
				$content = wpautop( $content );
			}
		}

		if ( $chunk == 'header' ) {
			$title = $fields['header_title'];
			$title_tag = 'h3';
			$title_classes = 'card-title';
			if ( empty($title) ) {
				$title_tag = 'none';
			}
			else {
				$title_level = $fields['header_title_level'];
				if ( $title_level['real'] ) {
					$title_tag = $title_level['real'];
				}
				$title_classes .= _parsley_render_heading( $title_level, $title_tag );
			}
			if ( $title_tag != 'none' ) {
				$content = sprintf( '<%s class="%s"><span>%s</span></%s>', $title_tag, $title_classes, $title, $title_tag )
					. $content;
			}
		}

		if ( $chunk == 'list-group' ) {
			$got = _parsley_render_col_listg( $fields, 'list-group list-group-flush' );
			$col_content .= $got[1];
		}
		elseif ( $content ) {
			$chunkstyling = ' ';
			$chunkclasses = "card-$chunk";
			$chunkclasses .= _parsley_render_styles( $fields[ $chunk . '_style' ], 'p', $chunkstyling );
			$col_content .= sprintf(
				'<div class="%s"%s>%s</div>',
				$chunkclasses,
				( empty($chunkstyling) ? '' : sprintf( ' style="%s"', $chunkstyling ) ),
				$content,
			);
		}
	}

	$col_content .= '</div>';

	return [ $col_classes, $col_content ];
}

function parsley_render_col_card ( $fields, &$classes, &$heading_in_column, &$heading_tag, &$heading_classes, &$heading ) {
	list ( $col_classes, $col_content ) = _parsley_render_col_card( $fields, [ 'header', 'body', 'footer' ] );
	return sprintf( '<div class="col-type-card %s">%s</div>', $col_classes, do_shortcode($col_content) );
}

function parsley_render_col_listg ( $fields, &$classes, &$heading_in_column, &$heading_tag, &$heading_classes, &$heading ) {
	list( $col_classes, $col_content ) = _parsley_render_col_listg( $fields );
	return sprintf( '<div class="col-type-list-group %s">%s</div>', $col_classes, do_shortcode($col_content) );
}

function parsley_render_col_listgcard ( $fields, &$classes, &$heading_in_column, &$heading_tag, &$heading_classes, &$heading ) {
	list ( $col_classes, $col_content ) = _parsley_render_col_card( $fields, [ 'header', 'list-group', 'footer' ] );
	return sprintf( '<div class="col-type-list-group-card %s">%s</div>', $col_classes, do_shortcode($col_content) );
}

function parsley_render_section ( $post_id, $count, $fields=null, $nested=false, $nested_type=false, &$menu='' ) {

	if ( $fields === null ) {
		if ( $nested ) {
			wp_die('huh?');
		}
		$all_sections = parsley_get_sections_data( $post_id );
		$fields       = $all_sections[ $count - 1 ];
	}

	$layout  = $fields['acf_fc_layout'];
	$source_post_id = $post_id;

	if ( $layout == 'post_section' ) {
		if ( $fields['section_source'] == 'common' ) {
			$ext_data = parsley_get_sections_data( 'option' );
			$is_common = true;
		}
		else {
			$ext_data = parsley_get_sections_data( $fields['post_id'] );
			$is_common = false;
		}
		$ext_id   = $fields['section_id'];
		$found_data = false;
		foreach ( $ext_data as $s ) {
			if ( $s['id'] == $ext_id ) {
				$found_data = $s;
			}
			elseif ( $s['real_index'] == $ext_id ) {
				$found_data = $s;
			}
			elseif ( count($s['tabs']) ) {
				foreach ( $s['tabs'] as $s2 ) {
					if ( $s2['id'] == $ext_id )              { $found_data = $s2; }
					elseif ( $s2['real_index'] == $ext_id )  { $found_data = $s2; }
				}
			}
			if ( $found_data ) {
				break;
			}
		}
		if ( $found_data ) {
			if ( ! $is_common ) {
				$source_post_id = $fields['post_id'];
			}
			$fields = $found_data;
			$layout = $fields['acf_fc_layout'];
		}
		else {
			return sprintf('<!-- error loading external section -->');
		}
	}

	if ( $fields['hidden'] ) {
		return '';
	}

	$wpautop = ! $fields['exact_html'];

	$contain = $fields['full_width'];
	if ( $contain == 'wide' ) {
		$contain = false;
	}

	$attrs   = '';
	$classes = '';
	$id      = $fields['id'];
	$content = $fields['content'];

	if ( empty($id) ) {
		if ( $nested ) {
			$id = $nested . '-section-' . $count;
		}
		else {
			$id = 'section-' . $count;
		}
	}

	if ( $count % 2 ) {
		$classes = 'section-odd';
		if ( $count === 1 ) {
			$classes .= ' section-first';
		}
	}
	else {
		$classes = 'section-even';
	}

	$paddingtype = 'py';

	if ( $nested ) {
		$classes .= ' section-nested';
		$contain = false;

		$S = $fields['style'];
		if ( $S['background_colour'] || $S['border_colour'] ) {
			$paddingtype = 'p';
		}

		if ( $nested_type == 'accordion' ) {
			$paddingtype = 'p';
			$contain = 'card-body p-0';
			$classes .= ' collapse';
			if ( $count == 1 ) {
				$classes .= ' show';
			}
			$attrs .= sprintf( ' aria-labelledby="%s-tabheader"', $id );
			$attrs .= sprintf( ' data-bs-parent="#%s-tabcontent"', $nested );
		}
	}

	$styling = ' ';
	$classes .= _parsley_render_styles( $fields['style'], $paddingtype, $styling );

	$heading = $fields['heading'];
	$heading_tag = 'h2';
	$heading_classes = 'section-title';
	if ( empty($heading) ) {
		$heading_tag = 'none';
	}
	else {
		$heading_level = $fields['heading_level'];
		if ( $heading_level['real'] ) {
			$heading_tag = $heading_level['real'];
		}
		$heading_classes .= _parsley_render_heading( $heading_level, $heading_tag );
	}

	if ( ! empty($fields['extra_classes']) ) {
		$classes .= ' ' . $fields['extra_classes'];
	}
	if ( ! empty($fields['aria_role']) ) {
		$attrs .= sprintf( ' role="%s"', $fields['aria_role'] );
	}

	if ( $nested ) {
		$icon = '';
		if ( $iconname = $fields['icon'] ) {
			$icon = sprintf( '<i class="fa fa-%s hvr-icon"></i> ', esc_html($iconname) );
		}

		$link_class = '';
		if ( $lc = $fields['link_class'] ) {
			$link_class = " $lc";
		}

		if ( $nested_type === 'pill-left' || $nested_type === 'pill-right' ) {
			$menu .= sprintf(
				'<a class="nav-link%s%s" id="%s-tab" data-bs-toggle="%s" href="#%s" role="tab" aria-controls="%s" aria-selected="%s">%s%s</a>',
				( ( $count == 1 ) ? ' active' : '' ),
				$link_class,
				esc_html($id),
				'pill',
				esc_html($id),
				esc_html($id),
				( ( $count == 1 ) ? 'true' : 'false' ),
				$icon,
				esc_html($heading)
			);
		}
		elseif ( $nested_type === 'accordion' ) {
			$menu .= sprintf(
				'<button class="btn btn-link btn-block text-left%s" type="button" data-bs-toggle="collapse" data-bs-target="#%s" aria-expanded="true" aria-controls="%s"><span class="float-end">%s</span>%s</button>',
				$link_class,
				esc_html($id),
				esc_html($id),
				$icon,
				esc_html($heading)
			);
		}
		else {
			$menu .= sprintf(
				'<li class="nav-item"><a class="nav-link%s%s" id="%s-tab" data-bs-toggle="%s" href="#%s" role="tab" aria-controls="%s" aria-selected="%s">%s%s</a></li>',
				( ( $count == 1 ) ? ' active' : '' ),
				$link_class,
				esc_html($id),
				$nested_type,
				esc_html($id),
				esc_html($id),
				( ( $count == 1 ) ? 'true' : 'false' ),
				$icon,
				esc_html($heading)
			);
		}

		if ( $nested_type != 'slick' ) {
			$classes .= ' tab-pane fade';
			$attrs   .= sprintf( ' role="tabpanel" aria-labelledby="%s-tab"', esc_html($id) );
			if ( $count == 1 ) {
				$classes .= ' show active';
			}
		}
	}

	$content = 'CONTENT';
	if ( $layout == 'html_content' ) {
		$content = $fields['content'];
		$classes .= ' section-type-html-content';
		if ( $wpautop ) {
			$content  = wpautop( $content );
			$classes .= ' section-wpautop';
		}
		$content = do_shortcode( $content );
	}
	elseif ( $layout == 'columns' || $layout == 'columns2' ) {
		$before = $fields['before_columns'];
		$after  = $fields['after_columns'];
		$classes .= ' section-type-columns';
		if ( $wpautop ) {
			$before   = wpautop( $before );
			$after    = wpautop( $after  );
			$classes .= ' section-wpautop';
		}
		$content  = do_shortcode( $before );
		$content .= '<div class="row">';
		$heading_in_column = $fields['heading_in_column'];
		foreach ( $fields['columns'] as $coldata ) {

			if ( $layout === 'columns' ) {
				$col_type = 'col_html';
			}
			else {
				$col_type = $coldata['acf_fc_layout'];
			}

			$got = call_user_func_array(
				'App\parsley_render_' . $col_type,
				[ $coldata, &$classes, &$heading_in_column, &$heading_tag, &$heading_classes, &$heading ] 
			);

			$content .= ( $got === false ) ? '<div class="col">ERROR</div>' : $got;
		}
		$content .= '</div>';
		$content .= do_shortcode( $after );
	}
	elseif ( $layout == 'primary_content' ) {
		$content = get_the_content( false, false, $source_post_id );
		$classes .= ' section-type-primary-content';
		if ( ! get_field( 'disable_wpautop', $source_post_id ) ) {
			$content  = wpautop( $content );
			$classes .= ' section-wpautop';
		}
		$content = do_shortcode( $content );
	}
	elseif ( $layout == 'post' ) {
		$other   = $fields['post_id'];
		$content = get_the_content( false, false, $other );
		$classes .= ' section-type-post';
		if ( ! get_field( 'disable_wpautop', $other ) ) {
			$content  = wpautop( $content );
			$classes .= ' section-wpautop';
		}
		$content = do_shortcode( $content );
	}
	elseif ( $layout == 'foogallery' ) {
		$content = '<p>Please install FooGallery.</p>';
		$classes .= ' section-type-foogallery';
		if ( function_exists( 'foogallery_render_gallery' ) ) {
			ob_start();
			foogallery_render_gallery( $fields['gallery_id'] );
			$content = ob_get_contents();
			ob_end_clean();
		}
	}
	elseif ( $layout == 'image' ) {
		$classes .= ' section-type-image';
		$heading_tag = 'none';
		$contain = false;
		$iatts = [ 'loading' => 'lazy' ];
		if ( $fields['img_class'] ) {
			$iatts['class'] = $fields['img_class'];
		}
		if ( $fields['img_id'] ) {
			$iatts['id'] = $fields['img_id'];
		}
		$content = wp_get_attachment_image( $fields['image'], 'full', false, $iatts );
	}
	elseif ( $layout == 'tabs' ) {
		$classes .= ' section-type-tabs';

		$tabtype = $fields['tab_type'];

		$before = do_shortcode( $fields['before_tabs'] );
		$after  = do_shortcode( $fields['after_tabs'] );

		if ( $wpautop ) {
			$before = wpautop( $before );
			$after  = wpautop( $after );
			$classes .= ' section-wpautop';
		}

		if ( $tabtype === 'pill' ) {
			$tabmenu  = sprintf( '<ul class="nav nav-pills mb-1" id="%s-tablist">', esc_html($id) );
			$tabmenuend = '</ul>';
		}
		elseif ( $tabtype === 'pill-left' || $tabtype === 'pill-right' ) {
			$tabmenu = sprintf( '<nav class="nav nav-pills flex-column" id="%s-tablist" role="tablist" aria-orientation="vertical">', esc_html($id) );
			$tabmenuend = '</nav>';
		}
		elseif ( $tabtype === 'accordion' ) {
			$tabmenu = '';
			$tabmenuend = '';
		}
		else {
			$tabmenu  = sprintf( '<ul class="nav nav-tabs" id="%s-tablist" role="tablist">', esc_html($id) );
			$tabmenuend = '</ul>';
		}

		$tabcount = 0;
		if ( $tabtype == 'accordion' ) {
			$tabpanes = sprintf( '<div class="accordion %s" id="%s-tabcontent">', esc_html( $fields['accordion_class'] ), esc_html($id) );
			$tabpanesend = '</div>';
		}
		elseif ( $tabtype == 'slick' ) {
			$opts = $fields['slick_options'];
			$tabpanes = sprintf( '<div class="slick-carousel slick-tabs" id="%s-tabcontent">', esc_html($id) );
			$tabpanesend = sprintf( '</div><script>document.getElementById("%s-tabcontent").setAttribute("data-slick", JSON.stringify(%s));</script>', esc_html($id), $opts );
		}
		else {
			$tabpanes = sprintf( '<div class="tab-content" id="%s-tabcontent">', esc_html($id) );
			$tabpanesend = '</div>';
		}

		$pill_class     = $fields['pill_class'];
		$content_class  = $fields['content_class'];

		foreach ( $fields['tabs'] as $tabdata ) {

			if ( $tabtype === 'accordion' ) {
				$button_html = '';
				$pane_html   = parsley_render_section( $post_id, ++$tabcount, $tabdata, $id, $tabtype, $button_html );
				$tabpanes .= '<div class="card">';
				$tabpanes .= sprintf( '<div class="card-header" id="%s-tabheader">', esc_html($id) );
				$tabpanes .= $button_html;
				$tabpanes .= '</div>';
				$tabpanes .= $pane_html;
				$tabpanes .= '</div>';
			}
			elseif ( $tabtype === 'slick' ) {
				// Slick needs the section to be wrapped because of width issues
				$tabpanes .= '<div>' . parsley_render_section( $post_id, ++$tabcount, $tabdata, $id, $tabtype, $tabmenu ) . '</div>';
			}
			else {
				$tabpanes .= parsley_render_section( $post_id, ++$tabcount, $tabdata, $id, $tabtype, $tabmenu );
			}
		}

		$tabmenu  .= $tabmenuend;
		$tabpanes .= $tabpanesend;

		if ( $tabtype === 'pill-left' ) {
			$content = sprintf(
				'%s<div class="row"><div class="%s">%s</div><div class="%s">%s</div></div>%s',
				$before,
				$pill_class,
				$tabmenu,
				$content_class,
				$tabpanes,
				$after
			);
		}
		elseif ( $tabtype === 'pill-right' ) {
			$content = sprintf(
				'%s<div class="row"><div class="%s">%s</div><div class="%s">%s</div></div>%s',
				$before,
				$content_class,
				$tabpanes,
				$pill_class,
				$tabmenu,
				$after
			);
		}
		elseif ( $tabtype === 'slick' ) {
			$content = $before . $tabpanes . $after;
		}
		else {
			$content = $before . $tabmenu . $tabpanes . $after;
		}
	}

	if ( $styling ) {
		$attrs .= sprintf( ' style="%s"', $styling );
	}

	$tag = 'section';
	if ( ! empty($fields['tag']) ) {
		$tag = $fields['tag'];
	}

	$html .= sprintf( '<%s id="%s" class="page-section %s"%s>', $tag, $id, $classes, $attrs );
	if ( $contain ) {
		$html .= '<div class="' . $contain . '">';
	}
	if ( $heading_tag != 'none' ) {
		$html .= sprintf( '<%s id="%s_heading" class="%s"><span>%s</span></%s>', $heading_tag, $id, $heading_classes, $heading, $heading_tag );
	}
	$html .= $content;
	if ( $contain ) {
		$html .= '</div>';
	}
	$html .= "</$tag>\n";

	if ( $fields['extra_css'] ) {
		$html = parsley_render_extra_css( $id, $fields['extra_css'] ) . $html;
	}

	return $html;
}

function parsley_render_extra_css ( $id, $css, $wrapper=true ) {
	$replacements = [
		'#this'      => "#$id",
		'#heading'   => "#${id}_heading",
		'SM-UP'      => '@media (min-width: 576px)',
		'MD-UP'      => '@media (min-width: 768px)',
		'LG-UP'      => '@media (min-width: 992px)',
		'XL-UP'      => '@media (min-width: 1200px)',
		'XS-DOWN'    => '@media (max-width: 575.98px)',
		'SM-DOWN'    => '@media (max-width: 767.98px)',
		'MD-DOWN'    => '@media (max-width: 991.98px)',
		'LG-DOWN'    => '@media (max-width: 1199.98px)',
		'XS-ONLY'    => '@media (max-width: 575.98px)',
		'SM-ONLY'    => '@media (min-width: 576px) and (max-width: 767.98px)',
		'MD-ONLY'    => '@media (min-width: 768px) and (max-width: 991.98px)',
		'LG-ONLY'    => '@media (min-width: 992px) and (max-width: 1199.98px)',
		'XL-ONLY'    => '@media (min-width: 1200px)',
	];
	foreach ( \App\theme_colours() as $c ) {
		$replacements["\$$c"] = \App\theme_get_option( "colour-$c" );
	}
	foreach ( [ 'serif', 'sans', 'display', 'monospace' ] as $f ) {
		$replacements["\$$f"] = \App\theme_get_option( "font-$f" );
	}
	$css = strtr( $css, $replacements );

	if ( ! $wrapper ) {
		return $css;
	}

	return sprintf( '<style type="text/css">%s</style>', $css );
}

function parsley_get_sections_data ( $post_id=null ) {

	if ( $post_id === null ) {
		$post_id = get_the_ID();
	}

	$processed = [];
	$in_tabs   = false;
	$sections  = get_field( 'design_sections', $post_id );
	$realidx   = 0;

	foreach ( $sections as $s ) {
		$s['real_index'] = ++$realidx;
		if ( $s['acf_fc_layout'] == 'end_tabs' ) {
			$in_tabs = false;
		}
		elseif ( $in_tabs ) {
			$processed[ array_key_last($processed) ]['tabs'] []= $s;
		}
		elseif ( $s['acf_fc_layout'] == 'tabs' ) {
			$s['tabs'] = [];
			$processed []= $s;
			$in_tabs = true;
		}
		else {
			$processed[] = $s;
		}
	}

	return $processed;
}

function parsley_render_sections ( $post_id=null ) {

	remove_filter( 'acf_the_content', 'wpautop' );

	if ( $post_id === null ) {
		$post_id = get_the_ID();
	}

	$count    = 0;
	$html     = '';
	$sections = parsley_get_sections_data( $post_id );

	foreach ( $sections as $s ) {
		$html .= parsley_render_section( $post_id, ++$count, $s );
	}

	// $html .= sprintf( '<!-- %s -->', esc_html( print_r( $sections, true ) ) );

	return $html;
}


