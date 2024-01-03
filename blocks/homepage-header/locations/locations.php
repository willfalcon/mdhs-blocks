<?php 
  $contact_block = get_field('contact_block'); 
  $id = $contact_block->ID;
?>
<div class="hh__locations" id="location-status" 
  data-sheet="<?php the_field('smartsheet_select'); ?>" 
  data-contact-block="<?php echo $id; ?>"
  data-search-field="<?php the_field('search_field', $id); ?>">

    <div class="location location-card">
      <header class="location__header">
        <h2><?php _e('Find Help Now', 'mdhs'); ?></h2>
      </header>
      
      <div class="location__body-wrapper">
        
        <div class="location__search-form">
          <label for="location-search"><?php the_field('search_label', $id); ?></label>
          <div class="location__input-wrapper">
            <input name="location-search" id="location-search" type="text" />
            <span class="location__input-suggestion"></span>
          </div>
        </div>

        <?php 
          $sheet = get_field( 'smartsheet_select' ); 
          global $counties;
        ?>
        <ul class="location__list" data-sheet="<?php echo $sheet; ?>">
          <?php foreach ($counties as $option) : // 82 ?>
            <li class="location__item" data-location="<?php echo $option; ?>" style="display: none;">
              <button id="<?php echo strtolower($option); ?>"><?php echo $option; ?> <i class="fa-solid fa-right-long"></i></button>
              <ul class="location__sub-list"></ul>
            </li>
          <?php endforeach; ?>
        </ul>

        <div class="location-result">
          <h3 class="location-result__county"></h3>
        </div>
        
        <div class="location__loader"><i class="fa-solid fa-spinner"></i></div>
      </div>
    </div>
  </div>