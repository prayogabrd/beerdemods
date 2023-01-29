<?php
session_start();
header("Content-Type: application/json; charset=utf-8");

// Required
require("../assets/php/.func__get_user_data.php");
require("../assets/php/.mysql__funcs.php");

// Get data from fetch
$input = json_decode(file_get_contents("php://input"), true);
if (isset($input["user_pass"])) {
  $_user_pass = mysqli_real_escape_string($_conn, base64_decode($input["user_pass"]));
}

// Data result
$result = [
  "status" => false,
];

// Get pass user
$_data = get_main_data();
if ($_data != false) {
  $user_name = $_data["user_name"];
  $user_email = $_data["user_email"];
  $user_pass = mysqli_real_escape_string($_conn, $_data["user_pass"]);
  $user_account = mysqli_get_data("SELECT `user_pass` FROM `account` WHERE `user_name` = '$user_name' AND `user_email` = '$user_email' AND `user_pass` = '$user_pass'");
  if (isset($user_account[0]) && isset($_user_pass)) {
    if (password_verify($_user_pass, $user_account[0]["user_pass"])) {
      $result["status"] = true;
    }
  }
}

// Hasil
echo(json_encode($result));