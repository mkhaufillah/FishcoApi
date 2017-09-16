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

  if (!(isset($_GET['product_category']) && isset($_GET['page']))) {
    die();
  }

  $product_category = strip_tags($_GET['product_category']);
  $page             = strip_tags($_GET['page']);
  $page             = filter_var($page, FILTER_SANITIZE_NUMBER_INT);

  if ($product_category == '' || $page == '') {
    die();
  }

  $lib    = new library();
  $result = $lib -> get_product_by_category($product_category, $page);
  echo $result;

}
?>
