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

  if (!(isset($_POST['user_name']) &&
      isset($_POST['user_phone']) &&
      isset($_POST['user_birth_date']) &&
      isset($_POST['user_gender']) &&
      isset($_POST['user_email']) &&
      isset($_POST['user_password']) &&
      isset($_POST['token_db']))) {
        die();
      }

  $user_name       = strip_tags($_POST['user_name']);
  $user_name       = filter_var($user_name, FILTER_SANITIZE_STRING);
  $user_name       = filter_var($user_name, FILTER_SANITIZE_SPECIAL_CHARS);
  $user_phone      = strip_tags($_POST['user_phone']);
  $user_phone      = filter_var($user_phone, FILTER_SANITIZE_NUMBER_INT);
  $user_birth_date = strip_tags($_POST['user_birth_date']);
  $user_birth_date = filter_var($user_birth_date, FILTER_SANITIZE_NUMBER_INT);
  $user_gender     = strip_tags($_POST['user_gender']);
  $user_gender     = filter_var($user_gender, FILTER_SANITIZE_STRING);
  $user_gender     = filter_var($user_gender, FILTER_SANITIZE_SPECIAL_CHARS);
  $user_email      = strip_tags($_POST['user_email']);
  $user_email      = filter_var($user_email, FILTER_SANITIZE_EMAIL);
  $user_password   = strip_tags($_POST['user_password']);
  $user_password   = filter_var($user_password, FILTER_SANITIZE_STRING);
  $user_password   = filter_var($user_password, FILTER_SANITIZE_SPECIAL_CHARS);
  $token_db        = $_POST['token_db'];

  if ($user_name       == '' ||
      $user_phone      == '' ||
      $user_birth_date == '' ||
      $user_gender     == '' ||
      $user_email      == '' ||
      $user_password   == '' ||
      $token_db        == '') {
        die();
      }

  $lib    = new library();
  $result = $lib->register($user_name,
                           $user_phone,
                           $user_birth_date,
                           $user_gender,
                           $user_email,
                           $user_password,
                           $token_db);
  echo $result;
}
?>
