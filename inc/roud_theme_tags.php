<?php

class Roud_Tags extends Roud
{
  public function __construct()
  {
    parent::__construct();
    /**
     * add Short Codes
     */
    //toc
    add_shortcode( 'toc', array( $this, 'table_of_content' ) );
    add_action('wp_enqueue_scripts', array( $this, 'toc_load' ));
    /**
     * Comment form
     */
    add_filter('comment_form_defaults', array( $this, 'custom_comment_form' ) );
  }

  /**
   * WP Short Code
   */
  public function table_of_content( $atts )
  {
    // based : https://xakuro.com/blog/wordpress/277
    $atts = shortcode_atts( array(
       'id'         => '',
       'class'      => 'toc-list',
       'title'      => '目次',
       'depth'      => 0
    ), $atts );

    $atts['depth'] = intval( $atts['depth'] );
    $top_level = 2;

    $level_max = 2;
    $level_min = 6;

    $content = get_the_content();

    preg_match_all( '/<([hH][2-6]).*?>(.*?)<\/[hH][2-6].*?>/u', $content, $headers );
    $header_count = count( $headers[0] );

    if( $header_count > 0 )
    {
        $level = intval( preg_replace( '/[^0-9]/', '', $headers[1][0] ) );
        $top_level = ( $top_level > $level ) ? $level : $top_level;
    }
    if( $top_level < $level_max )
        $top_level = $level_max;
    if( $top_level > $level_min )
        $top_level = $level_min;

    $depth_max = ( ( $atts['depth'] == 0 ) ? $level_min : $atts['depth'] ) - $top_level + $level_max;
    $depth_current = $top_level - $level_max;
    $depth_prev = $top_level - $level_max;

    $counters = array( 0, 0, 0, 0, 0 );
    $toc_list = '';

    for( $i = 0; $i < $header_count; $i++ )
    {
      $depth = intval( preg_replace( '/[^0-9]/', '', $headers[1][$i] ) ) - $top_level + 1;

      if( $depth >= 1 && $depth <= $depth_max )
      {

        if( $depth_current == $depth )
        {
          $toc_list .= '</li>' . PHP_EOL;
        }

        while( $depth_current > $depth )
        {
          $toc_list .= '</li>' . PHP_EOL;
          $toc_list .= str_repeat("\t", $depth_current);
          $toc_list .= '</ul>' . PHP_EOL;
          $depth_current --;
          $counters[$depth_current] = 0;
        }

        if( $depth_current != $depth_prev )
        {
          $toc_list .= str_repeat("\t", $depth_current);
          $toc_list .= '</li>' . PHP_EOL;
        }

        if( $depth_current < $depth )
        {
          $toc_list .= PHP_EOL;
          $toc_list .= str_repeat("\t", $depth_current + 1);
          $toc_list .= '<ul>' . PHP_EOL;
          $depth_current ++;
        }

        $counters[$depth_current - 1] ++;
        $number = $counters[$top_level - $level_max];
        for( $j = $top_level - 1; $j < $depth_current; $j++ )
        {
          $number .= '.' . $counters[$j];
        }

        $anchor = preg_replace( '/\./', '-', $number );
        $toc_list .= str_repeat("\t", $depth_current);
        $toc_list .= '<li><a href="#section' . $anchor . '"><span class="number">' . $number . '</span> ' . $headers[2][$i] . '</a>';

        $depth_prev = $depth;

      }
    }
    while( $depth_current >= 1 )
    {
      $toc_list .= '</li>' . PHP_EOL;
      $toc_list .= str_repeat("\t", $depth_current);
      $toc_list .= '</ul>' . PHP_EOL;
      $toc_list .= str_repeat("\t", $depth_current - 1);
      $depth_current--;
    }

      $html = PHP_EOL;
      $html .= '<div' . ( ( $atts['id'] != '' ) ? ' id="' . $atts['id'] . '"' : '' ) . ' class="' . $atts['class'] . '">' . PHP_EOL;
      $html .= "\t" . '<p class="toc-title">' . $atts['title'] . '</p>';
      $html .= $toc_list;
      $html .= '</div>' . PHP_EOL;

      return $html;
    }

    function toc_load()
    {
        wp_enqueue_script(self::$domain . '-toc', self::$assets_path . 'js/toc.js', array(), null, true);
    }


