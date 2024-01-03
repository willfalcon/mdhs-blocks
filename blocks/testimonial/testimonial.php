<div class="testimonial">
  <p class="testimonial__quote">
    "<?php the_field('quote_text'); ?>"
  </p>
  <?php if (get_field('attribution')) : ?>
    <p class="testimonial__attribution"><?php the_field( 'attribution' ); ?></p>
  <?php endif; ?>
</div>