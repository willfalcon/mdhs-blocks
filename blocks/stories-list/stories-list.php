<?php 

  $align = isset($block['align']) ? ' align' . $block['align'] : ''; 
   
  $categories = get_field('categories_to_filter');
  
  if (!$categories) {
    $categories = array();
  }
  $cats = join(',', $categories);


  $query = new WP_Query(array(
    'posts_per_page' => get_field('num_posts'),
    'cat' => $cats
  ));

  
?>


<div class="stories-list-wrapper<?php echo $align; ?>">
  <h2 class="stories-list-heading"><?php the_field( 'heading' ); ?></h2>

  <?php if ($query->have_posts()) : ?>
    <div class="stories-list">
      <?php
        while ($query->have_posts()): $query->the_post();
          get_template_part('parts/post');
        endwhile; wp_reset_postdata(); 
      ?>
    </div>
  <?php endif; ?>

</div>