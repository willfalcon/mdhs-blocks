<?php 
  $classes = 'icon-links';
  $classes .= isset($block['align']) ? ' align' . $block['align'] : ''; 
  $classes .= $is_preview ? ' is-preview': '';
?>

<?php if ( have_rows( 'links' ) ) : ?>
  <div class="<?php echo $classes; ?>">
    <?php while ( have_rows( 'links' ) ) : the_row(); ?>
      <?php $link = get_sub_field( 'link' ); ?>
      <a class="icon-links__link" href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>">
        <svg class="icon-links__icon"><use href="#<?php the_sub_field('icon_select'); ?>-icon"></use></svg>
        <span class="icon-links__label"><?php echo $link['title']; ?></span>
      </a>
    <?php endwhile; ?>
  </div>
<?php endif; ?>