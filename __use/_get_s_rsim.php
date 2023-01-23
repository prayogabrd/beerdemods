<?php
session_start();
header("Content-Type: application/json; charset=utf-8");
$result = [
  "rsim" => (isset($_SESSION["result_signin_massage"]) ? $_SESSION["result_signin_massage"] : "")
];
echo json_encode($result);