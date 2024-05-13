<?php
  $tax = get_field('taxonomy');
  if (!$tax) {
    $tax = 'category';
  }

  $cats = array();
  if ($tax == 'category') {
    $cats = get_categories(array(
      'parent' => 0
    )); 
  } else {
    $cats = get_terms(array(
      'taxonomy' => $tax,
    ));
  }
  $object = get_taxonomy($tax); 
  $object_type = $object->object_type[0];
  $type = get_post_type_object($object_type);
  $top_level_link = get_post_type_archive_link($type->name);
  $top_level_name = $tax == 'category' ? __('News', 'mdhs') : $type->label;
?>

<div class="categories">
  <ul class="parent-sidebar-menu" data-level="0">
    <li class="menu-item">
      <a href="<?= $top_level_link  ?>"><?= $top_level_name ?></a>
      <ul class="child-sidebar-menu" data-level="1">
        <?php foreach ($cats as $cat) : ?>
          <?php $child_cats = get_categories(array(
            'parent' => $cat->term_id
          ));
          $has_children = !empty($child_cats);
          ?>
          <li class="menu-item<?php echo $has_children ? ' has_children' : ''; ?>">
            <a href="<?php echo get_category_link( $cat ); ?>">
              <?php echo $cat->name; ?>
            </a>
            <?php if ($has_children) : ?>
              <ul class="grandchild-sidebar-menu">
                <?php foreach ($child_cats as $child) : ?>
                  <li class="menu-item">
                    <a href="<?php echo get_category_link( $child ); ?>">
                      <?php echo $child->name; ?>
                    </a>
                  </li>
                <?php endforeach; ?>
              </ul>
            <?php endif; ?>
          </li>
        <?php endforeach; ?>
      </ul>
    </li>
  </ul>
</div>