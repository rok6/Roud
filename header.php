<?php global $RTags; ?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js no-svg">

<head>
<meta charset="UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta name="description" content="<?php $RTags->siteinfo('description'); ?>" />
<?php wp_head(); ?>
<link rel="icon" href="<?php echo $RTags::$assets_path; ?>favicon.ico" />
<link rel="apple-touch-icon-precomposed" href="<?php echo $RTags::$assets_path; ?>favicon-152.png" />
</head>

<body>

	<header id="global-header">

	<div id="logo">
		<?php if (! is_single()) : ?>
		<h1 class="site-title"><a href="<?php echo get_home_url(); ?>"><?php $RTags->siteinfo('title'); ?></a></h1>
		<?php else : ?>
		<div class="site-title"><a href="<?php echo get_home_url(); ?>"><?php $RTags->siteinfo('title'); ?></a></div>
		<?php endif; ?>
	</div>

	<nav role="navigation" aria-label="<?php _e('Top Menu'); ?>">
		<?php
			wp_nav_menu(array(
				'theme_location'  => 'top',
				'container'       => false,
				'depth'						=> 3,
				'items_wrap'      => '<ul>%3$s</ul>'. PHP_EOL,
				'walker'          => new Roud_Walker_Nav_Menu,
			));
		?>
	</nav>

	</header>
