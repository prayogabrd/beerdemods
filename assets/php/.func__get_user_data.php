<?php
function getUserIpData() {
  $client = @$_SERVER["HTTP_CLIENT_IP"];
  $forward = @$_SERVER["HTTP_X_FORWARDED_FOR"];
  $remote = @$_SERVER["REMOTE_ADDR"];
  if (filter_var($client, FILTER_VALIDATE_IP)) {
    $ip = $client;
  } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
    $ip = $forward;
  } else {
    $ip = $remote;
  }
  $ip_data = json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip), true);
  if ($ip_data && $ip_data["geoplugin_countryCode"] != NULL ) {
    $result = [
      "ip" => $ip,
      "country_code" => $ip_data["geoplugin_countryCode"]
    ];
  } else {
    $result = [
      "ip" => $ip,
      "country_code" => "ID"
    ];
  }
  return $result;
}
function get_main_data() {
  if (
    (isset($_COOKIE["user_name"]) && $_COOKIE["user_name"] != "") &&
    (isset($_COOKIE["user_email"]) && $_COOKIE["user_email"] != "") &&
    (isset($_COOKIE["user_pass"]) && $_COOKIE["user_pass"] != "")
  ) {
    $data = [
      "user_name" => $_COOKIE["user_name"],
      "user_email" => $_COOKIE["user_email"],
      "user_pass" => $_COOKIE["user_pass"]
    ];
  } elseif (
    (isset($_SESSION["user_name"]) && $_SESSION["user_name"] != "") &&
    (isset($_SESSION["user_email"]) && $_SESSION["user_email"] != "") &&
    (isset($_SESSION["user_pass"]) && $_SESSION["user_pass"] != "")
  ) {
    $data = [
      "user_name" => $_SESSION["user_name"],
      "user_email" => $_SESSION["user_email"],
      "user_pass" => $_SESSION["user_pass"]
    ];
  } else {
    $data = false;
  }
  return $data;
}