<?php

$existing = get_field('use_existing');
global $post;

if (!$existing && !$post) {
  return;
}


while (have_rows('options', $existing ? $existing : null)) : the_row();

if (!get_sub_field('heading') && !get_sub_field('text')) {
  continue;
}

$hash_string = $existing . get_sub_field('heading');
$key = hash('md5', $hash_string);


$not_time = false;
if (get_sub_field('schedule_announcement')) {
  
  $now = current_time('mysql'); 
  $schedule = get_sub_field('schedule');

  $start = $schedule['start'];
  $end = $schedule['end'];
  

  if (($start && $now < $start) || ($end && $now >= $end)) {
    continue;
  }

  
}

  $effect = get_sub_field('effect');

?>

<div class="announcement <?php echo $is_preview ? 'preview' : 'closed'; ?> <?php echo $effect; ?>"<?php if ($effect == 'delay') : echo 'data-delay="' . get_sub_field('delay') . '"'; endif; ?> data-key="<?php echo $key; ?>">
  <header class="announcement__header"><?php _e('Announcement', 'mdhs'); ?></header>
  <div class="announcement__body">
    <h3 class="announcement__title"><?php the_sub_field('heading'); ?></h3>
    <div class="announcement__text"><?php the_sub_field('text'); ?></div>
  </div>
  <button class="announcement__expander"><span>+</span></button>
</div>


<?php endwhile; ?>

