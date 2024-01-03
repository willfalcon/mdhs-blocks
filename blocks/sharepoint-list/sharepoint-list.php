<?php 
 
  $sharepoint_table = get_field('sharepoint_table');
  
  // $site_id = get_field('site_id', $sharepoint_table->ID);
  // $list_id = get_field('list_id', $sharepoint_table->ID);
  // $sharepoint_columns = call_graph('\/sites/' . $site_id . '\/lists/' . $list_id. '/columns');
  // $items = call_graph('\/sites/' . $site_id . '\/lists/' . $list_id. '/items?expand=fields');

  $filters = get_field('filters');
  $hide_columns = get_field('hide_columns');
?>

 
<form class="sharepoint-list-search" data-table="<?php echo $sharepoint_table->ID; ?>">
  <input class="sharepoint-list-search-box" type="text" name="search-term" />
  <button class="sharepoint-list-search-button" type="submit">Search</button>
</form>
<div class="sharepoint-list loading" 
  data-table="<?php echo $sharepoint_table->ID; ?>" 
  data-filters="<?php if ($filters) : echo htmlspecialchars(json_encode($filters), ENT_QUOTES, 'UTF-8'); endif; ?>"
  data-hide-columns="<?php if ($hide_columns) : echo htmlspecialchars(json_encode($hide_columns), ENT_QUOTES, 'UTF-8'); endif; ?>"></div>
