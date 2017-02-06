<?php get_header(); ?>

  <main>

		<?php get_sidebar(); ?>

		<header class="page-header">
			<?php if ( have_posts() ) : ?>
				<h1 class="page-title"><?php printf( __( 'Search Results for: %s' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
			<?php else : ?>
				<h1 class="page-title"><?php _e( 'Nothing Found' ); ?></h1>
			<?php endif; ?>
		</header><!-- .page-header -->

    <section class="archive">
      <?php if ( have_posts() ) : ?>
		  <ul>
				<?php
          /* Start the Loop */
          while ( have_posts() ) : the_post();

            get_template_part( 'template-parts/post/content', 'list' );

          endwhile;
        ?>
      </ul>

	    <?php
	      the_posts_pagination( array(
	        'prev_text' => '<span class="screen-reader-text">' . __( 'Previous page' ) . '</span>',
	        'next_text' => '<span class="screen-reader-text">' . __( 'Next page' ) . '</span>',
	        'before_page_number' => '<span class="meta-nav">' . __( 'Page' ) . ' </span>',
	      ) );

				else :
      ?>

			<p><?php _e( 'キーワードにマッチする記事が見つかりませんでした。別のキーワードをお試しください。' ); ?></p>

			<?php

				get_search_form();

				endif;
			?>

    </section>
  </main>

<?php get_footer();
