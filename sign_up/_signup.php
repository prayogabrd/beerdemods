<?php
session_start();
ob_start();

// Required
require("../assets/php/.func__get_user_data.php");
require("../assets/php/.mysql__funcs.php");

// Retrieve data from fetch
$_fetch = json_decode(file_get_contents("php://input"), true);

// Cek apakah sudah signin sebelumnya
$_data = get_main_data();
if ($_data != false) {
  $user_name = $_data["user_name"];
  $user_email = $_data["user_email"];
  $user_pass = $_data["user_pass"];
  $user_account = mysqli_get_data("SELECT `user_name` FROM `account` WHERE `user_name` = '".$user_name."' AND `user_email` = '".$user_email."' AND `user_pass` = '".$user_pass."'");
}

// Cek jenis permintaan
?>
<?php if (isset($_fetch["__utilization_genre"]) && $_fetch["__utilization_genre"] === "initiate_a_request"): ?>
  <?php
  header("Content-Type: application/json; charset=utf-8");
  
  // Cek apakah sudah masuk sebelumnya
  if (isset($user_account[0])) {
    $__signup = [
      "status" => false,
      "massage" => ($_fetch["user_cc"] === "ID" ? "Anda sudah masuk sebelumnya!" : "You've logged in before!")
    ];
  } else {
    // Pendaftaran Akun
    $__signup = mysqli_signup($_fetch);
  }
  
  // Kembalikan hasil
  echo(json_encode($__signup));
  ?>
