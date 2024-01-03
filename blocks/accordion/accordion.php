<?php 
  
  $classes = isset($block['className']) ? ' ' . $block['className'] : '';
  if (isset($block['align'])) {
    $classes .= ' align' . $block['align'];
  }
  if (isset($block['backgroundColor'])) {
    $classes .= ' has-' . $block['backgroundColor'] . '-background-color';
  }
  if (isset($block['textColor'])) {
    $classes .= ' has-' . $block['textColor'] . '-color';
  }

  $allowed_blocks = array('cdhq/accordion-section');
  $template = array(
    array('cdhq/accordion-section', array(
      'heading' => 'Heading here.'
    ))
  );
?>

<div class="accordion<?php echo $classes; ?>"> 
  <h2 class="accordion__header"><?php the_field( 'heading' ); ?></h2>
  
  <InnerBlocks 
    allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" 
    template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" 
  />

  <?php if ( is_admin() ) : ?>
    <span class="outer-select-helper">
      <?php _e('This is a visible box to help select the outer accordion block when you need to add another section. It doesn\'t appear on the frontend.', 'mdhs'); ?>
    </span>
  <?php endif; ?>
  
</div>