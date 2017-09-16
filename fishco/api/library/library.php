<?php
require_once('koneksi.php');
require_once('m_crypt.php');

/**
 * mkhaufillah
 */

class library extends koneksi {

  //======== System library ===================================================

  private function json_error($string) {
    return json_encode(array('value' => 0, 'message' => $string));
  }

  private function check_token_db($string, $token_db) {
    $m_crypt   = new m_crypt();
    $decrypted = $m_crypt -> decrypt($token_db);

    if ($string === $decrypted)
    return true;

    return false;
  }

  private function check_email_phone($user_email, $user_phone) {
    $sql_check_email_register   = "SELECT user_id FROM user WHERE user_email = '$user_email'
                                   OR user_phone = $user_phone";
    $query_check_email_register = $this -> db -> prepare($sql_check_email_register);
    $query_check_email_register -> execute();

    if ($value_check_email_register = $query_check_email_register -> fetch(PDO::FETCH_OBJ))
    return true;

    return false;
  }

  private function check_email_phone_admin($admin_email, $admin_phone) {
    $sql_check_email_register   = "SELECT admin_id FROM admin WHERE admin_email = '$admin_email'
                                   OR admin_phone = $admin_phone";
    $query_check_email_register = $this -> db -> prepare($sql_check_email_register);
    $query_check_email_register -> execute();

    if ($value_check_email_register = $query_check_email_register -> fetch(PDO::FETCH_OBJ))
    return true;

    return false;
  }

  private function product_show_partial($sql_product) {
    $query_product = $this -> db -> prepare($sql_product);
    $query_product -> execute();

    $result_product    = array();
    $condition_product = false;

    while ($value_product = $query_product -> fetch(PDO::FETCH_OBJ)) {
      $condition_product = true;
      $product_id        = $value_product -> product_id;
      $product_name      = $value_product -> product_name;
      $product_price     = $value_product -> product_price;
      $product_photo1    = $value_product -> product_photo1;
      $address_city      = $value_product -> address_city;
      $address_province  = $value_product -> address_province;

      array_push($result_product, array(
        'product_id'       => $product_id,
        'product_name'     => $product_name,
        'product_price'    => $product_price,
        'product_photo1'   => $product_photo1,
        'address_city'     => $address_city,
        'address_province' => $address_province
      ));
    }

    if ($condition_product)
    return json_encode(array('value' => 1, 'result_product' => $result_product));

    return $this -> json_error('Upss!! Produk tidak tersedia');
  }

  private function product_show_full($sql_product) {
    $query_product = $this -> db -> prepare($sql_product);
    $query_product -> execute();

    $result_product    = array();
    $condition_product = false;

    if ($value_product = $query_product -> fetch(PDO::FETCH_OBJ)) {
      $condition_product    = true;
      $product_id           = $value_product -> product_id;
      $user_id              = $value_product -> user_id;
      $address_id           = $value_product -> address_id;
      $product_name         = $value_product -> product_name;
      $product_price        = $value_product -> product_price;
      $product_qty          = $value_product -> product_qty;
      $product_weight       = $value_product -> product_weight;
      $product_description  = $value_product -> product_description;
      $product_category     = $value_product -> product_category;
      $product_sold         = $value_product -> product_sold;
      $product_seeing       = $value_product -> product_seeing;
      $product_star         = $value_product -> product_star;
      $product_photo1       = $value_product -> product_photo1;
      $product_photo2       = $value_product -> product_photo2;
      $product_photo3       = $value_product -> product_photo3;
      $product_photo4       = $value_product -> product_photo4;
      $product_photo5       = $value_product -> product_photo5;
      $product_courier_desc = $value_product -> product_courier_desc;
      $address_city         = $value_product -> address_city;
      $address_province     = $value_product -> address_province;
      $address_id_city      = $value_product -> address_id_city;

      array_push($result_product, array(
        'product_id'           => $product_id,
        'user_id'              => $user_id,
        'address_id'           => $address_id,
        'product_name'         => $product_name,
        'product_price'        => $product_price,
        'product_qty'          => $product_qty,
        'product_weight'       => $product_weight,
        'product_description'  => $product_description,
        'product_category'     => $product_category,
        'product_sold'         => $product_sold,
        'product_seeing'       => $product_seeing,
        'product_star'         => $product_star,
        'product_photo1'       => $product_photo1,
        'product_photo2'       => $product_photo2,
        'product_photo3'       => $product_photo3,
        'product_photo4'       => $product_photo4,
        'product_photo5'       => $product_photo5,
        'product_courier_desc' => $product_courier_desc,
        'address_city'         => $address_city,
        'address_province'     => $address_province,
        'address_id_city'      => $address_id_city
      ));
    }

    if ($condition_product)
    return json_encode(array('value' => 1, 'result_product' => $result_product));

    return $this -> json_error('Upss!! Produk kosong');
  }

