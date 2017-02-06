<?php

class Roud_CMB2 extends Roud
{
    public function __construct()
    {
        parent::__construct();
        if (! function_exists(WP_PLUGIN_DIR . '/cmb2/init.php')) {
            include_once(ABSPATH . 'wp-admin/includes/plugin.php');

            if (is_plugin_active('cmb2/init.php')) {
                add_action('cmb2_init', array( $this, 'cmb2_add_fields' ));
            }
        }
    }

	/**
	 *
	 */
  function cmb2_add_fields()
  {
		$this->field_meta();

    add_filter( 'cmb2_render_ex_url', array($this, 'cmb2_render_ex_url_field_callback'), 10, 5 );
  }

  function field_meta()
  {
    $prefix = 'meta_';

    $cmb = new_cmb2_box(array(
      'id'            =>  $prefix . 'fields',
      'title'         => __('Meta data', self::$domain),
      'object_types'  => array( 'post', ),
      'context'       => 'normal',
      'priority'      => 'high',
      'show_names'    => true,
    ));

    $cmb->add_field(array(
      'name'		=> __('robots', self::$domain),
      'id'      => $prefix . 'robots',
      'type'		=> 'select',
      'default'	=> get_option('blog_public'),
      'options'	=> array(
    		0 => 'noindex, follow',
    		1 => 'index, follow',
    	),
    ));

    $cmb->add_field(array(
      'name'			=> __('description', self::$domain),
      'id'        => $prefix . 'desc',
      'type'			=> 'textarea_small',
    	'default_cb'	=> array( $this, 'field_meta_set_desc' ),
      'attributes'	=> array(
          //'rows' => 3,
      ),
    ));
  }

  function field_meta_set_desc($cmb_args, $cmb)
  {
      $content = get_post($cmb->object_id())->post_content;
      $content = strip_shortcodes($content);
      $content = wp_trim_words($content, 98, ' …');
      echo $content;
  }

}
