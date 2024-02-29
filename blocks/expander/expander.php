<?php
  $classes = 'expander collapsible';
  if (array_key_exists('className', $block)) {
    $classes .= ' ' . $block['className'];
  }
?> 
<div class="<?php echo $classes; ?>" id="<?php echo get_field('id') ? get_field('id') : slugify(get_field('heading')); ?>">
  <button class="expander__heading">
    <?php the_field( 'heading' ); ?>
    <span class="expander__icon"><span>+</span></span>
  </button>

  <div class="expander__panel">
    <InnerBlocks />
  </div>

</div>