<?php global $RTags; ?>

<li id="post-<?php the_ID(); ?>" class="post list">
  <div>
    <a href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark">
			<div class="image"><?php the_post_thumbnail('medium'); ?></div>
    	<h2 class="title"><?php the_title(); ?></h2>
    </a>
    <div class="datetime">
    	<?php $RTags->archive('time'); ?>
    </div>
    <div class="data">
    	<?php $RTags->archive('author'); ?>
      <div class="category"><?php $RTags->archive('category'); ?></div>
    </div>
  </div>
</li><!--#post-<?php the_ID(); ?>-->
