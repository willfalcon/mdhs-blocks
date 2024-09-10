<?php 
  $align = isset($block['align']) ? ' align' . $block['align'] : ''; 
?>

<div class="search<?php echo $align; ?>">
  <?php if ( get_field('heading') ) : ?>
    <h2 class="search__heading"><?php the_field('heading'); ?></h2>
  <?php endif; ?>

  <form class="search__form" id="search-form">
    <input class="search__input" name="s" placeholder="<?php the_field('placeholder_text'); ?>" />
    <button class="search__submit" title="Search" aria-label="Search"><svg><use href="#search-icon"></use></svg></button>
  </form>
</div>