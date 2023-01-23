<?php
// Required
require "../assets/php/.func__get_user_data.php";
// Check
if (!isset($_COOKIE["user_ip"])) {
  setcookie("user_ip", getUserIpData()["ip"], time() + (60*60*24*7), "/");
}
// Main
header("Content-Type: application/json; charset=utf-8");
$accept = file_get_contents("php://input");
$accept = json_decode($accept, true);
$results = ["success" => false];
if (isset($accept["language"]) && $accept["language"] != "") {
  $user_cc = ($accept["language"] == 'id' ? 'ID' : 'EN');
  setcookie("user_cc", $user_cc, time() + (60*60*24*7), "/");
  $results["success"] = true;
}
echo json_encode($results);