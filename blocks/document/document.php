<?php
  $doc = get_field('document');
  $image = get_the_post_thumbnail( $doc, 'medium' );
?>

<div class="document<?php echo $image ? '' : '--placeholder'; ?>">
  <a class="document__link" href="<?php echo get_the_permalink($doc); ?>">
    <?php if ($image) : ?>
      <div class="document__image-wrapper">
        <?php echo $image; ?>
      </div>
    <?php endif; ?>
    <span class="document__title"><?php echo get_the_title($doc); ?></span>
  </a>
</div>