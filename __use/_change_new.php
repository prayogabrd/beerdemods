<?php
session_start();
header("Content-Type: application/json; charset=utf-8");

// Required
require("../assets/php/.func__get_user_data.php");
require("../assets/php/.mysql__funcs.php");

// Ambil data dari fetch
$data = json_decode(file_get_contents("php://input"), true);

// ganti pw
$result = [
  "status" => false,
  "massage" => ($lang === "ID" ? "Terjadi kesalahan!" : "There is an error!")
];
if (
  (isset($data["lang"]) && base64_decode($data["lang"]) != "") &&
  (isset($data["old_pass"]) && strlen(base64_decode($data["old_pass"])) >= 8) &&
  (isset($data["new_pass"]) && strlen(base64_decode($data["new_pass"])) >= 8) &&
  (isset($data["conf_new_pass"]) && strlen(base64_decode($data["conf_new_pass"])) >= 8)
) {
    $lang = base64_decode($data["lang"]);
    $old_pass = mysqli_real_escape_string($_conn, base64_decode($data["old_pass"]));
    $new_pass = mysqli_real_escape_string($_conn, base64_decode($data["new_pass"]));
    $conf_new_pass = mysqli_real_escape_string($_conn, base64_decode($data["conf_new_pass"]));
  if ($old_pass != $new_pass) {
    if ($new_pass === $conf_new_pass) {
      $_data = get_main_data();
      if ($_data != false) {
        $user_name = $_data["user_name"];
        $user_email = $_data["user_email"];
        $user_pass = mysqli_real_escape_string($_conn, $_data["user_pass"]);
        $user_account = mysqli_get_data("SELECT `user_id`, `user_name`, `user_email`, `user_pass` FROM `account` WHERE `user_name` = '$user_name' AND `user_email` = '$user_email' AND `user_pass` = '$user_pass'");
        if (isset($user_account[0])) {
          $user_id = $user_account[0]["user_id"];
          if (password_verify($old_pass, $user_account[0]["user_pass"])) {
            $new_pass = password_hash($new_pass, PASSWORD_DEFAULT);
            mysqli_query($_conn, "UPDATE `account` SET `user_pass` = '$new_pass' WHERE `user_id` = '$user_id'");
            if (mysqli_affected_rows($_conn) > 0) {
              $result["status"] = true;
              $result["massage"] = ($lang === "ID" ? "Berhasil mengubah kata sandi!" : "Password changed successfully!");
              if (
                (isset($_COOKIE["user_name"]) && $_COOKIE["user_name"] != "") &&
                (isset($_COOKIE["user_email"]) && $_COOKIE["user_email"] != "") &&
                (isset($_COOKIE["user_pass"]) && $_COOKIE["user_pass"] != "")
              ) {
                setcookie("user_name", $user_account[0]["user_name"], time() + (60*60*24*30), "/");
                setcookie("user_email", $user_account[0]["user_email"], time() + (60*60*24*30), "/");
                setcookie("user_pass", $new_pass, time() + (60*60*24*30), "/");
              }
              if (
                (isset($_SESSION["user_name"]) && $_SESSION["user_name"] != "") &&
                (isset($_SESSION["user_email"]) && $_SESSION["user_email"] != "") &&
                (isset($_SESSION["user_pass"]) && $_SESSION["user_pass"] != "")
              ) {
                $_SESSION["user_name"] = $user_account[0]["user_name"];
                $_SESSION["user_email"] = $user_account[0]["user_email"];
                $_SESSION["user_pass"] = $new_pass;
              }
            } else {
              $result["massage"] = ($lang === "ID" ? "Tidak ada data yang diubah!" : "No data has been changed!");
            }
          } else {
            $result["massage"] = ($lang === "ID" ? "Kata sandi salah!" : "Wrong password!");
          }
        }
      }
    } else {
      $result["massage"] = ($lang === "ID" ? "Konfirmasi kata sandi salah!" : "Confirm password wrong!");
    }
  } else {
    $result["massage"] = ($lang === "ID" ? "Kata sandi baru sama dengan yang lama!" : "The new password is the same as the old one!");
  }
}

// Kembalikan hasil
echo(json_encode($result));