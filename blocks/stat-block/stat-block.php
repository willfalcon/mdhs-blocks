<div class="stat-block">
  <?php $icon = get_field( 'icon_select' ); ?>
  <?php if ($icon) : ?>
    <svg class="stat-block__icon"><use href="#<?php echo $icon; ?>-icon"></use></svg>
  <?php endif; ?>
  <?php if (get_field('before')): ?>
    <p class="stat-block__label stat-block__before"><?php the_field('before'); ?></p>
  <?php endif; ?>
  <div class="stat-block__stat">
    <?php if (get_field('prepend')): ?>
      <span class="stat-block__prepend"><?php the_field('prepend'); ?></span>
    <?php endif; ?>
    <?php if (get_field('stat')): ?>
      <span class="stat-block__number"><?php echo number_format(get_field('stat')); ?></span>
    <?php endif; ?>
    <?php if (get_field('append')): ?>
      <span class="stat-block__append"><?php the_field('append'); ?></span>
    <?php endif; ?>
  </div>
  <?php if (get_field('after')): ?>
    <p class="stat-block__label stat-block__after"><?php the_field( 'after' ); ?></p>
  <?php endif; ?>
</div>