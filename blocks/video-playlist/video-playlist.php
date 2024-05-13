<?php 
  $classes = 'video-playlist';
  if (array_key_exists('align', $block)) {
    $classes .= ' align' . $block['align'];
  }
   
  $video_content = get_field('video_content');
  
  $videos = get_field('videos', $video_content->ID);
  $cover_video = $videos[0];

  $cover_oembed = wp_oembed_get($cover_video['url']);

  if (count($videos) <= 1) {
    $classes .= ' is-single';
  }

?>

<div class="<?= $classes ?>">
  <h3 class="video-playlist__title"><?= $video_content->post_title ?></h3>
  <?php if ($cover_oembed) : ?>
    <div class="video-playlist__video">
      <div class="video-playlist__video-wrapper">
        <?= $cover_oembed ?>
      </div>
    </div>
  <?php endif; ?>

  <?php if (count($videos) > 1) : ?>
    <div class="video-playlist__playlist">
      <heading class="video-playlist__playlist-header"><?php the_field('playlist_header') ?></heading>
      <?php $i = 0; foreach ($videos as $video) : ?>
        <button class="video-playlist__button<?= $i == 0 ? ' active' : ''; ?>" data-url="<?= $video['url'] ?>" data-id="<?= $i ?>">
          <?= $video['video_title'] ?>
        </button>
      <?php $i++; endforeach; ?>
    </div>

    <div class="video-playlist__controls">
      <button class="video-playlist__expand button">
        <span class="visually-hidden">Expand Video</span> 
        <i class="fa-solid fa-maximize"></i>
      </button>
    </div>
  <?php endif; ?>

</div>