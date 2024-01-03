<?php
  $page_for_posts = get_option( 'page_for_posts' ); 
  $cats = get_categories(array(
    'parent' => 0
  )); 
  
?>

<div class="categories">
  <ul class="parent-sidebar-menu" data-level="0">
    <li class="menu-item">
      <a href="<?php echo get_the_permalink( $page_for_posts ); ?>"><?php echo get_the_title( $page_for_posts ); ?></a>
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