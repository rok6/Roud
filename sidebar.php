<?php
if(! ( is_active_sidebar( 'topbar' ) ) )
	return;
?>

<aside id="topbar" role="complementary">
	<div class="widget-area topbar-left">
	<?php dynamic_sidebar( 'topbar' ); ?>
	</div>
</aside>
