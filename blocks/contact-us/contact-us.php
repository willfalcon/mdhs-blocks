<?php 
  
  $classes = 'contact-us wp-block-media-text is-stacked-on-mobile is-image-fill';
  if (array_key_exists('align', $block)) {
    $classes .= ' align' . $block['align'];
  }
  if (array_key_exists('backgroundColor', $block)) {
    $classes .= ' has-' . $block['backgroundColor'] . '-background-color has-background';
  }

  $image = get_field('image') ? get_field('image') : get_field('contact_us_default_image', 'options');
  
  $template = array(
    array('core/heading', array(
      'style' => array(
        'typography' => array(
          'textTransform' => 'none'
        ),
      ),
      'textColor' => 'cobalt',
      'className' => 'is-style-alt',
      'content' => 'Contact Us'
    )),
    array('core/heading', array(
      'level' => 3,
      'textColor' => 'green',
      'content' => 'Economic Assistance Client Services'
    )),
    array('core/paragraph', array('content' => 'For customer support please call:')),
    array('core/buttons', array(), array(
      array('core/button', array(
        'url' => 'tel:8009483050',
        'text' => 'Call 800.948.3050'
      ))
    ))
  );
?>
<div class="<?php echo $classes; ?>">
  <figure class="wp-block-media-text__media contact-us__media" style="background-image:url(<?php echo $image['url']; ?>);background-position:50% 50%;">
    <?php echo wp_get_attachment_image( $image['id'], 'full' ); ?>
  </figure>
  <div class="wp-block-media-text__content contact-us__content">
    <div class="wp-block-group is-layout-constrained wp-block-group-is-layout-constrained">
      <InnerBlocks 
        template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" 
      />
    </div>
  </div>
</div>