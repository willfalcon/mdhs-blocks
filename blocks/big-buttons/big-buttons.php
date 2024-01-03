<?php 
  $align = isset($block['align']) ? ' align' . $block['align'] : ''; 
  $class = isset($block['className']) ? ' ' . $block['className'] : '';
  $class .= $is_preview ? ' ispreview' : '';
  $text_align = get_field( 'text_align' ); 
  if ($text_align != 'center') {
    $text_align = ' text-' . $text_align;
  } else {
    $text_align = '';
  }
?>

<?php if ( have_rows( 'buttons' ) ) : ?>
  <div class="big-buttons<?php echo $align; echo $class; ?>">
    <?php while ( have_rows( 'buttons' ) ) : the_row(); ?>
      <?php $link = get_sub_field( 'button' ); ?>
      <?php if (is_array($link)) : ?>
        <a class="big-buttons__button<?php echo $text_align; ?>" href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>">
          <span><?php echo $link['title']; ?></span>
        </a>
      <?php endif; ?>
    <?php endwhile; ?>
  </div>
<?php endif; ?>