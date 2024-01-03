<div class="related">
  <h3 class="related__heading"><?php _e('Related', 'mdhs'); ?></h3>
  <?php 
    global $post;
    $parent = null;
    if ($post) {
      $parent = get_post_parent($post->ID);
    }
    ?>
  <?php if ( have_rows( 'related_items' ) || get_field('include_parent_related') ) : ?>
    <ul class="related__items">
      <?php if (get_field('include_parent_related') && $parent) : ?>
        <?php 
          $parent_blocks = parse_blocks($parent->post_content);
          $parent_related_block = check_blocks($parent_blocks, 'cdhq/related');
          $data = $parent_related_block['attrs']['data'];
          $count = $data['related_items'];
        ?>

        <?php for ($x = 0; $x < $count; $x++) : ?>
          <?php $link = $data['related_items_' . $x . '_link']; ?>
          <?php if ($link) : ?>
            <li class="related__item">
              <a class="related__link" href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>">
                <?php echo $link['title']; ?>
              </a>
            </li>
          <?php endif; ?>
        <?php endfor; ?>

      <?php endif; ?>

      
      <?php while ( have_rows( 'related_items' ) ) : the_row(); ?>
        <?php $link = get_sub_field( 'link' ); ?>
        <?php if ($link) : ?>
          <li class="related__item">
            <a class="related__link" href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>">
              <?php echo $link['title']; ?>
            </a>
          </li>
        <?php endif; ?>
      <?php endwhile; ?>
    </ul>
  <?php endif; ?>
</div>