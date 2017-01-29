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
            'id'		=> $prefix . 'robots',
            'type'		=> 'select',
	        'default'	=> get_option('blog_public'),
	        'options'	=> array(
				0 => 'noindex, follow',
				1 => 'index, follow',
			),
        ));

        $cmb->add_field(array(
            'name'			=> __('description', self::$domain),
            'id'			=> $prefix . 'desc',
            'type'			=> 'textarea',
        	'default_cb'	=> array( $this, 'field_meta_set_desc' ),
            'attributes'	=> array(
                'rows' => 3,
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
			'id'			=> $prefix . 'menus',
			'type'			=> 'group',
			'description'	=> '',
			'options'	=> array(
				'group_title'   => __('Menu {#}', self::$domain),
				'add_button'	=> 'メニューの追加',
				'remove_button'	=> 'メニューの削除',
				'sortable'		=> true,
			),
		));

		$cmb->add_group_field( $group_field_id, array(
			'name'		=> __('link name', self::$domain),
			'id'		=> $prefix . 'name',
			'type'		=> 'text_medium',
		));

		$cmb->add_group_field( $group_field_id, array(
			'name'		=> __('url', self::$domain),
			'id'		=> $prefix . 'url',
			'type'		=> 'text_url',
			'type_cb'	=> array($this, 'field_navigation_select_url'),
			'allow'		=> array('url', 'attachment'),
		));

		$cmb->add_group_field( $group_field_id, array(
			'name'		=> __('target', self::$domain),
			'id'		=> $prefix . 'target',
			'type'		=> 'radio_inline',
			'options'	=> array(
				'_self' => '_self',
				'_blank'=> '_blank',
			),
			'default'	=> '_self',
		));

	}

    function field_navigation_select_url( $args )
	{
		var_dump($args);
    }

}
