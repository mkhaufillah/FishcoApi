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

  if (!(isset($_GET['product_id']) && isset($_GET['key']) && isset($_GET['page']))) {
    die();
  }

  $product_id = strip_tags($_GET['product_id']);
  $product_id = filter_var($product_id, FILTER_SANITIZE_NUMBER_INT);
  $key        = strip_tags($_GET['key']);
  $page       = strip_tags($_GET['page']);
  $page       = filter_var($page, FILTER_SANITIZE_NUMBER_INT);

  if ($product_id == '' || $key == '' || $page == '') {
    die();
  }

  $lib    = new library();
  $result = $lib -> get_recomended_product($product_id, $key, $page);
  echo $result;

}
?>
