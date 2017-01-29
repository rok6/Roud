<?php get_header(); ?>

  <main>

    <section class="archive">
      <ul>
        <?php
        if ( have_posts() ) :

          /* Start the Loop */
          while ( have_posts() ) : the_post();

            get_template_part( 'template-parts/post/content', 'list' );

          endwhile;

          the_posts_pagination( array(
            'prev_text' => '<span class="screen-reader-text">' . __( 'Previous page' ) . '</span>',
            'next_text' => '<span class="screen-reader-text">' . __( 'Next page' ) . '</span>',
            'before_page_number' => '<span class="meta-nav">' . __( 'Page' ) . ' </span>',
          ) );

        else :

          get_template_part( 'template-parts/post/content', 'none' );

        endif;
        ?>

      </ul>
    </section>
  </main>

<?php get_footer();