  private function check_user_token_lib($user_id, $user_token) {
    $sql_user   = "SELECT user_token FROM user WHERE user_id = $user_id";
    $query_user = $this -> db -> prepare($sql_user);
    $query_user -> execute();

    if ($value_user_token = $query_user -> fetch(PDO::FETCH_OBJ)) {
      if ($user_token === $value_user_token -> user_token) {
        return true;
      }
      return false;
    }
    return false;
  }

  public function generate_random_string($length = 32) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
  }

  //======= User library ====================================================

  public function register($user_name,
                           $user_phone,
                           $user_birth_date,
                           $user_gender,
                           $user_email,
                           $user_password,
                           $token_db) {
    if (!($this -> check_token_db($user_phone.'_&_'.
                                $user_email.'_&_'.
                                $user_password, $token_db)))
    return $this -> json_error('Upss!! Token database bermasalah');

    if ($this -> check_email_phone($user_email, $user_phone))
    return $this -> json_error('Upss!! Email/ phone sudah digunakan');

    $user_password = password_hash($user_password, PASSWORD_DEFAULT);

    $rand_string = $this -> generate_random_string();

    $sql_register = "INSERT INTO user (user_name,
                                       user_email,
                                       user_phone,
                                       user_password,
                                       user_gender,
                                       user_birth_date,
                                       user_token)
                    VALUES ('$user_name',
                            '$user_email',
                             $user_phone,
                            '$user_password',
                            '$user_gender',
                            '$user_birth_date',
                            '$rand_string')";
    $query_register = $this -> db -> prepare($sql_register);
    $query_register -> execute();

    return json_encode(array('value' => 1, 'message' => 'Sukses, Silahkan verifikasi email anda'));
  }

  public function login($user_email, $user_password, $user_token) {
    $sql_login = "SELECT * FROM user WHERE user_email = '$user_email'";
    $query_login = $this -> db -> prepare($sql_login);
    $query_login -> execute();

    if ($value_login = $query_login -> fetch(PDO::FETCH_OBJ)) {
      if (!(password_verify($user_password, $value_login -> user_password)))
      return $this -> json_error('Upss!! Password salah');

      if ($value_login -> user_email_verified == 0)
      return $this -> json_error('Upss!! Silahkan check email untuk verifikasi akun');

      $sql_set_token   = "UPDATE user SET user_token='$user_token' WHERE user_email = '$user_email'";
      $query_set_token = $this -> db -> prepare($sql_set_token);
      $query_set_token -> execute();

      $result_user = array(array(
        'user_id'             => $value_login -> user_id,
        'cooperative_id'      => $value_login -> cooperative_id,
        'user_name'           => $value_login -> user_name,
        'user_email'          => $value_login -> user_email,
        'user_phone'          => $value_login -> user_phone,
        'user_gender'         => $value_login -> user_gender,
        'user_birth_date'     => $value_login -> user_birth_date,
        'user_birth_place'    => $value_login -> user_birth_place,
        'user_date_register'  => $value_login -> user_date_register,
        'user_photo_profile'  => $value_login -> user_photo_profile,
        'user_nik'            => $value_login -> user_nik,
        'user_position'       => $value_login -> user_position,
        'user_religion'       => $value_login -> user_religion,
        'user_status'         => $value_login -> user_status,
        'user_token'          => $user_token,
        'user_phone_verified' => $value_login -> user_phone_verified,
        'user_email_verified' => $value_login -> user_email_verified
      ));
      return json_encode(array('value' => 1, 'result_user' => $result_user));
    }
    return $this -> json_error('Upss!! Email salah');
  }

  public function check_user_token($user_id, $user_token) {
    if (!($this -> check_user_token_lib($user_id, $user_token))) {
      return $this -> json_error('Upss!! Sesi berakhir silahkan login ulang');
    }
    return json_encode(array('value' => 1));
  }

  public function get_banner() {
    $sql_banner   = "SELECT * FROM banner WHERE banner_status = 1";
    $query_banner = $this -> db -> prepare($sql_banner);
    $query_banner -> execute();

    $result_banner    = array();
    $condition_banner = false;

    while ($value_banner = $query_banner -> fetch(PDO::FETCH_OBJ)) {
      $condition_banner   = true;
      $banner_id          = $value_banner -> banner_id;
      $banner_image       = $value_banner -> banner_image;
      $banner_publisher   = $value_banner -> banner_publisher;
      $banner_description = $value_banner -> banner_description;
      $banner_link        = $value_banner -> banner_link;

      array_push($result_banner, array(
        'banner_id'          => $banner_id,
        'banner_image'       => $banner_image,
        'banner_publisher'   => $banner_publisher,
        'banner_description' => $banner_description,
        'banner_link'        => $banner_link
      ));
    }

    if ($condition_banner)
    return json_encode(array('value' => 1, 'result_banner' => $result_banner));

    return $this -> json_error('Upss!! Banner kosong');
  }

  public function get_top_product($page) {
    if ($page == 0) {
      $sql_product = "SELECT product_id,
                             product_name,
                             product_price,
                             product_photo1,
                             address_city,
                             address_province FROM product
                      INNER JOIN address USING (address_id)
                      ORDER BY product_star DESC LIMIT 9";
    } else {
      $page = $page*9;
      $sql_product = "SELECT product_id,
                             product_name,
                             product_price,
                             product_photo1,
                             address_city,
                             address_province FROM product
                      INNER JOIN address USING (address_id)
                      ORDER BY product_star DESC LIMIT $page, 9";
    }
    return $this -> product_show_partial($sql_product);
  }

  public function banner_click($user_id, $banner_id, $token_db, $user_token) {
    if (!($this -> check_token_db($user_id.'_&_'.$banner_id, $token_db)))
    return $this -> json_error('Upss!! Token database bermasalah');

    if (!($this -> check_user_token_lib($user_id, $user_token)))
    return $this -> json_error('Upss!! Token user bermasalah');

    $sql_banner_click = "INSERT INTO banner_click (banner_id, user_id)
                      VALUES ($banner_id, $user_id)";
    $query_banner_click = $this -> db -> prepare($sql_banner_click);
    $query_banner_click -> execute();

    return json_encode(array('value' => 1));
  }

  public function get_latest_product($page) {
    if ($page == 0) {
      $sql_product = "SELECT product_id,
                             product_name,
                             product_price,
                             product_photo1,
                             address_city,
                             address_province FROM product
                      INNER JOIN address USING (address_id)
                      ORDER BY product_id DESC LIMIT 9";
    } else {
      $page = $page*9;
      $sql_product = "SELECT product_id,
                             product_name,
                             product_price,
                             product_photo1,
                             address_city,
                             address_province FROM product
                      INNER JOIN address USING (address_id)
                      ORDER BY product_id DESC LIMIT $page, 9";
    }
    return $this -> product_show_partial($sql_product);
  }

  public function get_product_by_category($product_category, $page) {
    if ($page == 0) {
      $sql_product = "SELECT product_id,
                             product_name,
                             product_price,
                             product_photo1,
                             address_city,
                             address_province FROM product
                      INNER JOIN address USING (address_id)
                      WHERE product_category = '$product_category'
                      ORDER BY product_id DESC LIMIT 9";
    } else {
      $page = $page*9;
      $sql_product = "SELECT product_id,
                             product_name,
                             product_price,
                             product_photo1,
                             address_city,
                             address_province FROM product
                      INNER JOIN address USING (address_id)
                      WHERE product_category = '$product_category'
                      ORDER BY product_id DESC LIMIT $page, 9";
    }
    return $this -> product_show_partial($sql_product);
  }

  public function get_product_by_id($product_id) {
    $sql_product = "SELECT * FROM product
                    INNER JOIN address USING (address_id)
                    WHERE product_id = '$product_id'";
    return $this -> product_show_full($sql_product);
  }

  public function get_recomended_product($product_id, $key, $page) {
    if ($page == 0) {
      $sql_product = "SELECT product_id,
                             product_name,
                             product_price,
                             product_photo1,
                             address_city,
                             address_province FROM product
                      INNER JOIN address USING (address_id)
                      WHERE product_name LIKE '%$key%' AND product_id != $product_id
                      ORDER BY product_id DESC LIMIT 4";
    } else {
      $page = $page*4;
      $sql_product = "SELECT product_id,
                             product_name,
                             product_price,
                             product_photo1,
                             address_city,
                             address_province FROM product
                      INNER JOIN address USING (address_id)
                      WHERE product_name LIKE '%$key%' AND product_id != $product_id
                      ORDER BY product_id DESC LIMIT $page, 4";
    }
    return $this -> product_show_partial($sql_product);
  }

  public function get_search_product($key, $page) {
    if ($page == 0) {
      $sql_product = "SELECT product_id,
                             product_name,
                             product_price,
                             product_photo1,
                             address_city,
                             address_province FROM product
                      INNER JOIN address USING (address_id)
                      WHERE product_name LIKE '%$key%'
                      ORDER BY product_id DESC LIMIT 9";
    } else {
      $page = $page*9;
      $sql_product = "SELECT product_id,
                             product_name,
                             product_price,
                             product_photo1,
                             address_city,
                             address_province FROM product
                      INNER JOIN address USING (address_id)
                      WHERE product_name LIKE '%$key%'
                      ORDER BY product_id DESC LIMIT $page, 9";
    }
    return $this -> product_show_partial($sql_product);
  }

  public function get_address_by_user_id($user_id, $token_db, $user_token) {
    if (!($this -> check_token_db($user_id.'_&_', $token_db)))
    return $this -> json_error('Upss!! Token database bermasalah');

    if (!($this -> check_user_token_lib($user_id, $user_token)))
    return $this -> json_error('Upss!! Token user bermasalah');

    $sql_address   = "SELECT * FROM address WHERE user_id = $user_id";
    $query_address = $this -> db -> prepare($sql_address);
    $query_address -> execute();

    $result_address    = array();
    $condition_address = false;

    while ($value_address     = $query_address -> fetch(PDO::FETCH_OBJ)) {
      $condition_address      = true;
      $address_id             = $value_address -> address_id;
      $user_id                = $value_address -> user_id;
      $address_name           = $value_address -> address_name;
      $address_street         = $value_address -> address_street;
      $address_village        = $value_address -> address_village;
      $address_subdistrict    = $value_address -> address_subdistrict;
      $address_city           = $value_address -> address_city;
      $address_province       = $value_address -> address_province;
      $address_regional       = $value_address -> address_regional;
      $address_postal         = $value_address -> address_postal;
      $address_id_city        = $value_address -> address_id_city;
      $address_id_subdistrict = $value_address -> address_id_subdistrict;

      array_push($result_address, array(
        'address_id'             => $address_id,
        'user_id'                => $user_id,
        'address_name'           => $address_name,
        'address_street'         => $address_street,
        'address_village'        => $address_village,
        'address_subdistrict'    => $address_subdistrict,
        'address_city'           => $address_city,
        'address_province'       => $address_province,
        'address_regional'       => $address_regional,
        'address_postal'         => $address_postal,
        'address_id_city'        => $address_id_city,
        'address_id_subdistrict' => $address_id_subdistrict
      ));
    }

    if ($condition_address)
    return json_encode(array('value' => 1, 'result_address' => $result_address));

    return $this -> json_error('Upss!! Address kosong');
  }

  public function put_into_cart($product_id,
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
                                $user_token) {

      if (!($this -> check_token_db($product_id.'_&_'.$user_id.'_&_'.$address_id, $token_db)))
      return $this -> json_error('Upss!! Token database bermasalah');

      if (!($this -> check_user_token_lib($user_id, $user_token)))
      return $this -> json_error('Upss!! Token user bermasalah');

      $sql_cart = "INSERT INTO cart (product_id,
                                     user_id,
                                     address_id,
                                     cart_qty,
                                     cart_weight,
                                     cart_detail,
                                     cart_shipping_cost,
                                     cart_courier_desc,
                                     cart_pay_method,
                                     cart_code,
                                     cart_token,
                                     cart_status,
                                     cart_additional_price,
                                     cart_price_total)
                                 VALUES ($product_id,
                                         $user_id,
                                         $address_id,
                                         $cart_qty,
                                         $cart_weight,
                                         '$cart_detail',
                                         $cart_shipping_cost,
                                         '$cart_courier_desc',
                                         '$cart_pay_method',
                                         $cart_code,
                                         '$cart_token',
                                         '$cart_status',
                                         $cart_additional_price,
                                         $cart_price_total)";
    $query_cart = $this -> db -> prepare($sql_cart);
    $query_cart -> execute();

    return json_encode(array('value' => 1,
                             'message' => 'Sukses, Silahkan melakukan pembayaran dan konfirmasi'));
  }

  public function add_address($user_id,
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
                              $user_token) {

    if (!($this -> check_token_db($user_id.'_&_'.$address_name, $token_db)))
    return $this -> json_error('Upss!! Token database bermasalah');

    if (!($this -> check_user_token_lib($user_id, $user_token)))
    return $this -> json_error('Upss!! Token user bermasalah');

    $sql_address_id_check = "SELECT address_id FROM address WHERE user_id = $user_id AND
                                                            address_name = '$address_name' AND
                                                            address_street = '$address_street'";
    $query_address_id_check = $this -> db -> prepare($sql_address_id_check);
    $query_address_id_check -> execute();

    if ($value_address_id_check = $query_address_id_check -> fetch(PDO::FETCH_OBJ))
    return $this -> json_error('Upss!! Alamat yang anda masukkan sama dengan alamat anda sebelumya');

    $sql_address = "INSERT INTO address (user_id,
                                         address_name,
                                         address_street,
                                         address_village,
                                         address_subdistrict,
                                         address_city,
                                         address_province,
                                         address_regional,
                                         address_postal,
                                         address_id_province,
                                         address_id_city,
                                         address_id_subdistrict)
                               VALUES ($user_id,
                                       '$address_name',
                                       '$address_street',
                                       '$address_village',
                                       '$address_subdistrict',
                                       '$address_city',
                                       '$address_province',
                                       '$address_regional',
                                       $address_postal,
                                       $address_id_province,
                                       $address_id_city,
                                       $address_id_subdistrict)";
    $query_address = $this -> db -> prepare($sql_address);
    $query_address -> execute();

    $sql_address_id = "SELECT address_id FROM address WHERE user_id = $user_id AND
                                                            address_name = '$address_name' AND
                                                            address_street = '$address_street'";
    $query_address_id = $this -> db -> prepare($sql_address_id);
    $query_address_id -> execute();

    if ($value_address_id = $query_address_id -> fetch(PDO::FETCH_OBJ))
    return json_encode(array('value' => 1,
                             'message' => 'Sukses',
                             'callback_id' => $value_address_id -> address_id));

  }

  public function payment($cart_id, $user_id, $payment_string, $token_db, $user_token) {
    if (!($this -> check_token_db($cart_id.'_&_'.$user_id.'_&_'.$payment_string, $token_db)))
    return $this -> json_error('Upss!! Token database bermasalah');

    if (!($this -> check_user_token_lib($user_id, $user_token)))
    return $this -> json_error('Upss!! Token user bermasalah');

    $sql_payment = "INSERT INTO payment (cart_id, user_id, payment_string)
                      VALUES ($cart_id, $user_id, '$payment_string')";
    $query_payment = $this -> db -> prepare($sql_payment);
    $query_payment -> execute();

    $sql_cart = "UPDATE cart SET cart_status = 'confirmed_payment' WHERE cart_id = $cart_id";
    $query_cart = $this -> db -> prepare($sql_cart);
    $query_cart -> execute();

    return json_encode(array('value' => 1, 'message' => 'Sukses'));
  }

  public function get_cart_by_user_id($user_id, $user_token) {
    if (!($this -> check_user_token_lib($user_id, $user_token)))
    return $this -> json_error('Upss!! Token user bermasalah');

    $sql_cart   = "SELECT cart.*,
                          product.product_name,
                          product.product_price,
                          product.product_qty,
                          product.product_photo1 FROM cart INNER JOIN product USING (product_id)
                                                 WHERE cart.user_id = $user_id
                                                 ORDER BY cart_id DESC LIMIT 10";
    $query_cart = $this -> db -> prepare($sql_cart);
    $query_cart -> execute();

    $result_cart    = array();
    $condition_cart = false;

    while ($value_cart = $query_cart -> fetch(PDO::FETCH_OBJ)) {
      $condition_cart        = true;
      $cart_id               = $value_cart -> cart_id;
      $product_id            = $value_cart -> product_id;
      $user_id               = $value_cart -> user_id;
      $address_id            = $value_cart -> address_id;
      $cart_qty              = $value_cart -> cart_qty;
      $cart_weight           = $value_cart -> cart_weight;
      $cart_detail           = $value_cart -> cart_detail;
      $cart_shipping_cost    = $value_cart -> cart_shipping_cost;
      $cart_courier_desc     = $value_cart -> cart_courier_desc;
      $cart_waybill          = $value_cart -> cart_waybill;
      $cart_pay_method       = $value_cart -> cart_pay_method;
      $cart_code             = $value_cart -> cart_code;
      $cart_token            = $value_cart -> cart_token;
      $cart_status           = $value_cart -> cart_status;
      $cart_additional_price = $value_cart -> cart_additional_price;
      $cart_price_total      = $value_cart -> cart_price_total;
      $product_name          = $value_cart -> product_name;
      $product_price         = $value_cart -> product_price;
      $product_qty           = $value_cart -> product_qty;
      $product_photo1        = $value_cart -> product_photo1;

      array_push($result_cart, array(
        'cart_id'               => $cart_id,
        'product_id'            => $product_id,
        'user_id'               => $user_id,
        'address_id'            => $address_id,
        'cart_qty'              => $cart_qty,
        'cart_weight'           => $cart_weight,
        'cart_detail'           => $cart_detail,
        'cart_shipping_cost'    => $cart_shipping_cost,
        'cart_courier_desc'     => $cart_courier_desc,
        'cart_waybill'          => $cart_waybill,
        'cart_pay_method'       => $cart_pay_method,
        'cart_code'             => $cart_code,
        'cart_token'            => $cart_token,
        'cart_status'           => $cart_status,
        'cart_additional_price' => $cart_additional_price,
        'cart_price_total'      => $cart_price_total,
        'product_name'          => $product_name,
        'product_price'         => $product_price,
        'product_qty'           => $product_qty,
        'product_photo1'        => $product_photo1
      ));
    }

    if ($condition_cart)
    return json_encode(array('value' => 1, 'result_cart' => $result_cart));

    return $this -> json_error('Upss!! Keranjang kosong');
  }

  public function get_cart_by_user_product($user_id, $user_token) {
    if (!($this -> check_user_token_lib($user_id, $user_token)))
    return $this -> json_error('Upss!! Token user bermasalah');

    $sql_cart   = "SELECT cart.*,
                          product.product_name,
                          product.product_price,
                          product.product_qty,
                          product.product_photo1 FROM cart INNER JOIN product USING (product_id)
                                                 WHERE product.user_id = $user_id &&
                                                 cart.cart_status != 'waiting_payment' &&
                                                 cart.cart_status != 'confirmed_payment'
                                                 ORDER BY cart_id DESC LIMIT 10";
    $query_cart = $this -> db -> prepare($sql_cart);
    $query_cart -> execute();

    $result_cart    = array();
    $condition_cart = false;

    while ($value_cart = $query_cart -> fetch(PDO::FETCH_OBJ)) {
      $condition_cart        = true;
      $cart_id               = $value_cart -> cart_id;
      $product_id            = $value_cart -> product_id;
      $user_id               = $value_cart -> user_id;
      $address_id            = $value_cart -> address_id;
      $cart_qty              = $value_cart -> cart_qty;
      $cart_weight           = $value_cart -> cart_weight;
      $cart_detail           = $value_cart -> cart_detail;
      $cart_shipping_cost    = $value_cart -> cart_shipping_cost;
      $cart_courier_desc     = $value_cart -> cart_courier_desc;
      $cart_waybill          = $value_cart -> cart_waybill;
      $cart_pay_method       = $value_cart -> cart_pay_method;
      $cart_code             = $value_cart -> cart_code;
      $cart_token            = $value_cart -> cart_token;
      $cart_status           = $value_cart -> cart_status;
      $cart_additional_price = $value_cart -> cart_additional_price;
      $cart_price_total      = $value_cart -> cart_price_total;
      $product_name          = $value_cart -> product_name;
      $product_price         = $value_cart -> product_price;
      $product_qty           = $value_cart -> product_qty;
      $product_photo1        = $value_cart -> product_photo1;

      array_push($result_cart, array(
        'cart_id'               => $cart_id,
        'product_id'            => $product_id,
        'user_id'               => $user_id,
        'address_id'            => $address_id,
        'cart_qty'              => $cart_qty,
        'cart_weight'           => $cart_weight,
        'cart_detail'           => $cart_detail,
        'cart_shipping_cost'    => $cart_shipping_cost,
        'cart_courier_desc'     => $cart_courier_desc,
        'cart_waybill'          => $cart_waybill,
        'cart_pay_method'       => $cart_pay_method,
        'cart_code'             => $cart_code,
        'cart_token'            => $cart_token,
        'cart_status'           => $cart_status,
        'cart_additional_price' => $cart_additional_price,
        'cart_price_total'      => $cart_price_total,
        'product_name'          => $product_name,
        'product_price'         => $product_price,
        'product_qty'           => $product_qty,
        'product_photo1'        => $product_photo1
      ));
    }

    if ($condition_cart)
    return json_encode(array('value' => 1, 'result_cart' => $result_cart));

    return $this -> json_error('Upss!! Keranjang kosong');
  }

  public function get_address_by_address_id($address_id, $token_db, $user_token, $user_id) {
    if (!($this -> check_token_db($address_id.'_&_'.$user_id.'_&_'.$user_token, $token_db)))
    return $this -> json_error('Upss!! Token database bermasalah');

    if (!($this -> check_user_token_lib($user_id, $user_token)))
    return $this -> json_error('Upss!! Token user bermasalah');

    $sql_address   = "SELECT * FROM address WHERE address_id = $address_id";
    $query_address = $this -> db -> prepare($sql_address);
    $query_address -> execute();

    $result_address    = array();
    $condition_address = false;

    if ($value_address = $query_address -> fetch(PDO::FETCH_OBJ)) {
      $condition_address      = true;
      $address_id             = $value_address -> address_id;
      $user_id                = $value_address -> user_id;
      $address_name           = $value_address -> address_name;
      $address_street         = $value_address -> address_street;
      $address_village        = $value_address -> address_village;
      $address_subdistrict    = $value_address -> address_subdistrict;
      $address_city           = $value_address -> address_city;
      $address_province       = $value_address -> address_province;
      $address_regional       = $value_address -> address_regional;
      $address_postal         = $value_address -> address_postal;
      $address_id_city        = $value_address -> address_id_city;
      $address_id_subdistrict = $value_address -> address_id_subdistrict;

      array_push($result_address, array(
        'address_id'             => $address_id,
        'user_id'                => $user_id,
        'address_name'           => $address_name,
        'address_street'         => $address_street,
        'address_village'        => $address_village,
        'address_subdistrict'    => $address_subdistrict,
        'address_city'           => $address_city,
        'address_province'       => $address_province,
        'address_regional'       => $address_regional,
        'address_postal'         => $address_postal,
        'address_id_city'        => $address_id_city,
        'address_id_subdistrict' => $address_id_subdistrict
      ));
    }

    if ($condition_address)
    return json_encode(array('value' => 1, 'result_address' => $result_address));

    return $this -> json_error('Upss!! Address kosong');
  }

  public function set_status_cart($cart_id, $user_id, $status_cart, $token_db, $user_token) {
    if (!($this -> check_token_db($cart_id.'_&_'.$user_id.'_&_'.$status_cart, $token_db)))
    return $this -> json_error('Upss!! Token database bermasalah');

    if (!($this -> check_user_token_lib($user_id, $user_token)))
    return $this -> json_error('Upss!! Token user bermasalah');

    $sql_cart = "UPDATE cart SET cart_status = '$status_cart' WHERE cart_id = $cart_id";
    $query_cart = $this -> db -> prepare($sql_cart);
    $query_cart -> execute();

    return json_encode(array('value' => 1, 'message' => 'Sukses'));
  }

  //======= Admin library ===================================================

  public function admin_register($admin_name,
                                $admin_phone,
                                $admin_birth_date,
                                $admin_gender,
                                $admin_email,
                                $admin_password,
                                $token_db) {
    if (!($this -> check_token_db($admin_phone.'_&_'.
                                $admin_email.'_&_'.
                                $admin_password, $token_db)))
    return false;

    if ($this -> check_email_phone_admin($admin_email, $admin_phone))
    return false;

    $admin_password = password_hash($admin_password, PASSWORD_DEFAULT);

    $rand_string = $this -> generate_random_string();

    $sql_register = "INSERT INTO admin (admin_name,
                                        admin_email,
                                        admin_phone,
                                        admin_password,
                                        admin_gender,
                                        admin_birth_date,
                                        admin_token)
                    VALUES ('$admin_name',
                            '$admin_email',
                             $admin_phone,
                            '$admin_password',
                            '$admin_gender',
                            '$admin_birth_date',
                            '$rand_string')";
    $query_register = $this -> db -> prepare($sql_register);
    $query_register -> execute();

    return true;
  }

  public function admin_login($admin_email, $admin_password, $admin_token) {
    $sql_login = "SELECT * FROM admin WHERE admin_email = '$admin_email'";
    $query_login = $this -> db -> prepare($sql_login);
    $query_login -> execute();

    if ($value_login = $query_login -> fetch(PDO::FETCH_OBJ)) {
      if (!(password_verify($admin_password, $value_login -> admin_password)))
      return false;

      if ($value_login -> admin_email_verified == 0)
      return false;

      $sql_set_token   = "UPDATE admin SET admin_token='$admin_token' WHERE admin_email = '$admin_email'";
      $query_set_token = $this -> db -> prepare($sql_set_token);
      $query_set_token -> execute();

      return true;
    }
    return false;
  }

  public function validate_attr_login($admin_email, $admin_token) {
    $sql_login = "SELECT admin_status FROM admin WHERE admin_email = '$admin_email' AND admin_token = '$admin_token' ";
    $query_login = $this -> db -> prepare($sql_login);
    $query_login -> execute();

    return $query_login;
  }

  public function get_payment() {
    $sql_cp   = "SELECT * FROM payment ORDER BY payment_id DESC";
    $query_cp = $this -> db -> prepare($sql_cp);
    $query_cp -> execute();

    return $query_cp;
  }

  public function set_payment($payment_id, $cart_id, $token_db) {
    if (!($this -> check_token_db($payment_id."_&_".$cart_id, $token_db))) {
      return false;
    }

    $sql_cart   = "UPDATE cart SET cart_status = 'verified_payment' WHERE cart_id = $cart_id";
    $sql_cp     = "UPDATE payment SET payment_status = 'verified_payment' WHERE payment_id = $payment_id";
    $query_cart = $this -> db -> prepare($sql_cart);
    $query_cp   = $this -> db -> prepare($sql_cp);
    $query_cart -> execute();
    $query_cp -> execute();

    return true;
  }
}
?>
