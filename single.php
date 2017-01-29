<?php get_header(); ?>

	<main id="main" class="site-main" role="main">

			<?php
				/* Start the Loop */
				while ( have_posts() ) : the_post();

					get_template_part( 'template-parts/post/content', '' );

					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif;

					the_posts_pagination( array(
					  'prev_text' => '<span>' . __( 'Previous page' ) . '</span>',
					  'next_text' => '<span>' . __( 'Next page' ) . '</span>',
					  'before_page_number' => '<span class="meta-nav">' . __( 'Page' ) . ' </span>',
					) );

				endwhile;
			?>

	</main><!-- #main -->

<?php get_footer();
