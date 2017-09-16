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

  if (!(isset($_POST['product_id']) &&
      isset($_POST['user_id']) &&
      isset($_POST['address_id']) &&
      isset($_POST['cart_qty']) &&
      isset($_POST['cart_weight']) &&
      isset($_POST['cart_detail']) &&
      isset($_POST['cart_shipping_cost']) &&
      isset($_POST['cart_courier_desc']) &&
      isset($_POST['cart_pay_method']) &&
      isset($_POST['cart_code']) &&
      isset($_POST['cart_token']) &&
      isset($_POST['cart_status']) &&
      isset($_POST['cart_additional_price']) &&
      isset($_POST['cart_price_total']) &&
      isset($_POST['token_db']) &&
      isset($_POST['user_token']))) {
        die();
      }

  $product_id            = strip_tags($_POST['product_id']);
  $product_id            = filter_var($product_id, FILTER_SANITIZE_NUMBER_INT);
  $user_id               = strip_tags($_POST['user_id']);
  $user_id               = filter_var($user_id, FILTER_SANITIZE_NUMBER_INT);
  $address_id            = strip_tags($_POST['address_id']);
  $address_id            = filter_var($address_id, FILTER_SANITIZE_NUMBER_INT);
  $cart_qty              = strip_tags($_POST['cart_qty']);
  $cart_qty              = filter_var($cart_qty, FILTER_SANITIZE_NUMBER_INT);
  $cart_weight           = strip_tags($_POST['cart_weight']);
  $cart_weight           = filter_var($cart_weight, FILTER_SANITIZE_NUMBER_INT);
  $cart_detail           = strip_tags($_POST['cart_detail']);
  $cart_shipping_cost    = strip_tags($_POST['cart_shipping_cost']);
  $cart_shipping_cost    = filter_var($cart_shipping_cost, FILTER_SANITIZE_NUMBER_INT);
  $cart_courier_desc     = strip_tags($_POST['cart_courier_desc']);
  $cart_pay_method       = strip_tags($_POST['cart_pay_method']);
  $cart_code             = strip_tags($_POST['cart_code']);
  $cart_code             = filter_var($cart_code, FILTER_SANITIZE_NUMBER_INT);
  $cart_token            = strip_tags($_POST['cart_token']);
  $cart_status           = strip_tags($_POST['cart_status']);
  $cart_additional_price = strip_tags($_POST['cart_additional_price']);
  $cart_additional_price = filter_var($cart_additional_price, FILTER_SANITIZE_NUMBER_INT);
  $cart_price_total      = strip_tags($_POST['cart_price_total']);
  $cart_price_total      = filter_var($cart_price_total, FILTER_SANITIZE_NUMBER_INT);
  $token_db              = $_POST['token_db'];
  $user_token            = $_POST['user_token'];

  if ($product_id            == '' ||
      $user_id               == '' ||
      $address_id            == '' ||
      $cart_qty              == '' ||
      $cart_weight           == '' ||
      $cart_detail           == '' ||
      $cart_shipping_cost    == '' ||
      $cart_courier_desc     == '' ||
      $cart_pay_method       == '' ||
      $cart_code             == '' ||
      $cart_token            == '' ||
      $cart_status           == '' ||
      $cart_additional_price == '' ||
      $cart_price_total      == '' ||
      $token_db              == '' ||
      $user_token             == '') {
        die();
      }

  $lib    = new library();
  $result = $lib->put_into_cart($product_id,
                                $user_id,
                                $address_id,
                                $cart_qty,
                                $cart_weight,
                                $cart_detail,
                                $cart_shipping_cost,
                                $cart_courier_desc,
                                $cart_pay_method,
                                $cart_code,
                                $cart_token,
                                $cart_status,
                                $cart_additional_price,
                                $cart_price_total,
                                $token_db,
                                $user_token);
  echo $result;
}
?>