<?php else: ?>
  <?php
  // Check previous position
  if (!isset($_SESSION["data_url"])) {
    $_SESSION["data_url"] = "";
  }
  $data_url = ($_SESSION["data_url"] === "" ? "../" : $_SESSION["data_url"]);
  
  // Redirect if already logged in
  if (isset($user_account[0])) {
    header("Location: $data_url");
    exit();
  }
  
  // Set up
  if (!isset($_COOKIE["user_ip"]) || !isset($_COOKIE["user_cc"])) {
    $user = getUserIpData();
    setcookie("user_ip", $user["ip"], time() + (60*60*24*7), "/");
    setcookie("user_cc", $user["country_code"], time() + (60*60*24*7), "/");
    header("Location: ./");
    exit();
  }
  
  // Get user data
  $user_ip = $_COOKIE["user_ip"];
  $user_cc = $_COOKIE["user_cc"];
  ?>
  <!DOCTYPE html>
  <html lang="<?= $user_cc === "ID" ? "id" : "en" ?>">
    <head>
      <meta name=”robots” content="index, follow" />
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
      <meta http-equiv="X-UA-Compatible" content="ie=edge">
      <title><?= $user_cc === "ID" ? "Daftar" : "Sign Up" ?> - BeerdeMods</title>
      <meta name="description" content="<?= $user_cc === "ID" ? "BeerdeMods adalah toko aplikasi dan game mod terpercaya yang disediakan oleh Beerde untuk perangkat Android. Daftar sekarang!" : "BeerdeMods is a trusted mod app and game store provided by Beerde for Android devices. Sign up now!" ?>" />
      <meta name="keywords" content="BEERDEMODS, SIGNUP, SIGN UP, LOGIN, DAFTAR, BUAT AKUN" />
      <link rel="shortcut icon" href="../assets/img/web__icon.png" />
      <!--Preconnect-->
      <link rel="preconnect" href="https://cdn.jsdelivr.net" />
      <link rel="preconnect" href="https://code.jquery.com" />
      <link rel="preconnect" href="https://fonts.googleapis.com" />
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
      <link rel="preconnect" href="https://site-assets.fontawesome.com" />
      <!--Bootstrap CSS-->
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous" />
      <!--Google Fonts-->
      <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lato&family=Ubuntu:wght@500&family=Poppins:wght@500&display=swap" />
      <!--FontAwesome Icons-->
      <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.2.1/css/all.css" />
      <!--SweetAlert2-->
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@5.0.15/dark.min.css" integrity="sha256-Dtn0fzAID6WRybYFj3UI5JDBy9kE2adX1xPUlW+B4XQ=" crossorigin="anonymous" />
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.0/dist/sweetalert2.min.js" integrity="sha256-1X1zUlVXzVybYb9gOcxg5DUgtS9faIHrdRprMphiF98=" crossorigin="anonymous"></script>
      <!--CSS-->
      <link rel="stylesheet" href="../assets/css/web__signup_style.css" />
      <!--JQuery-->
      <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
      <!--JS-->
      <script
        src="../assets/js/web__funcs.js"
        __data_lang="<?= base64_encode($user_cc) ?>"
        __data_user_ip="<?= base64_encode($user_ip) ?>"
        __data_change_lang_id="<?= base64_encode("../__use/_web_lang.php") ?>"
        __data_previous_url="<?= base64_encode($data_url) ?>"
      ></script>
      <script src="../assets/js/web__signup_sc.js"></script>
    </head>
    <body class="min-h-100 bg-custom-hex1C2021 text-light position-relative">
      <!--If the browser doesn't support JavaScript-->
      <noscript>
        <?= ($user_cc === "ID" ? "Browser kamu tidak mendukung JavaScript. :(" : "Your browser does not support JavaScript. :(") ?>
      </noscript>
      <!--end If the browser doesn't support JavaScript-->
      
      <!--Navbar-->
      <nav class="navbar navbar-expand navbar-dark fixed-top py-1 bg-custom-hex313131 shadow-sm">
        <div class="container-fluid">
          <a class="navbar-brand" href="<?= $data_url ?>">
            <div class="wh-30px <?= ($data_url === "../" ? "full-icon" : "position-relative") ?>"<?= ($data_url === "../" ? " style=\"background-image: url('../assets/img/web__icon.png');\"" : "") ?>>
              <?php if ($data_url != "../"): ?>
                <span class="d-inline-block position-absolute top-50 start-50 translate-middle fs-5">
                  <i class="fa-regular fa-arrow-left"></i>
                </span>
              <?php endif; ?>
            </div>
          </a>
          <div class="collapse navbar-collapse">
            <div class="navbar-nav ms-auto">
              <a class="nav-link">
                <button class="btn border border-0 text-light p-0 fs-4 mx-1" type="button" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-html="true" data-bs-title="Translate" data-bs-custom-class="tooltip-custom">
                  <i class="fa-regular fa-language"></i>
                </button>
              </a>
              <a class="nav-link">
                <button class="btn border border-0 p-0 text-light fs-4 mx-1" type="button" data-bs-toggle="offcanvas" data-bs-target="#navbarOffcanvas">
                  <i class="fa-solid fa-bars-staggered"></i>
                </button>
              </a>
            </div>
          </div>
        </div>
      </nav>
      <nav class="navbar navbar-dark fixed-top">
        <div class="offcanvas offcanvas-end bg-custom-hex313131 text-light shadow-sm" tabindex="-1" id="navbarOffcanvas">
          <div class="offcanvas-header">
            <h5 class="offcanvas-title pt-1">
              <button type="button" class="btn btn-submit-signin btn-signin"><?= $user_cc === "ID" ? "Masuk" : "Sign In" ?></button>
            </h5>
            <button type="button" class="btn border border-0 p-0 text-light fs-4" data-bs-dismiss="offcanvas" style="transform: translate(-8.5px, -5.5px);"><i class="fa-solid fa-xmark-large"></i></button>
          </div>
          <div class="offcanvas-body">
            <ul class="navbar-nav justify-content-end flex-grow-1 pe-3 font-ubuntu-500">
              <li class="nav-item py-3 ps-2">
                <a class="nav-link" target="_blank" href="../#about"><?= ($user_cc === "ID" ? "Tentang" : "About") ?></a>
              </li>
              <li class="nav-item py-3 ps-2">
                <a class="nav-link" target="_blank" href="../#contact">
                  <?= ($user_cc === "ID" ? "Kontak / Bantuan" : "Contact / Help") ?>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </nav>
      
      <!--Sign Up Form-->
      <section id="signupForm">
        <div class="container pt-5rem pb-5">
          <div class="row justify-content-center">
            <div class="col-md-9">
              <div class="px-2">
                <div class="p-2 pt-3 bg-dark rounded">
                  <h2 class="display-5 font-ubuntu-500 text-center text-light"><?= $user_cc === "ID" ? "Daftar" : "Sign Up" ?></h2>
                  <div class="pt-3">
                    <form id="form-signup">
                      <div class="form-floating mb-2">
                        <input type="text" class="form-control input-custom" id="name" placeholder="<?= ($user_cc === "ID" ? "Nama kamu" : "Your name") ?>" required minlength="3">
                        <label for="name"><?= ($user_cc === "ID" ? "Nama" : "Name") ?></label>
                        <div class="position-relative w-100">
                          <div class="px-2 pb-2 w-100 position-absolute bottom-100">
                            <div class="w-100 h-1k2px bg-custom-hex00bcb4"></div>
                          </div>
                        </div>
                      </div>
                      <div class="form-floating mb-2">
                        <input type="text" class="form-control input-custom" id="username" placeholder="username" required minlength="5">
                        <label for="username">Username</label>
                        <div class="position-relative w-100">
                          <div class="px-2 pb-2 w-100 position-absolute bottom-100">
                            <div class="w-100 h-1k2px bg-custom-hex00bcb4"></div>
                          </div>
                        </div>
                      </div>
                      <div class="form-floating mb-2">
                        <input type="email" class="form-control input-custom" id="email" placeholder="example@abc.xyz" required minlength="5">
                        <label for="email">Email</label>
                        <div class="position-relative w-100">
                          <div class="px-2 pb-2 w-100 position-absolute bottom-100">
                            <div class="w-100 h-1k2px bg-custom-hex00bcb4"></div>
                          </div>
                        </div>
                      </div>
                      <div class="form-floating mb-2">
                        <input type="password" class="form-control input-custom" id="password" placeholder="Password12321" required minlength="8" autocomplete="off">
                        <label for="password"><?= ($user_cc === "ID" ? "Kata sandi" : "Password") ?></label>
                        <div class="position-relative w-100">
                          <div class="px-2 pb-2 w-100 position-absolute bottom-100">
                            <div class="w-100 h-1k2px bg-custom-hex00bcb4"></div>
                          </div>
                        </div>
                      </div>
                      <div class="form-floating mb-3">
                        <input type="password" class="form-control input-custom" id="passwordConfirm" placeholder="Password12321" required minlength="8" autocomplete="off">
                        <label for="passwordConfirm"><?= ($user_cc === "ID" ? "Konfirmasi Kata sandi" : "Confirm Password") ?></label>
                        <div class="position-relative w-100">
                          <div class="px-2 pb-2 w-100 position-absolute bottom-100">
                            <div class="w-100 h-1k2px bg-custom-hex00bcb4"></div>
                          </div>
                        </div>
                      </div>
                      <div class="mb-4 px-2">
                        <div class="form-check">
                          <input class="form-check-input border-color-hex00bcb4" type="checkbox" id="checkConfirm" required>
                          <label class="form-check-label">
                            <?= ($user_cc === "ID" ? "Saya setuju dengan" : "I agree with the") ?> <a href="../terms-and-service"><?= ($user_cc === "ID" ? "syarat dan ketentuan" : "terms and service") ?></a>.
                          </label>
                        </div>
                      </div>
                      <div class="p-2">
                        <div class="d-block mb-2">
                          <button type="submit" class="btn btn-submit-signup w-100 py-3 position-relative">
                            <span class="btn-submit-text">
                              <?= ($user_cc === "ID" ? "Daftar" : "Sign Up") ?>
                            </span>
                            <span class="position-absolute top-50 start-50 translate-middle fs-4 notransition btn-submit-loading opacity-0">
                              <i class="fa-regular fa-spinner-third fa-spin notransition"></i>
                            </span>
                          </button>
                        </div>
                        <div class="d-block">
                          <button type="button" class="btn btn-submit-signin w-100 py-3 btn-signin"><?= ($user_cc === "ID" ? "Masuk" : "Sign In") ?></button>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
      
      <!--Footer-->
      <div id="fixFooter"></div>
      <footer id="myFooter" class="position-absolute w-100 bottom-0">
        <div class="bg-custom-hex313131">
          <div class="container ptc-3">
            <div class="row">
              <div class="col-6">
                <div class="px-2 text-light">
                  <h2 class="fs-3 fw-bold">Section</h2>
                  <hr class="m-0" />
                  <div class="p-2 fs-6">
                    <span class="mb-2 d-block">
                      <a href="../#about" target="_blank" class="text-reset text-decoration-none"><?= ($user_cc === "ID" ? "Tentang" : "About") ?></a>
                    </span>
                    <span class="mb-2 d-block">
                      <a href="../#contact" target="_blank" class="text-reset text-decoration-none"><?= ($user_cc === "ID" ? "Kontak / Bantuan" : "Contact / Help") ?></a>
                    </span>
                  </div>
                </div>
              </div>
              <div class="col-6">
                <div class="px-2 text-light">
                  <h2 class="fs-3 fw-bold"><?= ($user_cc === "ID" ? "Tautan" : "Link") ?></h2>
                  <hr class="m-0" />
                  <div class="p-2 fs-6">
                    <span class="mb-2 d-block">
                      <a href="https://saweria.co/beerde" target="_blank" class="text-reset text-decoration-none"><?= ($user_cc === "ID" ? "Donasi" : "Donation") ?></a>
                    </span>
                    <span class="mb-2 d-block">
                      <a href="https://t.me/+VwkH97RwS1kxZTg1" target="_blank" class="text-reset text-decoration-none">Telegram</a>
                    </span>
                    <span class="mb-2 d-block">
                      <a href="https://www.instagram.com/prayoga_brd" target="_blank" class="text-reset text-decoration-none">Instagram</a>
                    </span>
                    <span class="mb-2 d-block">
                      <a href="https://github.com/prayogabrd" target="_blank" class="text-reset text-decoration-none">Github</a>
                    </span>
                    <span class="mb-2 d-block">
                      <a href="https://discordapp.com/users/1036671636730036255" target="_blank" class="text-reset text-decoration-none">Discord</a>
                    </span>
                  </div>
                </div>
              </div>
            </div>
            <hr class="text-light" />
            <div class="px-2 pb-3">
              <div class="d-flex flex-wrap justify-content-center">
                <div class="d-inline-block">
                  <div class="fs-6 text-light d-flex flex-wrap ch">
                    <div class="wh-50px full-icon d-inline-block me-2 align-self-center" style="background-image: url('../assets/img/web__icon.png');"></div>
                    <div class="vrc ph bg-light"></div>
                    <div class="d-inline-block ps-2 align-self-center">
                      <span class="d-block fw-bold">BeerdeMods by Beerde</span>
                      <span class="d-block">Email: beerdemods@aol.com</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </footer>
      
      <!--Loading-->
      <div class="position-fixed top-0 bottom-0 start-0 end-0 bg-custom-hex1C2021" style="z-index: 9999; transition: none !important;" id="loader">
        <div class="position-absolute top-50 start-50 translate-middle display-3">
          <i class="fa-duotone fa-spinner-third fa-3x fa-spin text-hex00bcb4"></i>
        </div>
      </div>
      
      <!--Bootstrap JS-->
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    </body>
  </html>
<?php endif; ?>