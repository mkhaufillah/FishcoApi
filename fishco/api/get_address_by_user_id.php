<?php
require_once('library/library.php');
require_once('library/check_all_key.php');

/**
 * mkhaufillah
 */

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

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

  if (!(isset($_GET['user_id']) && isset($_GET['token_db']) && isset($_GET['user_token']))) {
    die();
  }

  $user_id    = strip_tags($_GET['user_id']);
  $user_id    = filter_var($user_id, FILTER_SANITIZE_NUMBER_INT);
  $token_db   = $_GET['token_db'];
  $user_token = $_GET['user_token'];

  if ($user_id == '' || $token_db == '' || $user_token == '') {
    die();
  }

  $lib    = new library();
  $result = $lib -> get_address_by_user_id($user_id, $token_db, $user_token);
  echo $result;

}
?>
