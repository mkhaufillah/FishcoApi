<?php
/**
 * mkhaufillah
 */

class koneksi {
  function __construct() {
    try {
      $this -> db = new PDO ('mysql:host=localhost;dbname=fishco', 'root', 'm4n9070000');
    } catch (PDOException $error) {
      echo json_encode(array('value' => 0, 'message' => 'Upss!! Kesalahan Sistem'));
    }
  }
}
?>
