<?php

if (!function_exists('array_find')) {
    function array_find($xs, $f) {
      foreach ($xs as $x) {
        if (call_user_func($f, $x) === true)
        return $x;
    }
    return null;
  }
}

if (!function_exists('array_find_index')) {
  function array_find_index($xs, $f) {
    $i = 0;
    foreach ($xs as $x) {
      if (call_user_func($f, $x) === true) {
        return $i;
      } else {
        $i++;
      }
    }
    return null;
  }
}  


/* Used by: Related block */
if (!function_exists('check_blocks')) {
  function check_blocks($blocks, $search_block) {
    if (!is_array($blocks)) {
      $blocks = parse_blocks($blocks);
    }
    foreach($blocks as $block) {
      if ($block['blockName'] == $search_block) {
        return $block;
      } 
      if ($block['innerBlocks']) {
        $returned = check_blocks($block['innerBlocks'], $search_block);
        if ($returned) {
          return $returned;
        }
      }
    }
    return null;
  }
}