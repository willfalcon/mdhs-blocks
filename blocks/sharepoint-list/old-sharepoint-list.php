<?php 
 
  $sharepoint_table = get_field('sharepoint_table');
  // $site_id = get_field('site_id', $sharepoint_table->ID);
  // $list_id = get_field('list_id', $sharepoint_table->ID);
  // $sharepoint_columns = call_graph('\/sites/' . $site_id . '\/lists/' . $list_id. '/columns');
  // $items = call_graph('\/sites/' . $site_id . '\/lists/' . $list_id. '/items?expand=fields');

  // $chosen_columns = get_field('columns', $sharepoint_table->ID);
  
?>

 

<!-- <table id="sharepoint-table"> 
  <thead>
    <tr>
      <?php //while (have_rows('columns', $sharepoint_table->ID) ) : the_row(); ?>
        <?php //$column = array_find($sharepoint_columns->value, function($col) {
          //return $col->name == get_sub_field('sharepoint_column');
        //}); ?>
        <th id="<?php// echo $column->name; ?>">
          <?php// echo $column->displayName; ?>
        </th>
      <?php //endwhile; ?>
    </tr>
  </thead>
  <tbody>
    <?php //foreach ($items->value as $item) : ?>
      <?php //$fields = $item->fields; ?>
      <tr id="<?php// echo $item->id; ?>">
        <?php //while (have_rows('columns', $sharepoint_table->ID) ) : the_row(); ?>
          <?php //$name = get_sub_field('sharepoint_column'); ?>
          <td>
            <?php 
            //if (property_exists($fields, $name)) {
             // echo $fields->$name; 
            //}
            ?> 
          </td>
        <?php //endwhile; ?>
      </tr>
    <?php //endforeach; ?>
  </tbody>
</table>

-->
<div id="sharepoint-grid-wrapper" data-table="<?php echo $sharepoint_table->ID; ?>"></div>
