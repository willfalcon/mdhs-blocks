<div class="contact-links">

  <h3 class="contact-links__heading"><?php _e('Contact', 'mdhs'); ?></h3>
  <?php 
    global $post;
    $parent = null;
    if ($post) {
      $parent = get_post_parent($post->ID);
    }
  ?>
  <?php if ( have_rows( 'contact_links' ) || get_field('inherit_parent') ) : ?>
    <ul class="contact-links__items">

      <?php if (get_field('inherit_parent') && $parent) : ?>
        <?php 
          $parent_blocks = parse_blocks($parent->post_content);
          $parent_related_block = check_blocks($parent_blocks, 'cdhq/contact-links');
        ?>

        <?php if ($parent_related_block) : ?>

        <?php 
          $data = $parent_related_block['attrs']['data'];
          $count = array_key_exists('contact_links', $data) ? count($data['contact_links']) : 0;
        ?>

        <?php for ($x = 0; $x < $count; $x++) : ?>
          <li class="contact-links__item">

            <?php if (array_key_exists('contact_links_' . $x . '_link', $data)) : ?>
              <?php $link = $data['contact_links_' . $x . '_link']; ?>
              <?php $target = $data['contact_links_' . $x . '_new_window'] ? '_blank' : '_self'; ?>
              <a class="contact-links__link" href="<?php the_sub_field('url'); ?>" target="<?php echo $target; ?>">
                <i class="fa-solid <?php echo $data['contact_links_' . $x . '_icon']; ?>"></i>
                <?php echo $data['contact_links_' . $x . '_label']; ?>
              </a>
              <?php endif; ?>
              
            <?php if (array_key_exists('contact_links_' . $x . '_number', $data)) : ?>
              <?php $phone = $data['contact_links_' . $x . '_number']; ?>
              <?php $phone = better_format_phone($phone); ?>
              <a class="contact-links__link" href="<?php echo $phone['href']; ?>">
                <i class="fa-solid fa-<?php echo json_decode($data['contact_links_' . $x . '_icon'])->id; ?>"></i>
                <?php echo $data['contact_links_' . $x . '_label']; ?>
              </a>
            <?php endif; ?>
              
            <?php if (array_key_exists('contact_links_' . $x . '_email', $data)) : ?>
              <?php $email = $data['contact_links_' . $x . '_email']; ?>
              <a class="contact-links__link" href="mailto:<?php echo $email; ?>">
                <i class="fa-solid fa-<?php echo json_decode($data['contact_links_' . $x . '_icon'])->id; ?>"></i>
                <?php echo $data['contact_links_' . $x . '_label']; ?>
              </a>
            <?php endif; ?>

          </li>
        <?php endfor; ?>

        <?php else: ?>
          <li class="contact-links__item no-parent-block">
            No Contact Links block found on parent page.
          </li>
        <?php endif; ?>

      <?php endif; ?>

      <?php while ( have_rows( 'contact_links' ) ) : the_row(); $layout = get_row_layout(); ?>
        <li class="contact-links__item">
          <?php if ($layout == 'email') : ?>
            <a class="contact-links__link" href="mailto:<?php the_sub_field('email'); ?>">
              <i class="<?php the_sub_field('icon'); ?>"></i>
              <?php the_sub_field('label'); ?>
            </a>
            <?php endif; ?>
            <?php if ($layout == 'phone') : ?>
              <?php
                $number = get_sub_field('number');
                
                $phone = $number ? better_format_phone($number) : false;
                
                
              ?>
              <?php if ($phone) : ?>
                <a class="contact-links__link" href="<?php echo $phone['href']; ?>">
                  <i class="<?php the_sub_field('icon'); ?>"></i>
                  <?php the_sub_field('label'); ?>
                </a>
              <?php endif; ?>
            <?php endif; ?>
            <?php if ($layout == 'url') : ?>
              <?php $target = get_sub_field('new_window') ? '_blank' : '_self'; ?>
              <a class="contact-links__link" href="<?php the_sub_field('url'); ?>" target="<?php echo $target; ?>">
                <i class="<?php the_sub_field('icon'); ?>"></i>
                <?php the_sub_field('label'); ?>
              </a>
          <?php endif; ?>
        </li>
      <?php endwhile; ?>
    </ul>
  <?php endif; ?>
</div>