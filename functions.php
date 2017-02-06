<?php

add_action('after_setup_theme', function () {
  if(! class_exists('Roud'))
  {
    require_once dirname(__FILE__) . '/inc/roud.php';
    require_once dirname(__FILE__) . '/inc/roud_theme_setup.php';
    require_once dirname(__FILE__) . '/inc/roud_cmb2_fields.php';
    require_once dirname(__FILE__) . '/inc/roud_custom_post.php';
    require_once dirname(__FILE__) . '/inc/roud_theme_tags.php';
  }
}, 0);

add_action('after_setup_theme', function () {
  new Roud_Themes();
  new Roud_CMB2();
  new Roud_CustomPost();

  $GLOBALS['RTags'] = call_user_func(array( 'Roud_Tags', 'get_object' ));
}, 99999);

class Roud_Walker_Nav_Menu extends Walker_Nav_Menu
{
	public $depth_current = 0;

	function start_lvl( &$output, $depth = 0, $args = array() ) {
    if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
        $t = '';
        $n = '';
    } else {
        $t = "\t";
        $n = "\n";
    }
		$this->depth_current++;
		$depth = $args->depth + $this->depth_current;

    $indent = str_repeat( $t, $depth );
    $output .= "{$n}{$indent}<ul class=\"sub-menu\">";
		$this->depth_current++;
  }

	function end_lvl( &$output, $depth = 0, $args = array() ) {
        if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }
				$this->depth_current--;
				$depth = $args->depth + $this->depth_current;

        $indent = str_repeat( $t, $depth );
        $output .= "{$n}{$indent}</ul>";

				$this->depth_current--;
				$depth = $args->depth + $this->depth_current;
				$indent = str_repeat( $t, $depth );
        $output .= "{$n}{$indent}";
    }

  function start_el(&$output, $item, $depth = 0, $args = Array(), $id = 0)
  {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
      $t = '';
      $n = '';
    } else {
      $t = "\t";
      $n = "\n";
    }
		$depth = $args->depth + $this->depth_current;

		$indent = ( $depth ) ? str_repeat( $t, $depth ) : '';
		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
	  $classes[] = 'menu-item-' . $item->ID;

		//
		$args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
    $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		//
		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args, $depth );
    $id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

    $output .= "{$n}{$indent}" . '<li class="menu-item">';

    $atts = array();
    $atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
    $atts['target'] = ! empty( $item->target )     ? $item->target     : '';
    $atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
    $atts['href']   = ! empty( $item->url )        ? $item->url        : '';

		//
		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

    $attributes = '';
    foreach ( $atts as $attr => $value ) {
      if ( ! empty( $value ) ) {
        $value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
        $attributes .= ' ' . $attr . '="' . $value . '"';
      }
    }

		//
		$title = apply_filters( 'the_title', $item->title, $item->ID );

		//
		$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

    $item_output = $args->before;
    $item_output .= '<a'. $attributes .'>';
    $item_output .= $args->link_before . $title . $args->link_after;
    $item_output .= '</a>';
    $item_output .= $args->after;

    $output .= apply_filters(
      'walker_nav_menu_start_el',
        $item_output,
        $item,
        $depth,
        $args
    );
  }

	function end_el( &$output, $item, $depth = 0, $args = array() ) {
    if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
      $t = '';
      $n = '';
    } else {
      $t = "\t";
      $n = "\n";
    }
		$depth = $args->depth;

		$indent = ( $depth ) ? str_repeat( $t, $depth-1 ) : '';
    $output .= "</li>{$indent}";
  }

	function set_indent(  ) {

	}
}
add_filter( 'walker_nav_menu_start_el', 'nav_menu_with_description', 10, 4 );
function nav_menu_with_description( $item_output, $item, $depth, $args )
{
	return $item_output;
}
