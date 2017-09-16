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
      isset($_POST['address_name']) &&
      isset($_POST['address_street']) &&
      isset($_POST['address_village']) &&
      isset($_POST['address_subdistrict']) &&
      isset($_POST['address_city']) &&
      isset($_POST['address_province']) &&
      isset($_POST['address_regional']) &&
      isset($_POST['address_postal']) &&
      isset($_POST['address_id_province']) &&
      isset($_POST['address_id_city']) &&
      isset($_POST['address_id_subdistrict']) &&
      isset($_POST['token_db']) &&
      isset($_POST['user_token']))) {
        die();
      }

  $user_id                = strip_tags($_POST['user_id']);
  $user_id                = filter_var($user_id, FILTER_SANITIZE_NUMBER_INT);
  $address_name           = strip_tags($_POST['address_name']);
  $address_street         = strip_tags($_POST['address_street']);
  $address_village        = strip_tags($_POST['address_village']);
  $address_subdistrict    = strip_tags($_POST['address_subdistrict']);
  $address_city           = strip_tags($_POST['address_city']);
  $address_province       = strip_tags($_POST['address_province']);
  $address_regional       = strip_tags($_POST['address_regional']);
  $address_postal         = strip_tags($_POST['address_postal']);
  $address_postal         = filter_var($address_postal, FILTER_SANITIZE_NUMBER_INT);
  $address_id_province    = strip_tags($_POST['address_id_province']);
  $address_id_province    = filter_var($address_id_province, FILTER_SANITIZE_NUMBER_INT);
  $address_id_city        = strip_tags($_POST['address_id_city']);
  $address_id_city        = filter_var($address_id_city, FILTER_SANITIZE_NUMBER_INT);
  $address_id_subdistrict = strip_tags($_POST['address_id_subdistrict']);
  $address_id_subdistrict = filter_var($address_id_subdistrict, FILTER_SANITIZE_NUMBER_INT);
  $token_db               = $_POST['token_db'];
  $user_token             = $_POST['user_token'];

  if ($user_id                == '' ||
      $address_name           == '' ||
      $address_street         == '' ||
      $address_village        == '' ||
      $address_subdistrict    == '' ||
      $address_city           == '' ||
      $address_province       == '' ||
      $address_regional       == '' ||
      $address_postal         == '' ||
      $address_id_province    == '' ||
      $address_id_city        == '' ||
      $address_id_subdistrict == '' ||
      $token_db               == '' ||
      $user_token             == '') {
        die();
      }

  $lib    = new library();
  $result = $lib->add_address($user_id,
                              $address_name,
                              $address_street,
                              $address_village,
                              $address_subdistrict,
                              $address_city,
                              $address_province,
                              $address_regional,
                              $address_postal,
                              $address_id_province,
                              $address_id_city,
                              $address_id_subdistrict,
                              $token_db,
                              $user_token);
  echo $result;
}
?>
