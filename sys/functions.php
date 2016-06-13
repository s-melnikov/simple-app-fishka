<?php

function debug() {
  echo '<pre>';
  call_user_func_array('var_dump', func_get_args());
  exit;
}

function markdown($markdown) {

  static $parser = null;

  if (!$parser) {
    $parser = new ParsedownExtra();
  }

  return $parser->text($markdown);
}
/*

$connect->insert_id;

$result->fetch_all(MYSQLI_ASSOC);

$connect->affected_rows;

*/