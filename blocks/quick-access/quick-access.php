<?php if ( have_rows( 'items' ) ) : ?>
  <div class="quick-access-wrapper<?php echo $is_preview ? ' is-preview' : ''; ?>">
    <div class="quick-access swiper">
      <div class="swiper-wrapper">
        <?php while ( have_rows( 'items' ) ) : the_row(); ?>
          <?php $link = get_sub_field( 'link' );  ?>
          <?php if (is_array($link) && array_key_exists('url', $link) && array_key_exists('target', $link)) : ?>
          <a class="quick-access__link swiper-slide" href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>">
            <?php $img = get_sub_field( 'image' ); ?>
            <?php if ($img) : ?>
              <div class="quick-access__image-wrapper">
                <?php echo wp_get_attachment_image($img['ID'], 'medium'); ?>
              </div>
            <?php endif; ?>
            <span class="quick-access__label"><?php the_sub_field( 'label' ); ?></span>
          </a>
          <?php endif; ?>
        <?php endwhile; ?>
      </div>
    </div>
    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>
  </div>
<?php endif; ?>