<?php
if ( ! ( is_active_sidebar( 'topbar-1' ) || is_active_sidebar( 'topbar-2' ) ) )
	return;
?>

<aside id="topbar" role="complementary">
	<div class="widget-area topbar-left">
	<?php dynamic_sidebar( 'topbar-1' ); ?>
	</div>
	<div class="widget-area topbar-left">
	<?php dynamic_sidebar( 'topbar-2' ); ?>
	</div>
</aside>
