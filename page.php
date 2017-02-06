<?php get_header(); ?>

	<main id="main" class="site-main" role="main">

		<?php
			/* Start the Loop */
			while ( have_posts() ) : the_post();

				get_template_part( 'template-parts/post/content', '' );

			endwhile;
		?>

	</main><!-- #main -->

<?php get_footer();
