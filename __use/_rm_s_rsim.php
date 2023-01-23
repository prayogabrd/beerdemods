<?php
session_start();
header("Content-Type: application/json; charset=utf-8");
if (isset($_SESSION["result_signin_massage"])) {
  unset($_SESSION["result_signin_massage"]);
}
$result = [
  "rsim" => ""
];
echo json_encode($result);