<?php

class Roud_CustomPost extends Roud
{
  public function __construct()
  {
    parent::__construct();

    $this->global_nav();
    $this->news();
    $this->illurweb();
  }

  private function global_nav()
  {
    $cpt_args = array(
      'labels' => array(
  			'name'       => __('ナビゲーション', self::$domain),
  			'all_items'  => __('メニュー一覧', self::$domain),
  		),
      'supports'  => array(
  			'title',
  		),
      'public'        => false,
      'has_archive'   => false,
      'show_ui'       => true,
      'show_in_rest'	=> false,
      'menu_position' => 10,
    );
    register_post_type('navs', $cpt_args);
  }

  private function news()
  {
    $cpt_args = array(
      'label'         => 'NEWS',
      'public'        => true,
      'has_archive'   => true,
      'show_ui'       => true,
      'show_in_rest'  => false,
      'menu_position' => 5,
    );
    register_post_type('news', $cpt_args);
  }

  private function illurweb()
  {
    $cpt_args = array(
      'label'         => __('ILLURWEB', self::$domain),
      'public'        => true,
      'has_archive'   => true,
      'show_ui'       => true,
      'show_in_rest'	=> false,
      'menu_position'	=> 5,
    );
    register_post_type('illurweb', $cpt_args);
    $tax_args = array(
      'label'         => __('チャプター'),
      'hierarchical'	=> true,
      'show_in_rest'	=> false,
    );
    register_taxonomy('illurweb_chap', 'illurweb', $tax_args);
  }
}
