<?php

function mdhs_get_statuses() {
  $statuses = [];
  while(have_rows('location_alert_settings', 'options'))  {
    the_row();

    $sheet = mdhsb_smartsheet_get_cached_sheet(get_sub_field('smartsheet_select'));
    
    global $column;
    $column = get_sub_field('location_status_field');
    $rows = $sheet->rows;
    

    $statuses = array_filter($rows, function($row) {
      $status = array_find($row->cells, function($cell) {
        global $column;
        return $cell->columnId == $column;
      });
      return property_exists($status, 'value') && $status->value !== '';
    });

  };
  return $statuses;
}