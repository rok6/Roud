<?php

//スタイルの追加
//add_filter('tiny_mce_before_init', array( $this, 'editor_styles' ), 10000);
//add_filter('mce_buttons', array( $this, 'add_custom_tags_1' ) );
//add_filter('mce_buttons_2', array( $this, 'add_custom_tags_2' ) );

function editor_styles( $array ) {

  $style_formats = array(
    array(
      'title' => 'テキスト強調（太文字＋サイズUP）- strong',
      'inline' => 'strong',
      //'classes' => ''
    ),
    array(
      'title' => 'テキスト強調（マーカー）- em',
      'inline' => 'em',
      //'classes' => ''
    ),
    array(
      'title' => '上付き文字 - sup',
      'inline' => 'sup',
      //'classes' => ''
    ),
    array(
      'title' => '下付き文字 - sub',
      'inline' => 'sub',
      //'classes' => ''
    ),
    array(
      'title' => '[コード用] - pre',
      'block' => 'pre',
    ),
    array(
      'title' => '[コード用 - プログラムソース] - code',
      'block' => 'code',
    ),
    array(
      'title' => '[コード用 - 変数] - var',
      'block' => 'var',
    ),
    array(
      'title' => '[コード用 - 結果・出力] - samp',
      'block' => 'samp',
    ),
    array(
      'title' => '[コード用 - キーボード操作] - kbd',
      'block' => 'kbd',
    ),
  );

  $array['style_formats'] = json_encode( $style_formats );

  return $array;
}

function add_custom_tags_1( $array ) {
  /*
    $mce_buttons = array(
      'formatselect', 'bold', 'italic', 'bullist', 'numlist', 'blockquote',
      'alignleft', 'aligncenter', 'alignright', 'link', 'unlink', 'wp_more', 'spellchecker'
    );
  */
  // dfw, fullscreen 以外の要素を削除
  array_splice( $array, 0, 13 );
  $array = array_merge( array(
    'formatselect',
    'styleselect', // new Styles
    'link', 'unlink',
    'wp_more',
    'spellchecker',
  ), $array );

  return $array;
}

function add_custom_tags_2( $array ) {
  /*
    $mce_buttons_2 = array(
      'strikethrough', 'hr', 'forecolor', 'pastetext', 'removeformat',
      'charmap', 'outdent', 'indent', 'undo', 'redo'
    );
  */
  $array = array(
    'undo', 'redo',
    'removeformat',
    'pastetext',
    'indent', 'outdent',
    'alignleft', 'aligncenter', 'alignright',
    'hr',
    //'charmap',
  );

  if ( ! wp_is_mobile() ) {
      $array[] = 'wp_help';
  }

  return $array;
}
