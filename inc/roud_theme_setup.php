<?php

class Roud_Themes extends Roud
{
    public function __construct()
    {
        parent::__construct();

        add_action('wp_enqueue_scripts', array( $this, 'load_styles' ));
        add_action('wp_enqueue_scripts', array( $this, 'load_scripts' ));
        add_action('widgets_init', array( $this, 'add_widgets_support' ));
        add_action('wp_footer', array( $this, 'include_svg_icons'), 9999);
        add_action('admin_menu', array( $this, 'custom_nav_menu_move'));

        add_filter('custom_menu_order', '__return_true');
        add_filter('menu_order', array( $this, 'custom_menu_order' ));
        //add_filter( 'esc_html', array( $this, 'rename_post_formats' ) );
        add_filter('excerpt_length', array( $this, 'excerpt_length' ), 9999);
        add_filter('excerpt_more', array( $this, 'excerpt_more' ));

        $this->add_theme_supports();
        $this->add_editor_style();
        $this->clean_wp_head(array(
          'wp_generator',
          'wp_shortlink_wp_head',
          'feed_links',
          'feed_links_extra',
          'rsd_link', //EditURI　外部ツールから記事を投稿しないのなら不要
          'wlwmanifest_link', //wlwmanifest　Windows Live Writer　を使わないなら不要
          //page-links
          'index_rel_link',
          'parent_post_rel_link',
          'start_post_rel_link',
          'adjacent_posts_rel_link_wp_head',
          //oEmbed
          'rest_output_link_wp_head',
          'wp_oembed_add_discovery_links',
          'wp_oembed_add_host_js',
          //emoji
          'print_emoji_detection_script',
          'print_emoji_styles',
          //inline-style
          'recent_comments_style',
        ));
    }

    function custom_nav_menu_move()
    {
      remove_submenu_page( 'themes.php','nav-menus.php' );
      add_menu_page(
        __( 'メニュー', self::$domain ),    //$page_title
        __( 'メニュー', self::$domain ),    //$menu_title
        'edit_theme_options',       //$capability
        'nav-menus.php',        //$menu_slug
        '',
        null,
        5
      );
    }

    function custom_menu_order( $menu_order )
    {
      $menu = array();

      foreach ( $menu_order as $key => $val ) {
        if ( 0 === strpos( $val, 'edit.php' ) )
          break;

        $menu[] = $val;
        unset( $menu_order[$key] );
      }

      foreach ( $menu_order as $key => $val ) {
        if ( 0 === strpos( $val, 'edit.php' ) ) {
          $menu[] = $val;
          unset( $menu_order[$key] );
        }
      }

      return array_merge( $menu, $menu_order );
    }

    /**
    * SVG icons
    */
    public function include_svg_icons($svg_url)
    {
        $svg_icons = str_replace('/', '\\', ABSPATH . '../assets/images/svg-icons.svg');
        if (file_exists($svg_icons))
		{
            require_once $svg_icons;
        }
    }

    /**
    * add original Editor class
    */
    public function custom_editor_settings($array)
    {
        $array['body_class'] = 'editor-area';
        $array['block_formats'] = "見出し(h2)=h2; 見出し(h3)=h3; 見出し(h4)=h4; 見出し(h5)=h5; 見出し(h6)=h6; 段落(p)=p;";
        return $array;
    }
    public function add_editor_style()
    {
        add_filter('tiny_mce_before_init', array( $this, 'custom_editor_settings' ));
        add_editor_style(array( self::$assets_path . 'css/editor-style.css', $this->custom_fonts_url() ));
    }

    /**
    *	load Styles
    */
    public function load_styles()
    {
        wp_enqueue_style('font', $this->custom_fonts_url(), array(), null);
        wp_enqueue_style('style', self::$assets_path . 'css/style.css', array(), '170119');
    }

    /**
    *	load Scripts
    */
    public function load_scripts()
    {
        if (!is_admin())
		{
            wp_deregister_script('jquery');
            wp_enqueue_script('jquery', '//ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js', array(), null, true);
        }

        wp_enqueue_script(self::$domain . '-plugins', self::$assets_path . 'js/plugins.js', array(), null, true);
        wp_enqueue_script(self::$domain . '-scripts', self::$assets_path . 'js/index.js', array(), null, true);
    }

    /**
    *	add Supports
    */
    public function add_theme_supports()
    {
        add_theme_support('automatic-feed-links');
        add_theme_support('title-tag');
        add_theme_support('post-thumbnails');
        add_theme_support('customize-selective-refresh-widgets');

        add_theme_support('html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption'
        ));

	    /**
	     *  Image Supports
	     */
	    add_image_size('featured-image', 1920, 1080, true);
        add_image_size('thumbnail-avatar', 100, 100, true);
    }

    /**
    *	add Widgets
    */
    public function add_widgets_support()
    {
        register_sidebar(array(
	        'name'          => __('Topbar Left', self::$domain),
	        'id'            => 'topbar-1',
	        'description'   => __('画面上部のバー、左側に表示します。', self::$domain),
	        'before_widget' => '<section id="%1$s" class="widget %2$s">',
	        'after_widget'  => '</section>',
	        'before_title'  => '<p class="widget-title">',
	        'after_title'   => '</p>',
	    ));
        register_sidebar(array(
	        'name'          => __('Topbar Right', self::$domain),
	        'id'            => 'topbar-2',
	        'description'   => __('画面上部のバー、右側に表示します。', self::$domain),
	        'before_widget' => '<section id="%1$s" class="widget %2$s">',
	        'after_widget'  => '</section>',
	        'before_title'  => '<p class="widget-title">',
	        'after_title'   => '</p>',
	    ));
    }

    /**
    * Post formats Renames
    */
    public function rename_post_formats($format_text)
    {
        if ($format_text == 'ギャラリー') {
            return '画像付のレイアウト';
        }
        if ($format_text == '画像') {
            return '画像単独のレイアウト';
        }
        if ($format_text == '動画') {
            return '動画メインのレイアウト';
        }
        return $format_text;
    }

    /**
     * Excerpt length
     */
    public function excerpt_length($length)
    {
        return 60; // default = 110(55)
    }

    /**
     * Excerpt More message
     */
    public function excerpt_more($more)
    {
        return ' ...';
    }

    /**
    * load Web Fonts
    */
    public function custom_fonts_url()
    {
        $fonts_url = '';
        $font_families = array();
	    //add fonts
	    $font_families[] = 'Source+Serif+Pro';

        $query_args = array(
	        'family' => implode('|', $font_families),
	    );
        $fonts_url = add_query_arg($query_args, '//fonts.googleapis.com/css');

        return esc_url_raw($fonts_url);
    }

}
