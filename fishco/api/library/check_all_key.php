<?php
require_once('koneksi.php');

/**
 * mkhaufillah
 */
class check_all_key extends koneksi {

  public function check_api_key($api_key) {
    $sql_api_key   = "SELECT api_key FROM api_key WHERE api_key = '$api_key'";
    $query_api_key = $this -> db -> prepare($sql_api_key);
    $query_api_key -> execute();

    if ($value_api_key = $query_api_key -> fetch(PDO::FETCH_OBJ))
    return true;

    return false;
  }

  public function check_android_key($android_key) {
    $sql_android_key   = "SELECT android_key FROM android_key WHERE android_key = '$android_key'";
    $query_android_key = $this -> db -> prepare($sql_android_key);
    $query_android_key -> execute();

    if ($value_android_key = $query_android_key -> fetch(PDO::FETCH_OBJ))
    return true;

    return false;
  }
}


?>
