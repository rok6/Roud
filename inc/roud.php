<?php
/**
 * 参考：
 * http://wpxtreme.jp/use-class-in-wordpress-theme
 * https://2inc.org/blog/2012/06/29/1626/
 */

class Roud {

  static private $class_obj = NULL;
  static protected $domain;
  static public $assets_path;

	public function __construct() {

	  self::$domain = strtolower( get_class( $this ) );
	  //self::$assets_path = get_home_url() . '/assets/';
	  self::$assets_path = get_template_directory_uri() . '/assets/';

	  load_theme_textdomain(
      self::$domain,
      get_template_directory() . '/languages'
    );
	}

  /**
   * to GLOBALS['my class object']
   */
	public static function get_object() {
		if ( NULL === self::$class_obj ) {
		 self::$class_obj = new static();
		}
		return self::$class_obj;
	}

  /**
   * clean wp_head
   */
  function clean_wp_head( $array ) {

    if ( is_string( $array )  )
      $array = array( $array );
    if ( ! is_array( $array ) )
      return;

    foreach( $array as $func ) {

      if ( ! is_string($func)  )
        return;

      switch( $func ) {
        case 'index_rel_link' :
        case 'rsd_link' :
        case 'wlwmanifest_link' :
        case 'wp_generator' :
        case 'wp_shortlink_wp_head' :
        case 'rest_output_link_wp_head' :
        case 'wp_oembed_add_discovery_links' :
        case 'wp_oembed_add_host_js' :
          remove_action( 'wp_head', $func );
          break;
        case 'parent_post_rel_link' :
        case 'start_post_rel_link' :
        case 'adjacent_posts_rel_link_wp_head' :
          remove_action( 'wp_head', $func, 10, 0 );
          break;
        case 'feed_links' :
          remove_action( 'wp_head', $func, 2 );
          break;
        case 'feed_links_extra' :
          remove_action( 'wp_head', $func, 3 );
          break;
        case 'print_emoji_detection_script' :
          remove_action( 'wp_head', $func, 7 );
          remove_action( 'admin_print_scripts', $func );
        case 'print_emoji_styles' :
          remove_action( 'wp_print_styles', $func );
          remove_action( 'admin_print_styles', $func );
          break;
        case 'wp_staticize_emoji_for_email' :
          remove_action( 'wp_mail', $func );
          break;
        case 'wp_staticize_emoji' :
          remove_action( 'the_content_feed', $func );
          remove_action( 'comment_text_rss', $func );
          break;
        case 'recent_comments_style' :
          add_action('widgets_init', function(){
            global $wp_widget_factory;
            remove_action( 'wp_head', array(
          		$wp_widget_factory -> widgets['WP_Widget_Recent_Comments'], 'recent_comments_style'
          	));
          });
          break;
        default : return;
      }
    }
  }


}
