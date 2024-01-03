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
    
  $type = $parent_data && array_key_exists('type', $parent_data) ? $parent_data['type'] : get_field( 'type' ); 

  if ($type === 'table') {
    include(__DIR__ . '/table.php');
  } else { 
    include(__DIR__ . '/lookup/lookup.php');
  }
?>