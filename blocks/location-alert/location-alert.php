<?php

// write_log($args);

// $statuses = $args['statuses'];

$statuses = mdhs_get_statuses();


?>

<div class="location-alert">
  <ul class="location-alert__list">
    <?php foreach($statuses as $status) : ?>
      <li class="location-alert__item">
        <span class="location-alert__location-name">
          <?= $status->cells[0]->value ?>
        </span> 
        <span class="location-alert__status">
          <?= $status->cells[14]->value ?>
        </span>
      </li>
    <?php endforeach; ?>
  </ul>
</div>