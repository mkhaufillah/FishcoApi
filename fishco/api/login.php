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

  if (!(isset($_POST['user_email']) &&
      isset($_POST['user_password']) &&
      isset($_POST['user_token']))) {
        die();
  }

  $user_email    = strip_tags($_POST['user_email']);
  $user_email    = filter_var($user_email, FILTER_SANITIZE_EMAIL);
  $user_password = strip_tags($_POST['user_password']);
  $user_password = filter_var($user_password, FILTER_SANITIZE_STRING);
  $user_password = filter_var($user_password, FILTER_SANITIZE_SPECIAL_CHARS);
  $user_token = $_POST['user_token'];

  if ($user_token    == '' ||
      $user_email    == '' ||
      $user_password == '') {
        die();
  }

  $lib    = new library();
  $result = $lib->login($user_email, $user_password, $user_token);
  echo $result;
}
?>
