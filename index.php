<?php get_header(); ?>

<div class="wrap" style="padding:140px 28px 64px;">
  <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <article>
      <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
      <?php the_content(); ?>
    </article>
  <?php endwhile; else : ?>
    <p>Walay content nga makita.</p>
  <?php endif; ?>
</div>

<?php get_footer(); ?>