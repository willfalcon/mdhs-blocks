<?php 

$report = get_field('report');
if ($report) :
    
    $newdocuments = get_field('reports_with_doc', $report);

?>
  <div class="report-block collapsible">
    <h3 class="report-block__title"><?php echo get_the_title($report); ?></h3>

    <?php if (!empty($newdocuments)) : ?>
    <div class="report-block__latest report">
      <?php $latest = $newdocuments[0]->ID; ?>
      <a class="report__title" href="<?php echo get_the_permalink($latest); ?>"><?php echo get_the_title($latest); ?></a> 
      <a class="report__download" href="<?php echo get_the_permalink($latest); ?>" download aria-label="<?php echo __('Download ', 'mdhs') . get_the_title($latest)?>">
        <i class="fa-solid fa-download"></i> 
      </a>
    </div>

    <div class="report-block__previous">
      <h4 class="report-block__header">
        <button class="report-block__button" aria-label="<?php _e('Expand list of reports.', 'mdhs'); ?>">
          <span><?php if (get_field('dropdown_heading')) : the_field('dropdown_heading'); else: _e('Other Documents in this Series', 'mdhs'); endif;  ?></span>
          <svg class="report-block__caret"><use href="#caret"></use></svg>
        </button>
      </h4>

      <div class="report-block__list">
        <?php $other_documents = array_slice($newdocuments, 1); ?>
        <?php foreach ($other_documents as $document) : $document = $document->ID; ?>
          <div class="report-block__report report">
            <a class="report__title" href="<?php echo get_the_permalink($document); ?>"><?php echo get_the_title($document); ?></a> 
            <a class="report__download" href="<?php echo get_the_permalink($document); ?>" download aria-label="<?php echo __('Download ', 'mdhs') . get_the_title($document)?>">
              <i class="fa-solid fa-download"></i> 
            </a>
          </div>
        <?php endforeach; ?>
      </div>

    </div>
    <?php endif; ?>
  </div>    


<?php

endif;
?>