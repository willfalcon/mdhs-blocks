<?php 
  $tags = get_field( 'tags' );
  $align = isset($block['align']) ? ' align' . $block['align'] : ''; 
  
  $pages = get_posts(array(
    'post_type' => 'page',
    'tax_query' => array(
      array(
        'taxonomy' => 'page-tag',
        'terms' => $tags
      )
    )
  ));
?>

<ul class="filterable-page-list<?php echo $align; ?>">
  <?php foreach ($pages as $page) : ?>
    <li>
      <a href="<?php echo get_the_permalink($page->ID); ?>"><?php echo get_the_title($page->ID); ?></a>
    </li>

  <?php endforeach; ?>
</ul>