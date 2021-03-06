<?php get_header(); ?>

  <main>
		
		<?php get_sidebar(); ?>

    <section class="archive">
      <ul>
        <?php
        if ( have_posts() ) :

          /* Start the Loop */
          while ( have_posts() ) : the_post();

            get_template_part( 'template-parts/post/content', 'list' );

          endwhile;

        else :

          get_template_part( 'template-parts/post/content', 'none' );

        endif;
        ?>

      </ul>

      <?php
      the_posts_pagination( array(
        'prev_text' => '<span class="screen-reader-text">' . __( 'Previous page' ) . '</span>',
        'next_text' => '<span class="screen-reader-text">' . __( 'Next page' ) . '</span>',
        'before_page_number' => '<span class="meta-nav">' . __( 'Page' ) . ' </span>',
      ) );
      ?>

    </section>
  </main>

<?php get_footer();
