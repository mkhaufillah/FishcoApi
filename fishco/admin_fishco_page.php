<?php
  session_start();
  require_once('api/library/library.php');
  require_once('api/library/m_crypt.php');
  $lib       = new library();
  $m_crypt   = new m_crypt();

//====== function logout ====================================
  function logout() {
    $_SESSION['login_admin_session_email'] = null;
    $_SESSION['login_admin_session_token'] = null;
    session_unset();
    session_destroy();
  }

//======= cek request method =====================================
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {

//======= on logout code =======================================
    if (isset($_POST['submit_logout']) && $_POST['submit_logout'] != '') {
      logout();
    }

//======== on login code ===========================================
    if (isset($_POST['admin_email']) &&
        isset($_POST['admin_password']) &&
        isset($_POST['submit_login'])) {
          $admin_email    = strip_tags($_POST['admin_email']);
          $admin_email    = filter_var($admin_email, FILTER_SANITIZE_EMAIL);
          $admin_password = strip_tags($_POST['admin_password']);
          $admin_password = filter_var($admin_password, FILTER_SANITIZE_STRING);
          $admin_password = filter_var($admin_password, FILTER_SANITIZE_SPECIAL_CHARS);
          $admin_token    = $lib -> generate_random_string();

          if ($admin_token    != '' &&
              $admin_email    != '' &&
              $admin_password != '') {
                $result = $lib -> admin_login($admin_email, $admin_password, $admin_token);
                if ($result) {
                  $_SESSION["login_admin_session_email"] = $admin_email;
                  $_SESSION["login_admin_session_token"] = $admin_token;
                  ?> <h1 style="color:green">Login sukses</h1><hr/> <?php
                } else {
                  ?> <h1 style="color:red">Login gagal, pastikan kombinasi email dan password benar, pastikan email sudah dikonfirmasi</h1><hr/> <?php
                }
          }
    }

//=============== on register code ===============================================
    if (isset($_POST['admin_name']) &&
        isset($_POST['admin_phone']) &&
        isset($_POST['admin_birth_date']) &&
        isset($_POST['admin_gender']) &&
        isset($_POST['admin_email']) &&
        isset($_POST['submit_register'])) {
          $admin_name       = strip_tags($_POST['admin_name']);
          $admin_name       = filter_var($admin_name, FILTER_SANITIZE_STRING);
          $admin_name       = filter_var($admin_name, FILTER_SANITIZE_SPECIAL_CHARS);
          $admin_phone      = strip_tags($_POST['admin_phone']);
          $admin_phone      = filter_var($admin_phone, FILTER_SANITIZE_NUMBER_INT);
          $admin_birth_date = strip_tags($_POST['admin_birth_date']);
          $admin_birth_date = filter_var($admin_birth_date, FILTER_SANITIZE_NUMBER_INT);
          $admin_gender     = strip_tags($_POST['admin_gender']);
          $admin_gender     = filter_var($admin_gender, FILTER_SANITIZE_STRING);
          $admin_gender     = filter_var($admin_gender, FILTER_SANITIZE_SPECIAL_CHARS);
          $admin_email      = strip_tags($_POST['admin_email']);
          $admin_email      = filter_var($admin_email, FILTER_SANITIZE_EMAIL);

          if ($admin_name       != '' &&
              $admin_phone      != '' &&
              $admin_birth_date != '' &&
              $admin_gender     != '' &&
              $admin_email      != '') {

                $admin_password = $lib -> generate_random_string(6);
                $token_db  = $m_crypt -> encrypt($admin_phone.'_&_'.
                                                 $admin_email.'_&_'.
                                                 $admin_password);

                $result = $lib->admin_register($admin_name,
                                               $admin_phone,
                                               $admin_birth_date,
                                               $admin_gender,
                                               $admin_email,
                                               $admin_password,
                                               $token_db);
                if ($result) {
                  ?> <h1 style="color:green">Sukses registrasi</h1><hr/> <?php
                } else {
                  ?> <h1 style="color:red">Gagal registrasi, pastikan email belum terdaftar sebelumnya</h1><hr/> <?php
                }
              }
    }
  }