    /**
     * Comment List Costomize
     */
    public function costom_comment_list( $comment, $args, $depth )
    {
      $GLOBALS['comment'] = $comment;

      $comment_link = get_edit_comment_link() ? '<a class="edit-link" href="%10$s">' . __('編集') . '</a>' : '';
      $format = <<< COMMENT_LIST_STYLES

<li id="comment-%2\$s" class="comment-stem">
  <article class="comment-body">
    <footer>
      <div class="comment-author">
        %5\$s
        <cite class="fn">%4\$s</cite>
      </div>
      <div class="comment-metadata">
        <a class="date-time" href="%9\$s"><time datetime="%6\$s">%7\$s %8\$s</time></a>{$comment_link}
      </div>
    </footer>
    <div class="comment-content">
      <p>
        %3\$s
      </p>
    </div>
    <div class="reply">
      %11\$s
    </div>
  </article>
</li>

COMMENT_LIST_STYLES;

      echo sprintf( $format,
        get_comment_class(),
        get_comment_ID(),
        get_comment_text(),
        get_comment_author(),
        get_avatar( $comment, 50 ),

        get_comment_date( DATE_W3C ), //ex. 2017-01-11T06:21:37+00:00
        get_comment_date(),
        get_comment_time(),
        get_comment_link(),

        get_edit_comment_link(),
        get_comment_reply_link( array_merge( $args, array(
            'reply_text' => __('返信'),
            'depth'      => $depth,
            'before'     => '',
            'after'      => '',
          )
        ))
      );
    }

    /**
     * Comment Form Costomize
     */
    function custom_comment_form( $comment_args )
    {
      $commenter = wp_get_current_commenter();
      $req = get_option( 'require_name_email' );
      $aria_req = ( $req ? ' aria-required="true"' : '' );

      /**
       * コメントフォーム：名前
       */
      $author_html = <<< INPUT_AUTHOR_NAME

    <p class="comment-form-author">
        <label for="author">%1\$s%3\$s</label>
        <input id="author" name="author" type="text" value="%5\$s" placeholder="%2\$s"%4\$s />
    </p>

INPUT_AUTHOR_NAME;

      /**
       * コメントフォーム：本文
       */
      $content_html = <<< INPUT_CONTENT

    <p class="comment-form-comment">
        <label for="comment">%1\$s<span class="required">*</span></label>
        <textarea id="comment" name="comment" maxlength="65525" aria-required="true" required="required"></textarea>
    </p>

INPUT_CONTENT;

      $comment_args['fields'] = array(
          'author' => sprintf( $author_html, __( 'Name' ), __('無記入の場合匿名になります'), ( $req ? ' <span class="required">*</span>' : '' ), $aria_req, esc_attr( $commenter['comment_author'] ) ),
          'email'  => '',
          'url'    => '',
      );
      $comment_args['title_reply']          = __( 'Leave a Reply' );
      $comment_args['title_reply_to']       = __( 'Leave a Reply to %s' ); //返信を押した後、誰に返信するかを表示。
      $comment_args['comment_notes_before'] = ''; //__( '* が付いている欄は必須項目です' ) __( 'Your email address will not be published.' ) . ( $req ? $required_text : '' )
      $comment_args['comment_notes_after']  = '';
      $comment_args['must_log_in']          = '';
        /*
          sprintf( __( 'You must be <a href="%s">logged in</a> to post a comment.' ),
            wp_login_url( apply_filters( 'the_permalink', get_permalink( $post_id ) ) )
          )
        */
      $comment_args['logged_in_as']      = ''; //ログイン中、ログインしていることを表示。
      $comment_args['comment_field']     = sprintf( $content_html, __( '本文' ) );
      $comment_args['cancel_reply_link'] = __( 'コメントをキャンセル' );
      $comment_args['label_submit']      = __( 'コメントを送信' );

      return $comment_args;
  }


  public function archive( $key )
  {
    switch ( $key )
    {
      case 'author':
        return printf( __( '<span class="author">%1$s</span>' . PHP_EOL, self::$domain ),
          get_the_author()
        );
      break;

      case 'author_link':
        return printf( __( '<a class="author" href="%1$s">%2$s</a>' . PHP_EOL, self::$domain ),
          esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
          get_the_author()
        );
      break;

      case 'time':
        $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';
        if( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) )
        {
          $time_string .= PHP_EOL . "\t" . '<time class="entry-date updated" datetime="%3$s">%4$s</time>' . PHP_EOL;
        }
        echo sprintf( $time_string,
          get_the_date( DATE_W3C ),
          get_the_date(),
          get_the_modified_date( DATE_W3C ),
          get_the_modified_date()
        );
      break;

			case 'category':
				// $cat = get_the_category();
				// $cat_id = $cat[0]->term_id;
				// $cat_name = $cat[0]->cat_name;
				// $cat_slug  = $cat[0]->category_nicename;
				echo get_the_category_list();
				break;

      default:
      break;
    }
  }

  public function siteinfo( $key )
  {
    switch ( $key )
    {
      case 'title':
        return bloginfo($key);
      break;

      case 'description':
        return bloginfo($key);
      break;

      default:
      break;
    }
  }

}
