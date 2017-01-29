
<article id="post-<?php the_ID(); ?>" class="post">

  <header class="entry-header">
    <?php
      if ( is_single() ) :
        the_title( '<h1 class="entry-title">', '</h1>' );
      else :
        the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
      endif;
    _PE(); ?>
  </header>

  <?php if ( '' !== get_the_post_thumbnail() && ! is_single() ) : ?>
		<div class="post-thumbnail">
			<a href="<?php the_permalink(); ?>">
				<?php the_post_thumbnail( 'featured-image' ); ?>
			</a>
		</div>
	<?php endif; _PE(); ?>

  <div class="entry-body">
    <?php

      the_content( sprintf(
        __( 'この記事を読む <span class="read-more">"%s"</span>' ),
        get_the_title()
      ) );

      wp_link_pages( array(
				'before'      => '<div class="page-links">' . __( 'Pages:' ),
				'after'       => '</div>',
				'link_before' => '<span class="page-number">',
				'link_after'  => '</span>',
			) );
    ?>
  </div>
</article>