//========= on confirm ======================================================
  if (isset($_POST['submit_confirm']) &&
      isset($_POST['payment_id']) &&
      isset($_POST['cart_id'])) {

        $payment_id = $_POST['payment_id'];
        $cart_id        = $_POST['cart_id'];

        if ($payment_id != '' &&
            $cart_id != '') {

              $token_db  = $m_crypt -> encrypt($payment_id.'_&_'.
                                               $cart_id);

              if ($lib -> set_payment($payment_id ,$cart_id, $token_db)) {
                ?> <h1 style="color:green">Cart terkonfirmasi</h1><hr/> <?php
              } else {
                ?> <h1 style="color:red">Gagal Konfirmasi</h1><hr/> <?php
              }

        }
  }
?>

<!-- view html admin page -->
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>

<!-- php code, validate admin login and check token -->
    <?php
      if (isset($_SESSION['login_admin_session_email']) && isset($_SESSION['login_admin_session_token'])) {

        $email = $_SESSION['login_admin_session_email'];
        $token = $_SESSION['login_admin_session_token'];

        $query = $lib -> validate_attr_login($email, $token);

//check token and validate type admin
        if ($value = $query -> fetch(PDO::FETCH_OBJ)) {
          if ($value -> admin_status == "root_adm") {
    ?>

<!-- view register new admin if admin success login -->
      <h3 style="color:blue">Daftarkan admin baru</h3>
      <form class="register" action="admin_fishco_page.php" method="post">
        <input type="text" name="admin_name" placeholder="Nama">
        <input type="number" name="admin_phone" placeholder="Phone (+62xxx)">
        <input type="date" name="admin_birth_date" placeholder="Tgl. lahir (YYYY-MM-DD)">
        <input type="text" name="admin_gender" placeholder="Gender (Pria/Wanita)">
        <input type="email" name="admin_email" placeholder="Email">
        <input type="submit" name="submit_register" value="Register">
      </form><hr/>

<!--- closing validate login/token and get confirm pay -->
    <?php
      } $query_cp = $lib -> get_payment();
    ?>

<!-- logout button -->
      <h3 style="color:blue">Logout Akun</h3>
      <form class="logout" action="admin_fishco_page.php" method="post">
        <input type="submit" name="submit_logout" value="Logout">
      </form><hr/>

<!-- table looping confirm pqy -->
      <h3 style="color:blue">Daftar konfirmasi pembayaran transfer</h3>
      <table border="1" draggable="true">
        <tr>
          <th style="padding:12px">payment_id</th>
          <th style="padding:12px">cart_id</th>
          <th style="padding:12px">user_id</th>
          <th style="padding:12px">payment_string</th>
          <th style="padding:12px">payment_register</th>
          <th style="padding:12px">confirm</th>
        </tr>

<!-- looping -->
    <?php while ($value_cart = $query_cp -> fetch(PDO::FETCH_OBJ)) { ?>

        <tr>
          <td style="padding:12px"><?php echo $value_cart -> payment_id ?></td>
          <td style="padding:12px"><?php echo $value_cart -> cart_id ?></td>
          <td style="padding:12px"><?php echo $value_cart -> user_id ?></td>
          <td style="padding:12px"><?php echo $value_cart -> payment_string ?></td>
          <td style="padding:12px"><?php echo $value_cart -> payment_register ?></td>

          <?php if ($value_cart -> payment_status == "confirmed_payment") { ?>

          <td style="padding:12px">
            <form class="confirm" action="admin_fishco_page.php" method="post">
              <input type="hidden" name="payment_id" value="<?php echo $value_cart -> payment_id ?>">
              <input type="hidden" name="cart_id" value="<?php echo $value_cart -> cart_id ?>">
              <input style="background-color:red; color:white" type="submit" name="submit_confirm" value="<?php echo $value_cart -> payment_status; ?>">
            </form>
          </td>

        <?php } else { ?>

          <td style="padding:12px"><input style="background-color:green; color:white" type="button" value="<?php echo $value_cart -> payment_status; ?>"></td>

        <?php } ?>

        </tr>

    <?php } ?>

    </table><hr/>

<!-- if admin token not match and admin not logged in -->
    <?php } else {logout();} } else { ?>

      <h3 style="color:blue">Silahkan login terlebih dahulu</h3>
      <form class="login" action="admin_fishco_page.php" method="post">
        <input type="email" name="admin_email" placeholder="Email">
        <input type="password" name="admin_password" placeholder="Password">
        <input type="submit" name="submit_login" value="Login">
      </form><hr/>

    <?php } ?>

  </body>
</html>
