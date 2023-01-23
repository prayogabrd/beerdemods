<?php
// Required
require(".connect_mysql.php");
// Main
function mysqli_get_data($q) {
  global $_conn;
  $_data = mysqli_query($_conn, $q);
  $reads = [];
  while ($read = mysqli_fetch_assoc($_data)) {
    $reads[] = $read;
  }
  return $reads;
}
function mysqli_signup($data) {
  global $_conn;
  if ($data["user_cc"] != "ID") {
    $data["user_cc"] = "EN";
  }
  $output = [
    "status" => false,
    "massage" => ($data["user_cc"] === "ID" ? "Pendaftar gagal!" : "Signup failed!")
  ];
  if ($data["name"] != "" && $data["user_name"] != "" && $data["user_email"] != "" && $data["user_pass"] != "" && $data["conf_pass"] != "" && $data["i_agree"] != false) {
    $user = mysqli_get_data("SELECT `user_name`, `user_email` FROM `account` WHERE `user_name` = '".strtolower(stripcslashes($data["user_name"]))."' OR `user_email` = '".strtolower(stripcslashes($data["user_email"]))."'");
    if (!isset($user[0])) {
      if ($data["user_pass"] === $data["conf_pass"]) {
        mysqli_query($_conn, "
          INSERT INTO `account` (
            `user_id`,
            `user_ip`,
            `user_cc`,
            `name`,
            `user_name`,
            `user_email`,
            `user_pass`,
            `user_profile`,
            `user_call`,
            `user_access`,
            `user_admin`,
            `is_owner`
          ) VALUES (
            NULL,
            '".$data["user_ip"]."',
            '".$data["user_cc"]."',
            '".htmlspecialchars($data["name"])."',
            '".strtolower(stripcslashes($data["user_name"]))."',
            '".strtolower(stripcslashes($data["user_email"]))."',
            '".password_hash(mysqli_real_escape_string($_conn, $data["user_pass"]), PASSWORD_DEFAULT)."',
            '-',
            '-',
            '1',
            '-1',
            '-1'
          );
        ");
        if (mysqli_affected_rows($_conn) > 0) {
          $output["status"] = true;
          $output["massage"] = ($data["user_cc"] === "ID" ? "Akun berhasil dibuat, Ayo masuk!" : "Account created successfully, Come on in!");
        } else {
          $output["massage"] = ($data["user_cc"] === "ID" ? "Server bermasalah!" : "Server problem!");
        }
      } else {
        $output["massage"] = ($data["user_cc"] === "ID" ? "Konfirmasi kata sandi tidak sama!" : "Password confirmation is not the same!");
      }
    } else if ($user[0]["user_name"] === $data["user_name"]) {
      $output["massage"] = ($data["user_cc"] === "ID" ? "Username sudah digunakan!" : "Username already taken!");
    } else {
      $output["massage"] = ($data["user_cc"] === "ID" ? "Email sudah digunakan!" : "Email already taken!");
    }
  } else {
    $output["massage"] = ($data["user_cc"] === "ID" ? "Formulir yang tertera wajib diisi!" : "The form listed must be filled out!");
    $output["data"] = $data;
  }
  return $output;
}
function mysqli_signin($data) {
  global $_conn;
  if ($data["user_cc"] != "ID") {
    $data["user_cc"] = "EN";
  }
  $output = [
    "status" => false,
    "massage" => ($data["user_cc"] === "ID" ? "Masuk gagal!" : "Signin failed!"),
    "data" => []
  ];
  if ($data["user"] != "" && $data["user_pass"] != "" && $data["user_ip"] != "" && $data["user_cc"] != "") {
    $user = mysqli_get_data("SELECT `user_name`, `user_email`, `user_pass` FROM `account` WHERE `user_name` = '".stripcslashes($data["user"])."' OR `user_email` = '".stripcslashes($data["user"])."'");
    if (isset($user[0])) {
      if (password_verify(mysqli_real_escape_string($_conn, $data["user_pass"]), $user[0]["user_pass"])) {
        mysqli_query($_conn, "
          UPDATE
            `account`
          SET
            `user_ip` = '".$data["user_ip"]."',
            `user_cc` = '".$data["user_cc"]."'
          WHERE
            `user_id` = '".$user[0]["user_id"]."';
        ");
        $output["status"] = true;
        $output["massage"] = ($data["user_cc"] === "ID" ? "Masuk berhasil!" : "Signin Successful!");
        $output["data"] = $user;
      } else {
        $output["massage"] = ($data["user_cc"] === "ID" ? "Kata sandi salah!" : "Wrong password!");
      }
    } else {
      $output["massage"] = ($data["user_cc"] === "ID" ? "Username atau email tidak ditemukan!" : "Username or email not found!");
    }
  } else {
    $output["massage"] = ($data["user_cc"] === "ID" ? "Forum yang tertera wajib diisi!" : "The form listed is mandatory!");
  }
  return $output;
}