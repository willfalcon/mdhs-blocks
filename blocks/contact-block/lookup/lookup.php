<?php 
  $inherit = get_field('inherit_parent');
  global $post;
  $parent = $post ? get_post_parent($post->ID) : false;
  $parent_data = array();

  if ($parent && $inherit) {
    $parent_blocks = parse_blocks($parent->post_content);
    $parent_related_block = check_blocks($parent_blocks, 'cdhq/contact-block'); 
    if ($parent_related_block) {
      $parent_data = $parent_related_block['attrs']['data'];
    }
  }

  $contact_block = $parent_data && array_key_exists('contact_block', $parent_data) ? get_post($parent_data['contact_block']) : get_field('contact_block');
  $block_id = $contact_block ? $contact_block->ID : null;

  // if (!$contact_block) {
  //   return;
  // }
  global $counties;
  
?>

<div class="contact-block" 
  data-smartsheet-id="<?php the_field( 'smartsheet_select', $block_id ); ?>" 
  data-search-field="<?php the_field('search_field', $block_id); ?>"
  data-search-result-field="<?php the_field('search_result_field', $block_id); ?>"
  data-missing-contact="<?php the_field('missing_contact', $block_id); ?>"
  data-default-phone="<?php the_field('phone', 'options'); ?>"
  data-location-status-field="<?php the_field('location_status_field', $block_id); ?>"
  <?php if (have_rows('filter', $block_id)): while (have_rows('filter', $block_id)) : the_row(); ?>
    data-filter-field="<?php the_sub_field('filter_field'); ?>"
    data-filter-condition="<?php the_sub_field('condition'); ?>"
    data-filter-value="<?php the_sub_field( 'value' ); ?>"
    data-post-id="<?php echo $block_id; ?>"
  <?php endwhile; endif; ?>
  >
  <h2 class="contact-block__heading"><?php echo $contact_block->post_title; ?></h2>

  <div class="flip-card">
    <div class="flip-card-inner">
      <div class="flip-card-front">
        <label for="location-search"><?php the_field('search_label', $block_id); ?></label>
        <input name="location-search" id="location-search" type="text" />

        <ul class="contact-block__location-list">
          <?php foreach ($counties as $option) : // 82 ?>
            <li data-location="<?php echo strtolower($option); ?>" style="display: none;">
              <button id="<?php echo strtolower($option); ?>"><?php echo $option; ?> <i class="fa-solid fa-right-long"></i>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
      <div class="flip-card-back contact">
        <div class="contact__wrapper">
          <button class="contact__back"><i class="fa-solid fa-left-long"></i> Back</button>
          <p class="contact__location"></p>
          <h3 class="contact__name"></h3>
          <ul class="contact__icons"></ul>
          <a class="contact__phone" href=""></a>
          <address class="contact__address">
            <span class="line-1"></span>
            <span class="line-2"></span>
          </address>
          <a class="contact__website" href="" target="_blank"></a>
        </div>
      </div>
    </div>
  </div>
</div>