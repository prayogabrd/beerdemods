<?php
session_start();
header("Content-Type: application/json; charset=utf-8");

// Lepas sesi login
unset($_SESSION["user_name"]);
unset($_SESSION["user_email"]);
unset($_SESSION["user_pass"]);
setcookie("user_name", false, -1, "/");
setcookie("user_email", false, -1, "/");
setcookie("user_pass", false, -1, "/");
unset($_COOKIE["user_name"]);
unset($_COOKIE["user_email"]);
unset($_COOKIE["user_pass"]);