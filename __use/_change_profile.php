<?php
session_start();
header("Content-Type: application/json; charset=utf-8");

// Required
require("../assets/php/.func__get_user_data.php");
require("../assets/php/.mysql__funcs.php");

// Ambil data dari fetch
$data = json_decode(file_get_contents("php://input"), true);

// Olah data
$user_cc = base64_decode(stripcslashes($data["lang"]));

// Set result
$result = [
  "status" => false,
  "massage" => ($user_cc === "ID" ? "Terjadi kesalahan!" : "There is an error!")
];

// Cek apakah user sudah masuk
$_data = get_main_data();
if ($_data != false) {
  $user_name = $_data["user_name"];
  $user_email = $_data["user_email"];
  $user_pass = $_data["user_pass"];
  $user_account = mysqli_get_data("SELECT `user_id`, `user_name`, `user_pass` FROM `account` WHERE `user_name` = '$user_name' AND `user_email` = '$user_email' AND `user_pass` = '$user_pass'");
  if (isset($user_account[0])) {
    $user_id = $user_account[0]["user_id"];
    if (isset($data["user_email"])) {
      $_user_email = strtolower(stripcslashes(base64_decode($data["user_email"])));
      if ($_user_email != $user_account[0]["user_email"]) {
        if (isset($data["name"]) && isset($data["user_call"])) {
          $_name = htmlspecialchars(base64_decode($data["name"]));
          $_user_call = strtolower(stripcslashes(base64_decode($data["user_call"])));
          mysqli_query($_conn, "UPDATE `account` SET `name` = '$_name', `user_email` = '$_user_email', `user_call` = '$_user_call' WHERE `user_id` = '$user_id'");
          if (mysqli_affected_rows($_conn)) {
            $result["status"] = true;
            $result["massage"] = ($user_cc === "ID" ? "Profil berhasil diperbarui!" : "Profile updated successfully!");
            if (
              (isset($_COOKIE["user_name"]) && $_COOKIE["user_name"] != "") &&
              (isset($_COOKIE["user_email"]) && $_COOKIE["user_email"] != "") &&
              (isset($_COOKIE["user_pass"]) && $_COOKIE["user_pass"] != "")
            ) {
              setcookie("user_name", $user_account[0]["user_name"], time() + (60*60*24*30), "/");
              setcookie("user_email", $_user_email, time() + (60*60*24*30), "/");
              setcookie("user_pass", $user_account[0]["user_pass"], time() + (60*60*24*30), "/");
            }
            if (
              (isset($_SESSION["user_name"]) && $_SESSION["user_name"] != "") &&
              (isset($_SESSION["user_email"]) && $_SESSION["user_email"] != "") &&
              (isset($_SESSION["user_pass"]) && $_SESSION["user_pass"] != "")
            ) {
              $_SESSION["user_name"] = $user_account[0]["user_name"];
              $_SESSION["user_email"] = $_user_email;
              $_SESSION["user_pass"] = $user_account[0]["user_pass"];
            }
          } else {
            $result["massage"] = ($user_cc === "ID" ? "Tidak ada yang diperbaharui!" : "Nothing has been updated!");
          }
        }
      } else {
        $result["massage"] = ($user_cc === "ID" ? "Email sudah digunakan!" : "Email already in use!");
      }
    } else {
      if (isset($data["name"]) && isset($data["user_call"])) {
        $_name = htmlspecialchars(base64_decode($data["name"]));
        $_user_call = strtolower(stripcslashes(base64_decode($data["user_call"])));
        mysqli_query($_conn, "UPDATE `account` SET `name` = '$_name', `user_call` = '$_user_call' WHERE `user_id` = '$user_id'");
        if (mysqli_affected_rows($_conn)) {
          $result["status"] = true;
          $result["massage"] = ($user_cc === "ID" ? "Profil berhasil diperbarui!" : "Profile updated successfully!");
        } else {
          $result["massage"] = ($user_cc === "ID" ? "Tidak ada yang diperbaharui!" : "Nothing has been updated!");
        }
      }
    }
  }
}

echo(json_encode($result));