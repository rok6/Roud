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


function _PE()
{
    echo PHP_EOL;
}
