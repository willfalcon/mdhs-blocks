<?php

function get_sp_access_token() {
  $access_token_expires = get_field('sp_access_token_expires', 'options');
  $access_token;
  if (!$access_token_expires || time() > (int)$access_token_expires) {
    $tenant = get_field('sharepoint_tenant_id', 'options');
    $url = 'https://login.microsoftonline.com/' . $tenant . '/oauth2/v2.0/token';
    $response = wp_remote_post($url, array(
      'headers' => array(
        'Content-Type' => 'application/x-www-form-urlencoded'
      ),
      'body' => array(
        'client_id' => get_field( 'sharepoint_client_id', 'options' ),
        'scope' => 'https://graph.microsoft.com/.default',
        'client_secret' => get_field( 'sharepoint_client_secret', 'options'),
        'grant_type' => 'client_credentials'
      )
    ));

    $body = json_decode(wp_remote_retrieve_body($response));
    
    if (property_exists($body, 'error')) {
      if ($body->error == 'unauthorized_client') {
        update_field('sp_token_error', "Client Secret error! Possibly, the client secret is expired.", 'options');
      }
      if ($body->error == 'invalid_client') {
        update_field('sp_token_error', "Client Secret error! Sharepoint doesn't recognize this as a valid client secret. Make sure the right thing was copy/pasted, and that it was pasted exactly.", 'options');
      }
      return $body;
    } else {
      if (get_field('sp_token_error', 'options')) {
        update_field('sp_token_error', null, 'options');
      }
      update_field('sp_access_token', $body->access_token, 'options');
      update_field('sp_access_token_expires', time() + $body->expires_in, 'options');
      return $body->access_token;
    } 

  } else {
    return get_field('sp_access_token', 'options');
  }
}

function call_graph($endpoint, $v1 = false) {
  $access_token = get_sp_access_token();
  
  // write_log($access_token);
  if (is_object($access_token)) {
    if (property_exists($access_token, 'error')) {
      return $access_token;
    }
  }

  $base = $v1 ? 'https://mdhsmsgov-my.sharepoint.com/_api/web' : 'https://graph.microsoft.com/v1.0';
  $url =  $base . $endpoint;

  $headers = $v1 ? array(
      'Authorization' => 'Bearer ' . $access_token,
      'Accept' => 'application/json;odata=verbose'
    ) : array(
      'Authorization' => 'Bearer ' . $access_token
  );

  $response = wp_remote_get($url, array(
    'headers' => $headers
  ));

  $body = json_decode(wp_remote_retrieve_body($response));
  
  return $body;

}


function mdhsb_sp_get_table($data) {
  $table = $data['id'];
  $site_id = get_field('site_id', $table);
  $list_id = get_field('list_id', $table);
  $chosen_columns = get_field('columns', $table);
  global $fields;
  $fields = array_map(function($row) {
    return $row['sharepoint_column'];
  }, $chosen_columns);
  
  $sharepoint_columns = call_graph('\/sites/' . $site_id . '\/lists/' . $list_id. '/columns');


  if (property_exists($sharepoint_columns, 'error')) {
    return 'Something went wrong';
  }

  global $columns;
  $columns = array_values(array_filter($sharepoint_columns->value, function($col) {
    global $fields;
    return in_array($col->name, $fields);
  }));
  
  $mapped_columns = array_map(function($col) {
    global $columns;
    global $this_col;
    $this_col = $col;
    $col['data'] = array_find($columns, function($c) {
      global $this_col;
      return $c->name == $this_col['sharepoint_column'];
    });
    return $col;
  }, $chosen_columns);
  
  $items = call_graph('\/sites/' . $site_id . '\/lists/' . $list_id . '/items?expand=fields');

  $response = new stdClass();
  
  $response->columns = $columns;
  $response->mappedColumns = $mapped_columns;
  $response->items = $items->value;
  return $response;
  
}

function mdhsb_sp_get_columns($data) {
  $table = $data['id'];
  $chosen_columns = get_field('columns', $table);
  $fields = array_map(function($row) {
    return $row['sharepoint_column'];
  }, $chosen_columns);
  return $fields;
}


function mdhsb_set_sharepoint_column_options($field) {
	global $post;
	$site_id;
	$list_id;

	$table_value = get_field('sharepoint_table');
	if ($table_value) {
		$site_id = get_field('site_id', $table_value->ID);
		$list_id = get_field('list_id', $table_value->ID);
	} else if ($post) {
		$site_id = get_field('site_id', $post->ID);
		$list_id = get_field('list_id', $post->ID);
	} else {
		return $field;
	}

	if (!$site_id || !$list_id) {
		return $field; 
	}
	
	$field['choices'] = array();
	$columns = call_graph('\/sites/' . $site_id . '\/lists/' . $list_id. '/columns');
  if (property_exists($columns, 'error')) {
    return $field;
  }

  // write_log($columns);
	foreach($columns->value as $column) {
		$field['choices'][$column->name] = $column->displayName;
	}	
	return $field;
}

add_filter('acf/load_field/name=sharepoint_column', 'mdhsb_set_sharepoint_column_options');
add_filter('acf/load_field/name=document_name_column', 'mdhsb_set_sharepoint_column_options');


function mdhsb_check_sharepoint_settings() {
  $screen = get_current_screen();
  if ($screen->parent_base == 'edit' && $screen->post_type == 'sharepoint-table') {
    $site_id = get_field('site_id', get_the_ID());
    $site = call_graph('\/sites/' . $site_id);

    if (property_exists($site, 'error') && $site->error->message == 'Provided identifier is malformed - id is not valid') {
      echo '<div class="notice notice-error">
        <p>' . __('Error getting the sharepoint list. If your Site ID looks like this: https://mdhsmsgov.sharepoint.com/sites/ProcurementServices, them remove the "https", and try adding a colon before the first slash, like this: mdhsmsgov.sharepoint.com:/sites/ProcurementServices', 'mdhs') . '</p>
      </div>';
    }

    $list_id = get_field('list_id', get_the_ID());
    $list = call_graph('\/sites/' . $site_id . '\/lists');
    if (property_exists($list, 'error')) {
      echo '<div class="notice notice-error">
        <p>' . __('Sharepoint Error Client ', 'mdhs') . '</p>
      </div>';
    }

    if (property_exists($list, 'error') && $list->error->message == 'The provided path does not exist, or does not represent a site') {
      echo '<div class="notice notice-warning">
        <p>' . __('List wasn\'t found. It\'s possible you\'ve got a Sharepoint Site Hostname instead of a site ID. The ID for the Site currently entered is: ', 'mdhs') . $site->id . '</p>
      </div>';
    }
    
  }

}

add_action('admin_notices', 'mdhsb_check_sharepoint_settings');

function mdhsb_sharepoint_client_error_admin_notices() {
  if (get_field('sp_token_error', 'options')) {
    echo '<div class="notice notice-error">
        <h3>Sharepoint Client Secret Error</h3>
        <p>' . get_field('sp_token_error', 'options') . '</p>
      </div>';
  }
}
add_action('admin_notices', 'mdhsb_sharepoint_client_error_admin_notices');