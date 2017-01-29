<?php global $RTags; ?>

<li id="post-<?php the_ID(); ?>" class="post list">
  <div>
    <a href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark">
    <h2 class="title"><?php the_title(); ?></h2>
  <?php //if ( '' !== get_the_post_thumbnail() && ! is_single() ) : ?>
    <div class="image">
      <?php the_post_thumbnail('medium'); _PE(); ?>
    </div>
  <?php //endif; ?>
    </a>
    <div class="content">
      <?php the_excerpt(); ?>
    </div>
    <div class="data">
    <?php $RTags->archive('author'); _PE();?>
      <?php $RTags->archive('time'); _PE();?>
    </div>
    <div class="category">
      <span></span>
    </div>
  </div>
</li><!--#post-<?php the_ID(); ?>-->
