<?php
session_start();
header("Content-Type: application/json; charset=utf-8");

// Cek sesi
if (isset($_SESSION["uploaded_image"])) {
  $result = [
    "success" => true,
    "data" => $_SESSION["uploaded_image"]
  ];
} else {
    $result = [
    "success" => false,
    "data" => []
  ];
}

// Kembalikan data
unset($_SESSION["uploaded_image"]);
echo(json_encode($result));