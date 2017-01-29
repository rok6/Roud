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
		$this->field_navigation();


    add_filter( 'cmb2_render_ex_url', array($this, 'cmb2_render_ex_url_field_callback'), 10, 5 );
  }

	/**
	 *
	 * CMBs Standard
	 *
	 * Keys
	 * =================================================
	 *
	 * New Box
	 * @title //box title
	 * @id
	 * @object_types // Array
	 * @context  //  'normal', 'advanced', or 'side'
	 * @priority //  'high', 'core', 'default' or 'low'
	 * @show_names // bool
	 * @cmb_styles // bool
	 * @closed // bool
	 * ...etc
	 *
	 * New Fields
	 * @name
	 * @id
	 * @desc
	 * @type // input type
	 * @default // default key
	 * @options // Array(key, value)
	 * @repeatable // bool
	 * @save_id // bool
	 * @allow // ex. array( 'url', 'attachment' )
	 * @show_on_cb // my_callback[return bool]  ex. return false => hidden
	 * @on_front //
	 * @attributes // Array()
	 * ...etc
	 *
	 */
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
      'type'			=> 'textarea_medium',
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

	/**
	 * CMB2 - Navigations
	 */
  function field_navigation()
  {
    $prefix = 'nav_';

    $cmb = new_cmb2_box(array(
			'title'       => __('Menu list', self::$domain),
			'id'          =>  $prefix . 'menu_list',
			'object_types'=> array( 'navs', ),
			'context'     => 'normal',
			'priority'    => 'high',
			'show_names'  => true,
    ));

		$group_field_id = $cmb->add_field(array(
			'id'           => $prefix . 'menus',
			'type'         => 'group',
			'description'  => '',
			'options'	=> array(
				'group_title'   => __('Menu {#}', self::$domain),
				'add_button'    => 'メニューの追加',
				'remove_button'	=> 'メニューの削除',
				'sortable'      => true,
			),
		));

		$cmb->add_group_field( $group_field_id, array(
			'name' => __('link name', self::$domain),
			'id'   => $prefix . 'name',
			'type' => 'text_medium',
		));

		$cmb->add_group_field( $group_field_id, array(
			'name'     => __('url', self::$domain),
			'id'       => $prefix . 'url',
			'type'     => 'ex_url',
			//'allow'    => array('url', 'attachment'),
		));

		$cmb->add_group_field( $group_field_id, array(
			'name'     => __('target', self::$domain),
			'id'       => $prefix . 'target',
			'type'     => 'radio_inline',
			'options'  => array(
				'_self'   => '_self',
				'_blank'  => '_blank',
			),
			'default'	=> '_self',
		));

	}

  /**
   * Render Address Field
   */
  function cmb2_render_ex_url_field_callback( $field, $value, $object_id, $object_type, $field_type ) {

      // make sure we specify each part of the value we need.
      $value = wp_parse_args( $value, array(
          'url_select' => '',
          'url_type_text'      => '',
      ) );

      ?>
      <div class="alignleft"><p><label for="<?php echo $field_type->_id( '_url_select' ); ?>'">Type Select</label></p>
          <?php echo $field_type->select( array(
              'name'    => $field_type->_name( '[url_select]' ),
              'id'      => $field_type->_id( '_url_select' ),
              'options' => $this->cmb2_get_url_select_options( $value['url_select'] ),
              'desc'    => '',
          ) ); ?>
      </div>
      <div class="alignleft"><p><label for="<?php echo $field_type->_id( '_url_type_text' ); ?>">Address 1</label></p>
          <?php echo $field_type->input( array(
              'name'  => $field_type->_name( '[url_type_text]' ),
              'id'    => $field_type->_id( '_url_type_text' ),
              'value' => $value['url_type_text'],
              'desc'  => '',
              'show_on_cb'  => array($this, 'cmb2_only_show_for_type_text'),
          ) ); ?>
      </div>
      <br class="clear" />
      <?php
      echo $field_type->_desc( true );

  }

  function cmb2_get_url_select_options( $value = false ) {
    $state_list = array(
      'text'  => 'Text',
      'post'  => 'Post',
      'category'  => 'Category',
    );

    $state_options = '';
    foreach ( $state_list as $abrev => $state ) {
      $state_options .= '<option value="'. $abrev .'" '. selected( $value, $abrev, false ) .'>'. $state .'</option>';
    }

    return $state_options;
  }

  function cmb2_only_show_for_type_text( $cmb ) {
    $status = get_post_meta( $cmb->object_id(), 'nav_url', 1 );
    return 'external' === $status;
  }

}
