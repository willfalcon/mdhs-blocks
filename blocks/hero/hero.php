<?php 
  $classes = 'hero';
  $classes .= $is_preview ? ' is-preview' : '';
?>

<div class="<?php echo $classes; ?>">

  <?php 
  
    $image = get_field('image'); 
    $link = get_field('link');

    $image_wrapper_open = $link ?
      $image ?
        '<a class="hero__image-wrapper" href="' . $link['url'] . '">'
      :
        '<a class="hero__image-wrapper placeholder" href="' . $link['url'] . '">'
      :
        '<div class="hero__image-wrapper">'
      ;
    $image_wrapper_close = $link ? '</a>' : '</div>';
  ?>

  <?php if ($image) : ?>
    <?php echo $image_wrapper_open; ?>
      <img class="hero__image" src="<?php echo $image['sizes']['medium_large']; ?>" alt="<?php echo $image['alt']; ?>" />
    <?php echo $image_wrapper_close; ?>
  <?php else: ?>
    <div class="hero__image-wrapper placeholder">
      <div class="hero__image"></div>
    </div>
  <?php endif; ?>

  <?php if (get_field('heading')) : ?>
    <p class="hero__heading"><?php the_field('heading'); ?></p>
    <?php else : ?>
      <p class="hero__heading"><?php _e('Hero Heading Text', 'cdhq'); ?></p>
  <?php endif; ?>
</div>