<?php $align = isset($block['align']) ? ' align' . $block['align'] : '';  ?>

<div class="homepage-header<?php echo $align; ?>">

  <div class="hh__side-content">
    <?php while ( have_rows( '2nd_content_area' ) ) : the_row(); ?>
      <img class="hh__logo" src="<?php echo get_template_directory_uri(); ?>/dist/mdhs-logo.svg" alt="<?php _e('Mississippi Department of Human Services', 'mdhs'); ?>" />
      <?php the_sub_field( 'content' ); ?>
      <?php $button = get_sub_field( 'button' ); ?>
      <?php if ($button) : ?>
        <a class="hh__button button" href="<?php echo $button['url']; ?>" target="<?php echo $button['target']; ?>">
          <?php echo $button['title']; ?>
        </a>
      <?php endif; ?>
    <?php endwhile; ?>
  </div>

  <div class="hh__post">
    <?php $custom = get_field('post_section'); ?>
    <?php if ($custom) : ?>
      <?php while (have_rows( 'manual_info' ) ) : the_row(); ?>
        <?php $image = get_sub_field( 'image' ); ?>
        <div class="hh__image-wrapper">
          <?php echo wp_get_attachment_image($image['ID'], 'medium_large'); ?>
        </div>
        <h2 class="hh__heading"><?php the_sub_field( 'heading' ); ?></h2>
        <p class="hh__excerpt"><?php the_sub_field( 'text' ); ?></p>
        <?php $post_button = get_sub_field( 'button' ); ?>
        <?php if ($post_button) : ?>
          <a class="hh__button button" href="<?php echo $post_button['url']; ?>" target="<?php echo $post_button['target']; ?>">
            <?php echo $post_button['title']; ?>
          </a>
        <?php endif; ?>
      <?php endwhile; ?>
    <?php else: ?>
      <?php 
        $post = get_field( 'post' );
        $image = get_post_thumbnail_id($post->ID);
        $image = wp_get_attachment_image($image, 'medium_large');
      ?>
      <div class="hh__image-wrapper">
        <?php echo $image; ?>
      </div>
      <h2 class="hh__heading"><?php echo $post->post_title; ?></h2>
      <p class="hh__excerpt"><?php echo get_the_excerpt($post->ID); ?></p>
      <a class="hh__button button" href="<?php the_permalink($post->ID); ?>"><?php _e('Learn More', 'mdhs'); ?></a>
    <?php endif; ?>
  </div>

  <?php include( plugin_dir_path(__FILE__) . 'locations/locations.php' ); ?>
 
</div>