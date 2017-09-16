<?php
require_once('library/library.php');
require_once('library/check_all_key.php');

/**
 * mkhaufillah
 */

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  if (!(isset(getallheaders()['api_key']))) {
    die();
  }

  $api_key = getallheaders()['api_key'];
  $key_all = new check_all_key();

  if (!($key_all -> check_api_key($api_key))) {
    die();
  }

  if (isset(getallheaders()['android_key'])) {
    if (!($key_all -> check_android_key(getallheaders()['android_key']))) {
      die();
    }
  }

  if (!(isset($_POST['user_id']) &&
      isset($_POST['banner_id']) &&
      isset($_POST['token_db']) &&
      isset($_POST['user_token']))) {
        die();
  }

  $user_id    = strip_tags($_POST['user_id']);
  $user_id    = filter_var($user_id, FILTER_SANITIZE_NUMBER_INT);
  $banner_id  = strip_tags($_POST['banner_id']);
  $banner_id  = filter_var($banner_id, FILTER_SANITIZE_NUMBER_INT);
  $token_db   = $_POST['token_db'];
  $user_token = $_POST['user_token'];

  if ($banner_id  == '' ||
      $user_id    == '' ||
      $token_db   == '' ||
      $user_token == '') {
        die();
  }

  $lib    = new library();
  $result = $lib -> banner_click($user_id, $banner_id, $token_db, $user_token);
  echo $result;
}
?>
