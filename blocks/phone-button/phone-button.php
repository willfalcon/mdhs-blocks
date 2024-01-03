<?php 
  $number = get_field( 'phone', 'options' ); 
  $href = "tel:+1" . format_phone($number)['href'];
  $align = isset($block['align']) ? ' align' . $block['align'] : ''; 
?>
<div class="wp-block-button phone-button<?php echo $align; ?>">
  <a class="wp-block-button__link phone-button__link" href="<?php echo $href; ?>">
    <svg class="phone-button__icon"><use href="#phone-icon"></use></svg>
    <?php _e('Call Now', 'mdhs'); ?>
  </a>
</div>