<?php $labels = get_field('include_labels'); ?>

<div class="quick-help<?php echo $is_preview ? ' is-preview' : ''; ?>">
  <div class="quick-help__interior">
    <h2 class="quick-help__heading"><?php _e('Quick Help', 'mdhs'); ?></h2>
    <?php if (have_rows('items')) : ?>
      <div class="quick-help__items">
        <?php while (have_rows('items')) : the_row(); ?>
          <div class="quick-help__item">
            <?php if ($labels && get_sub_field('label')) : ?>
            <p class="quick-help__label"><?php the_sub_field('label'); ?></p>
            <?php endif; ?>
            <?php $link = get_sub_field('button'); ?>
            <?php if ($link) : ?>
              <a class="quick-help__button button" href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>">
                <?php echo $link['title']; ?>
              </a>
            <?php endif; ?>
          </div>
        <?php endwhile; ?>
      </div>
    <?php endif; ?>
  </div>
</div>