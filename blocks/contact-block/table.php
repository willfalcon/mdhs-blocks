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
  if (!$contact_block) {
    return;
  }
  $block_id = $contact_block ? $contact_block->ID : null;

  global $counties;

  $smartsheet_id = get_field( 'smartsheet_select', $block_id );
  global $sheet;
  $sheet = mdhsb_smartsheet_get_cached_sheet($smartsheet_id);
  global $contact_card;
  $contact_card = get_field( 'contact_card', $block_id);

  global $filters;
  $filters = get_field('filter', $block_id);
  
  $filtered_rows = array();
  if (property_exists($sheet, 'rows')) {
    $filtered_rows = array_filter($sheet->rows, function($row) {
      global $filters;
      $field = array_find($row->cells, function($cell) {
        global $filters;
        return $cell->columnId == $filters['filter_field'];
      });
      return property_exists($field, 'value') && $field->value == $filters['value'];
    });
  }
  
  global $columnIds;
  $columnIds = array();
?>

<?php
  if (!function_exists('ss_table_head')) {
    function ss_table_head($field) {
      global $contact_card;
      global $sheet;
      global $fieldKey;
      $fieldKey = $field;
      if ($contact_card[$field]) {
        echo '<th>';
        $col = array_find($sheet->columns, function($column) {
          global $contact_card;
          global $fieldKey;
          return $column->id == $contact_card[$fieldKey];
        });
        global $columnIds;
        $columnIds[$fieldKey] = $col->id;
        echo $col->title;
        echo '</th>';
      }
    }
  }
  if (!function_exists('ss_table_cell')) {
    function ss_table_cell($row, $field) {
      global $contact_card;
      global $sheet;
      global $fieldKey;
      $fieldKey = $field;
      if ($contact_card[$field]) {
        echo '<td>';
        $cell = array_find($row->cells, function($cell) {
          global $contact_card;
          global $field;
          global $columnIds;
          global $fieldKey;
          return $cell->columnId == $columnIds[$fieldKey];
        });
        if (property_exists($cell, 'displayValue')) {
          $email = filter_var($cell->displayValue, FILTER_VALIDATE_EMAIL);
          $phone = better_format_phone($cell->displayValue);
          if ($email) {
            echo '<a href="mailto:' . $email . '">' . $cell->displayValue . '</a>';
          } else if (is_array($phone)) {
            echo '<a href="' . $phone['href'] . '">' . $phone['display'] . '</a>';
          } else {
            echo $cell->displayValue;
          }
        }
        echo '</td>';
      }
    }
  }
?>

<div class="contact-block-table"
  data-smartsheet-id="<?php the_field( 'smartsheet_select', $block_id ); ?>"
  data-search-field="<?php the_field('search_field', $block_id); ?>"
  <?php if (have_rows('filter', $block_id)): while (have_rows('filter', $block_id)) : the_row(); ?>
    data-filter-field="<?php the_sub_field('filter_field'); ?>"
    data-filter-condition="<?php the_sub_field('condition'); ?>"
    data-filter-value="<?php the_sub_field( 'value' ); ?>"
    data-post-id="<?php echo $block_id; ?>"
  <?php endwhile; endif; ?>
>
  <h2 class="contact-block__heading"><?php echo $contact_block->post_title; ?></h2>
  <table>
    <thead>
      <tr>
        <?php
        $headings = get_field('table_headings_to_include');
          foreach ($headings as $heading) {
            ss_table_head($heading);
          } 
        ?>
        
    </tr>
    </thead>
    <tbody>
      <?php foreach ($filtered_rows as $row) : ?>
        <tr>
          <?php 
            foreach ($headings as $heading) {
              ss_table_cell($row, $heading);
            }
          ?>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
