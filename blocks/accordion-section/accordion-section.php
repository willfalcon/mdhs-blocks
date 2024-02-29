<div class="accordion__section expander collapsible" id="<?php echo get_field('id') ? get_field('id') : slugify(get_field('heading')); ?>">
  <button class="accordion__heading expander__heading">
    <span class="accordion__title expander__title"><?php
    if (is_admin()) {
      $heading = get_field('heading');
      echo $heading ? $heading : __('Edit this heading in the sidebar settings or by clicking the edit button above.');
    } else {
      the_field( 'heading' );  
    }
     ?></span>
    <span class="accordion__icon expander__icon"><span>+</span></span>
  </button>
  <div class="accordion__panel expander__panel">
    <InnerBlocks />
  </div>
</div>