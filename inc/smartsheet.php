<?php

function mdhsb_smartsheet_get_sheets() {
  
  $url = 'https://api.smartsheet.com/2.0/sheets';

  $response = wp_remote_get($url, array(
    'headers' => array(
      'Authorization' => 'Bearer ' . get_field('smartsheet_access_token', 'options')
    )
  ));
  $data = wp_remote_retrieve_body($response);
  
  return json_decode($data);
}

function mdhsb_set_smartsheet_select_options($field) {
	$field['choices'] = array();
	$stuff = mdhsb_smartsheet_get_sheets();
	$sheets = $stuff->data;
	foreach ($sheets as $sheet) {
		$field['choices'][$sheet->id] = $sheet->name;
	}
	return $field;
}
add_filter('acf/load_field/name=smartsheet_select', 'mdhsb_set_smartsheet_select_options');

function mdhsb_smartsheet_get_sheet($data) {
  $url = 'https://api.smartsheet.com/2.0/sheets/' . $data['id'];
  
  $response = wp_remote_get($url, array(
    'headers' => array(
      'Authorization' => 'Bearer ' . get_field('smartsheet_access_token', 'options')
    )
  ));

  $data = wp_remote_retrieve_body($response);
  return json_decode($data);
}

function mdhsb_smartsheet_get_rows($data) {
  $page = $data['page'] ? $data['page'] : 1;
  
  $url = 'https://api.smartsheet.com/2.0/sheets/' . $data['id'] . '?pageSize=50&page=' . $page;

  $response = wp_remote_get($url, array(
    'headers' => array(
      'Authorization' => 'Bearer ' . get_field('smartsheet_access_token', 'options')
    )
  ));
  $data = wp_remote_retrieve_body($response);
  return json_decode($data);
}

function mdhsb_smartsheet_search_sheet($data) {
  $id = $data['id'];
  $query = $data->get_params()['query'];
  
  $url = 'https://api.smartsheet.com/2.0/search/sheets/' . $id . '?query=' . $query;
  
  $response = wp_remote_get($url, array(
    'headers' => array(
      'Authorization' => 'Bearer ' . get_field('smartsheet_access_token', 'options')
    )
  ));
  $data = wp_remote_retrieve_body($response);
  return json_decode($data);
  
}


function mdhsb_smartsheet_get_cached_sheet($sheet) {
  $expiry = get_field( 'smartsheet_cache_expiry', 'options' );
  
  if (!$expiry || time() > (int)$expiry) {
    $data = mdhsb_smartsheet_get_sheet(array('id' => $sheet));
    update_field('smartsheet_cache', json_encode($data), 'options');
    update_field('smartsheet_cache_expiry', time() + 30, 'options');
    return $data;
  } else {
    $cache = get_field( 'smartsheet_cache', 'options' );
    return json_decode($cache);
  }
}


function mdhsb_smartsheet_county_search($data) {
  
  $sheet = $data['sheet'];
  $counties = $data['counties'];
  global $searchField;
  $searchField = $data['searchField'];
  $data = mdhsb_smartsheet_get_cached_sheet($sheet);

  $columns = $data->columns;
  $rows = $data->rows;
  
  global $county_column;
  $county_column = array_find($columns, function($column) {
    global $searchField;
    return $column->id == $searchField;
  });
  
  $options = $county_column->options;

  
  $results = array();

  foreach (explode(',', $counties) as $county) {
    global $option;
    $option = $county;

    $rows_with_county = array_filter($rows, function($row) {
      $cells = $row->cells;
      $county_cell = array_find($cells, function($cell) {
        global $county_column;
        return $cell->columnId == $county_column->id;
      });
      $counties = array_map('strtolower', explode(', ', $county_cell->value));
      
      global $option;
      
      return in_array($option, $counties); 
    });
    
    $results[] = array(
      'county' => $county,
      'locations' => $rows_with_county
    );
  }

  return $results;
}


