<?php
session_start();
header("Content-Type: application/json; charset=utf-8");

// Required
require("../assets/php/.func__get_user_data.php");
require("../assets/php/.mysql__funcs.php");

// Data result
$result = [
  "status" => -1,
];

// Cek apakah sudah pernah masuk sebelumnya
$_data = get_main_data();

// Jika sudah, cek apakah user valid
if ($_data != false) {
  $user_name = $_data["user_name"];
  $user_email = $_data["user_email"];
  $user_pass = $_data["user_pass"];
  $user_account = mysqli_get_data("SELECT `user_id` FROM `account` WHERE `user_name` = '".$user_name."' AND `user_email` = '".$user_email."' AND `user_pass` = '".$user_pass."'");
  if (isset($user_account[0])) {
    mysqli_query($_conn, "UPDATE `account` SET `user_profile` = '-' WHERE `user_id` = '".$user_account[0]["user_id"]."'");
    if (mysqli_affected_rows($_conn) > 0) {
      $result["status"] = 1;
    } else {
      $result["status"] = 0;
    }
  }
}

// Kembalikan nilai
echo(json_encode($result));