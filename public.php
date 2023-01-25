<?php
session_start();
ob_start();

// Required
require("assets/php/.func__get_user_data.php");
require("assets/php/.mysql__funcs.php");

// Cek apakah user sudah masuk
$_data = get_main_data();
if ($_data != false) {
  $user_name = $_data["user_name"];
  $user_email = $_data["user_email"];
  $user_pass = $_data["user_pass"];
  $user_account = mysqli_get_data("SELECT `user_cc`, `user_ip`, `user_profile` FROM `account` WHERE `user_name` = '".$user_name."' AND `user_email` = '".$user_email."' AND `user_pass` = '".$user_pass."'");
}

// Set user data
if (!isset($_COOKIE["user_ip"]) || !isset($_COOKIE["user_cc"])) {
  if (isset($user_account[0])) {
    $user = [
      "ip" => $user_account[0]["user_ip"],
      "country_code" => $user_account[0]["user_cc"],
    ];
  } else {
    $user = getUserIpData();
  }
  setcookie("user_ip", $user["ip"], time() + (60*60*24*7), "/");
  setcookie("user_cc", $user["country_code"], time() + (60*60*24*7), "/");
  header("Location: ./");
  exit();
}

// Set url
$_SESSION["data_url"] = $host."/";

// Get user cc
$user_cc = $_COOKIE["user_cc"];
?>
<!DOCTYPE html>
<html lang="<?= $user_cc === "ID" ? "id" : "en" ?>">
  <head>
    <meta name=”robots” content="index, follow" />
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>BeerdeMods - <?= $user_cc === "ID" ? "Temukan mod 100% bekerja dengan BeerdeMods!" : "Find a 100% working mod with BeerdeMods!" ?></title>
    <meta name="description" content="<?= $user_cc === "ID" ? "BeerdeMods adalah toko aplikasi dan game mod terpercaya yang disediakan oleh Beerde untuk perangkat Android." : "BeerdeMods is a trusted mod app and game store provided by Beerde for Android devices." ?>" />
    <meta name="keywords" content="BeerdeMods" />
    <link rel="shortcut icon" href="./assets/img/web__icon.png" />
    <!--Preconnect-->
    <link rel="preconnect" href="https://cdn.jsdelivr.net" />
    <link rel="preconnect" href="https://code.jquery.com" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="preconnect" href="https://site-assets.fontawesome.com" />
    <link rel="preconnect" href="https://unpkg.com" />
    <link rel="preconnect" href="https://script.google.com" />
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
    <link rel="stylesheet" href="./assets/css/web__style.css" />
    <!--JQuery-->
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
    <!--TypeIt-->
    <script src="https://unpkg.com/typeit@8.7.1/dist/index.umd.js"></script>
    <!--JS-->
    <script
      src="./assets/js/web__funcs.js"
      __data_lang="<?= base64_encode($user_cc) ?>"
      __data_change_lang_id="X191c2UvX3dlYl9sYW5nLnBocA=="
      __data_s_id="aHR0cHM6Ly9zY3JpcHQuZ29vZ2xlLmNvbS9tYWNyb3Mvcy9BS2Z5Y2J4RU85bXh5WjNYVkFjLTdGUVB2TEJTX3pXcmVISklmVWdxNVFPWE1rbm5QODRHYUs4a2dxeDJzYTR4bWNLTlpfdXEvZXhlYw=="
      __data_get_s_rsim="<?= base64_encode("__use/_get_s_rsim.php") ?>"
      __data_rm_s_rsim="<?= base64_encode("__use/_rm_s_rsim.php") ?>"
      __data_signout_account="<?= base64_encode("__use/_signout.php") ?>"
      __data_get_logined="<?= base64_encode("__use/_logined.php") ?>"
    ></script>
    <script src="./assets/js/web__sc.js"></script>
  </head>
  <body class="min-h-100 bg-custom-hex1C2021" data-bs-spy="scroll" data-bs-target="#autoActive" data-bs-smooth-scroll="true">
    <!--If the browser doesn't support JavaScript-->
    <noscript>
      <?= ($user_cc === "ID" ? "Browser kamu tidak mendukung JavaScript. :(" : "Your browser does not support JavaScript. :(") ?>
    </noscript>
    <!--end If the browser doesn't support JavaScript-->
    
    <div id="home"></div>
    <!--NavBar-->
    <nav class="navbar navbar-expand navbar-dark fixed-top py-1">
      <div class="container-fluid">
        <a class="navbar-brand" href="">
          <div class="wh-30px full-icon" style="background-image: url('./assets/img/web__icon.png');"></div>
        </a>
        <div class="collapse navbar-collapse">
          <div class="navbar-nav ms-auto">
            <a class="nav-link">
              <button
                class="btn border border-0 text-light p-0 fs-4 mx-1"
                type="button"
                data-bs-toggle="tooltip"
                data-bs-placement="left"
                data-bs-html="true"
                data-bs-title="Translate"
                data-bs-custom-class="tooltip-custom"
              >
                <i class="fa-regular fa-language"></i>
              </button>
            </a>
            <a class="nav-link">
              <button
                class="btn p-0 border border-0 wh-30px full-icon rounded-circle mx-1"
                style="background-image: url('<?= (isset($user_account[0]) && $user_account[0]["user_profile"] != "-" ? $user_account[0]["user_profile"] : "./assets/img/user_icon.png") ?>');"
                type="button"
                data-bs-toggle="tooltip"
                data-bs-placement="left"
                data-bs-html="true"
                data-bs-title="<?= ($user_cc === "ID" ? "Akun" : "Account") ?>"
                data-bs-custom-class="account-manager"
              ></button>
            </a>
            <a class="nav-link">
              <button class="btn border border-0 p-0 text-light fs-4 mx-1" type="button" data-bs-toggle="offcanvas" data-bs-target="#navbarme">
                <i class="fa-solid fa-bars-staggered"></i>
              </button>
            </a>
          </div>
        </div>
      </div>
    </nav>
    <nav class="navbar navbar-dark fixed-top">
      <div class="offcanvas offcanvas-end bg-custom-hex313131 text-light shadow-sm" tabindex="-1" id="navbarme">
        <div class="offcanvas-header">
          <h5 class="offcanvas-title pt-1">
            <?php if (!isset($user_account[0])): ?>
              <button type="button" class="btn border border-0 text-light btn-signin pe-0"><?= $user_cc === "ID" ? "Masuk" : "Sign In" ?></button>
              <span class="mx-1 pt-1"><i class="fa-solid fa-horizontal-rule rotate-90deg"></i></span>
              <button type="button" class="btn btn-outline-light btn-signup"><?= $user_cc === "ID" ? "Daftar" : "Sign Up" ?></button>
            <?php else: ?>
              <button type="button" class="btn btn-outline-signout btn-signout"><?= $user_cc === "ID" ? "Keluar" : "Sign Out" ?></button>
            <?php endif; ?>
          </h5>
          <button type="button" class="btn border border-0 p-0 text-light fs-4" data-bs-dismiss="offcanvas" style="transform: translate(-8.5px, -5.5px);"><i class="fa-solid fa-xmark-large"></i></button>
        </div>
        <div class="offcanvas-body">
          <ul class="navbar-nav justify-content-end flex-grow-1 pe-3 font-ubuntu-500" id="autoActive">
            <li class="nav-item py-3 ps-2">
              <a class="nav-link" href="#about"><?= ($user_cc === "ID" ? "Tentang" : "About") ?></a>
            </li>
            <li class="nav-item py-3 ps-2">
              <a class="nav-link" href="#superiority"><?= ($user_cc === "ID" ? "Keunggulan" : "Superiority") ?></a>
            </li>
            <li class="nav-item py-3 ps-2">
              <a class="nav-link" href="#faq">
                <?= ($user_cc === "ID" ? "Pertanyaan" : "FAQ") ?>
              </a>
            </li>
            <li class="nav-item py-3 ps-2">
              <a class="nav-link" href="#contact">
                <?= ($user_cc === "ID" ? "Kontak" : "Contact") ?>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    
    <!--Jumbotron-->
    <section id="jumbotron" class="position-relative">
      <div class="w-100 h-150px bg-hide-top position-absolute bottom-0 text-light"></div>
      <div class="bg-hide-tr">
        <div class="container pt-4rem pb-5">
          <div class="pt-5rem">
            <h1><span class="font-ubuntu-500 display-3 text-light typing"></span><span class="font-ubuntu-500 display-3 text-light">|</span></h1>
            <p class="pt-3 fs-5 text-light">
              <?= ($user_cc === "ID" ? "Temukan mod aplikasi yang Anda cari di sini." : "Find the application mod you are looking for here.") ?>
            </p>
          </div>
          <div class="pt-3">
            <div class="row">
              <?php if (isset($user_account[0])): ?>
                <div class="col-md-6">
                  <div class="px-3 pt-2">
                    <button type="button" class="btn w-100 rounded-pill border border-0 btn-outline-start p-3 fs-5 mb-3 btn-start"><?= $user_cc === "ID" ? "Mulai" : "Start" ?></button>
                  </div>
                </div>
              <?php else: ?>
                <div class="col-md-6">
                  <div class="px-3">
                    <button type="button" class="btn w-100 rounded-pill border border-0 bg-custom-hex000000 text-light p-3 fs-5 mb-3 btn-signin"><?= $user_cc === "ID" ? "Masuk" : "Sign In" ?></button>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="px-3">
                    <button type="button" class="btn w-100 rounded-pill border border-0 bg-light p-3 fs-5 mb-3 btn-signup"><?= $user_cc === "ID" ? "Daftar" : "Sign Up" ?></button>
                  </div>
                </div>
              <?php endif; ?>
            </div>
          </div>
          <div class="pt-6rem text-center text-light fs-5">
            <div class="swipe-down fa-bounce d-inline-block">
              <span class="d-block"><?= ($user_cc === "ID" ? "Geser ke bawah" : "Swipe down") ?></span>
              <span class="d-block"><i class="fa-regular fa-arrow-down"></i></span>
            </div>
          </div>
        </div>
      </div>
    </section>
    
    <!--Introduction-->
    <section>
      <div class="bg-hide-br">
        <div class="container pt-4rem pb-5">
          <h2 class="font-ubuntu-500 display-5 text-light"><?= ($user_cc === "ID" ? "Untuk Anda" : "For You") ?></h2>
          <div class="pt-5">
            <div class="row">
              <div class="col-lg-4">
                <div class="px-2 pb-2">
                  <div class="bg-custom-hex1C2021 text-light rounded p-2">
                    <span class="fs-6 font-poppins text-hex00bcb4 d-block">
                      <i class="fa-regular fa-hashtag"></i>
                      <?= ($user_cc === "ID" ? "Game Online Multiplayer" : "Multiplayer Online Games") ?>
                    </span>
                  </div>
                </div>
              </div>
              <div class="col-lg-4">
                <div class="px-2 pb-2">
                  <div class="bg-custom-hex1C2021 text-light rounded p-2">
                    <span class="fs-6 font-poppins text-hex00bcb4 d-block">
                      <i class="fa-regular fa-hashtag"></i>
                      <?= ($user_cc === "ID" ? "Aplikasi Pengedit" : "Editing Application") ?>
                    </span>
                  </div>
                </div>
              </div>
              <div class="col-lg-4">
                <div class="px-2 pb-2">
                  <div class="bg-custom-hex1C2021 text-light rounded p-2">
                    <span class="fs-6 font-poppins text-hex00bcb4 d-block">
                      <i class="fa-regular fa-hashtag"></i>
                      <?= ($user_cc === "ID" ? "Game Offline" : "Offline Games") ?>
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="bg-delete-br">
        <div class="container pt-4rem">
          <div class="px-2">
            <div class="bg-dark rounded-top pt-3 pb-1 px-3">
              <span class="font-poppins fs-4 d-block text-hex00bcb4">
                <?= ($user_cc === "ID" ? "Apa itu BeerdeMods?" : "What is BeerdeMods?") ?>
              </span>
              <p class="pt-4 fs-5 text-light">
                <span class="ms-2">
                  <?= ($user_cc === "ID" ? "BeerdeMods adalah sebuah toko aplikasi dan game mod yang terpercaya yang disediakan oleh perusahaan bernama Beerde untuk perangkat Android. Mereka menyediakan berbagai macam aplikasi dan game mod yang berkualitas dan teruji untuk perangkat Android, sehingga kamu dapat dengan mudah menemukan dan mendownload aplikasi atau game mod yang kamu inginkan dengan kepercayaan yang tinggi. Selain itu, BeerdeMods juga menjamin keamanan dan kemudahan dalam proses download, sehingga kamu dapat dengan tenang menggunakan layanan mereka tanpa perlu khawatir akan keamanan perangkat atau data pribadi." : "BeerdeMods is a trusted app and game mod store provided by a company called Beerde for Android devices. They provide a wide range of quality and tested mod apps and games for Android devices, so you can easily find and download the mod apps or games you want with high confidence. Apart from that, BeerdeMods also guarantees safety and convenience in the download process, so you can calmly use their services without worrying about the security of your device or personal data.") ?>
                </span>
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>
    
    <!--About-->
    <section id="about">
      <div class="container">
        <div class="px-2 pb-5">
          <div class="bg-dark rounded-bottom pt-4rem pb-3 px-3 text-light">
            <h2 class="font-ubuntu-500 display-5"><?= ($user_cc === "ID" ? "Tentang Kami" : "About Us") ?></h2>
            <p class="pt-4 fs-5">
              <span class="ms-2">
                <?= ($user_cc === "ID" ? "Kami menyediakan aplikasi dan game mod untuk perangkat Android yang dikelola oleh Beerde. Kami menawarkan berbagai pilihan aplikasi dan game mod berkualitas yang telah kami uji untuk perangkat Android, sehingga Anda dapat dengan mudah menemukan dan mengunduh aplikasi atau game mod yang Anda inginkan dengan keyakinan yang tinggi. Selain itu, Kami juga menjamin keamanan dan kemudahan dalam proses download, sehingga Anda dapat dengan tenang menggunakan layanan kami tanpa khawatir tentang keamanan perangkat atau privasi data Anda." : "We provide modded apps and games for Android devices managed by Beerde. We offer a wide selection of quality modded apps and games that we have tested for Android devices, so you can easily find and download the modded apps or games you want with high confidence. Apart from that, we also guarantee security and convenience in the download process, so you can calmly use our services without worrying about the security of your device or the privacy of your data.") ?>
              </span>
            </p>
          </div>
        </div>
      </div>
    </section>
    
    <!--Superiority-->
    <section id="superiority">
      <div class="container pt-4rem pb-5">
        <h2 class="text-light display-5 font-ubuntu-500">
          <?= ($user_cc === "ID" ? "Keunggulan" : "Superiority") ?>
        </h2>
        <div class="pt-5">
          <div class="row">
            <div class="col-md-4">
              <div class="px-2 mb-2">
                <div class="text-bg-dark rounded p-2">
                  <div class="p-2 position-relative">
                    <div class="ratio ratio-1x1">
                      <div class="w-100 h-100 bg-hex00bcb4 rounded-circle"></div>
                    </div>
                    <span class="position-absolute top-50 start-50 translate-middle icon-size-auto text-light">
                      <i class="fa-duotone fa-boxes-stacked fa-3x"></i>
                    </span>
                  </div>
                  <span class="d-block cfs-auto text-hex00bcb4 font-poppins pt-3">
                    <?= ($user_cc === "ID" ? "Banyak Aplikasi" : "Many Applications") ?>
                  </span>
                  <p class="text-light fs-6 pt-1">
                    <span class="ms-1">
                      <?= ($user_cc === "ID" ? "Kami menyediakan aplikasi dan game mod berkualitas untuk Android yang telah teruji, sehingga Anda dapat dengan mudah menemukan dan mengunduh yang Anda inginkan." : "We provide quality modded apps and games for Android that have been tested, so you can easily find and download what you want.") ?>
                    </span>
                  </p>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="px-2 mb-2">
                <div class="text-bg-dark rounded p-2">
                  <div class="p-2 position-relative">
                    <div class="ratio ratio-1x1">
                      <div class="w-100 h-100 bg-hex00bcb4 rounded-circle"></div>
                    </div>
                    <span class="position-absolute top-50 start-50 translate-middle icon-size-auto text-light">
                      <i class="fa-duotone fa-rabbit-running fa-3x" style="--fa-primary-opacity: 0.4; --fa-secondary-opacity: 1;"></i>
                    </span>
                  </div>
                  <span class="d-block cfs-auto text-hex00bcb4 font-poppins pt-3">
                    <?= ($user_cc === "ID" ? "99% Akses Cepat" : "99% Quick Access") ?>
                  </span>
                  <p class="text-light fs-6 pt-1">
                    <span class="ms-1">
                      <?= ($user_cc === "ID" ? "Anda tidak akan terganggu oleh iklan, sehingga Anda dapat dengan mudah mengunduh aplikasi dan game yang ada disini. Kami mengutamakan kenyamanan anda disini." : "You will not be disturbed by advertisements, so you can easily download the applications and games that are here. We prioritize your comfort here.") ?>
                    </span>
                  </p>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="px-2 mb-2">
                <div class="text-bg-dark rounded p-2">
                  <div class="p-2 position-relative">
                    <div class="ratio ratio-1x1">
                      <div class="w-100 h-100 bg-hex00bcb4 rounded-circle"></div>
                    </div>
                    <span class="position-absolute top-50 start-50 translate-middle icon-size-auto text-light">
                      <i class="fa-duotone fa-shield-check fa-3x" style="--fa-primary-opacity: 0.4; --fa-secondary-opacity: 1;"></i>
                    </span>
                  </div>
                  <span class="d-block cfs-auto text-hex00bcb4 font-poppins pt-3">
                    <?= ($user_cc === "ID" ? "Privasi Aman" : "Secure Privacy") ?>
                  </span>
                  <p class="text-light fs-6 pt-1">
                    <span class="ms-1">
                      <?= ($user_cc === "ID" ? "Anda dapat dengan tenang menggunakan layanan kami tanpa harus khawatir keamanan privasi data Anda, kami memastikan bahwa semua data Anda akan aman saat menggunakan layanan kami." : "You can safely use our services without having to worry about the security of your data privacy, we ensure that all your data will be safe when using our services.") ?>
                    </span>
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    
    <!--FAQ-->
    <section id="faq">
      <div class="container pt-4rem pb-5">
        <h3 class="display-5 text-light font-ubuntu-500">
          <?= ($user_cc === "ID" ? "Pertanyaan yang Sering Diajukan" : "Frequently Asked Questions") ?>
        </h3>
        <div class="pt-5">
          <div class="row">
            <div class="col-md-4">
              <div class="px-2 pb-2">
                <div class="p-2 text-bg-dark rounded">
                  <h3 class="fs-4 font-poppins text-hex00bcb4">
                    <?= ($user_cc === "ID" ? "Apa itu mod?" : "What are mods?") ?>
                  </h3>
                  <div class="pt-2 fs-6">
                    <span class="ms-1">
                      <?= ($user_cc === "ID" ? "Mod (kependekan dari &quot;Modifikasi&quot;) adalah perubahan satu atau lebih aspek permainan video, seperti tampilan atau cara berinteraksi yang dibuat oleh pemain atau penggemar permainan video. Mod dapat berkisar dari perubahan kecil sampai perombakan seutuhnya, dan dapat menambah nilai dan minat ke permainan tersebut." : "Mods (short for &quot;Modifications&quot;) are changes to one or more aspects of a video game, such as the appearance or way of interacting, made by a player or fan of a video game. Mods can range from minor tweaks to a complete overhaul, and can add value and interest to the game.") ?>
                    </span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="px-2 pb-2">
                <div class="p-2 text-bg-dark rounded">
                  <h3 class="fs-4 font-poppins text-hex00bcb4">
                    <?= ($user_cc === "ID" ? "Apakah mod dan cheat itu sama?" : "Are mods and cheats the same?") ?>
                  </h3>
                  <div class="pt-2 fs-6">
                    <span class="ms-1">
                      <?= ($user_cc === "ID" ? "Jadi dapat disimpulkan bahwa perbedaan yang paling mendasar adalah cheat dipakai untuk mencurangi lawan, sedangkan MOD hanya untuk merubah konten dalam game agar permainan tampak berbeda dan bersuasana baru." : "So it can be concluded that the most basic difference is that cheats are used to cheat opponents, while MOD is only for changing in-game content so that the game looks different and has a new atmosphere.") ?>
                    </span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="px-2 pb-2">
                <div class="p-2 text-bg-dark rounded">
                  <h3 class="fs-4 font-poppins text-hex00bcb4">
                    <?= ($user_cc === "ID" ? "Apa kelebihan aplikasi mod?" : "What are the advantages of the mod application?") ?>
                  </h3>
                  <div class="pt-2 fs-6">
                    <span class="ms-1">
                      <?= ($user_cc === "ID" ? "Aplikasi mod menawarkan fitur atau kemampuan yang tidak tersedia di versi asli aplikasi tersebut. Seperti dapat digunakan secara gratis, Tanpa iklan, fitur tambahan yang tidak tersedia di versi asli aplikasi tersebut dan lainnya." : "Mod apps offer features or capabilities that are not available in the original version of the app. As can be used for free, No ads, additional features that are not available in the original version of the application and others.") ?>
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    
    <!--Member-->
    <section id="member">
      <div class="container pt-4rem pb-5">
        <h2 class="display-5 text-light font-ubuntu-500">
          <?= ($user_cc === "ID" ? "Keanggotaan" : "Membership") ?>
        </h2>
        <div class="pt-4 px-2">
          <div class="bg-hex00bcb4 rounded">
            <iframe src="https://saweria.co/widgets/recent?streamKey=d5d0d7d433c34a61096f7ae3414acac7" width="100%" height="64px" allowtransparency="true" scrolling="no" frameborder="0" framespacing="0" class="bg-transparent m-0 p-0"></iframe>
          </div>
          <div class="bg-dark rounded mt-3">
            <iframe src="https://saweria.co/widgets/leaderboard?streamKey=d5d0d7d433c34a61096f7ae3414acac7" width="100%" height="450px" allowtransparency="true" frameborder="0" framespacing="0" class="bg-transparent m-0 p-0"></iframe>
          </div>
        </div>
      </div>
    </section>
    
    <!--Contact-->
    <section id="contact">
      <div class="container pt-4rem pb-5">
        <h2 class="display-5 text-light font-ubuntu-500">
          <?= ($user_cc === "ID" ? "Hubungi Kami" : "Contact Us") ?>
        </h2>
        <div class="pt-5">
          <div class="row justify-content-center">
            <div class="col-md-7">
              <div class="px-2">
                <div class="text-bg-dark p-2 rounded position-relative save-height">
                  <form name="form-contact">
                    <div class="form-floating mb-2 pb-0">
                      <input type="text" class="form-control input-custom" id="inputName" placeholder="<?= ($user_cc === "ID" ? "Nama kamu" : "Your name") ?>" required minlength="3" name="name">
                      <label for="inputName"><?= ($user_cc === "ID" ? "Nama" : "Name") ?></label>
                      <div class="position-relative w-100">
                        <div class="px-2 pb-2 w-100 position-absolute bottom-100">
                          <div class="w-100 h-1k2px bg-custom-hex00bcb4"></div>
                        </div>
                      </div>
                    </div>
                    <div class="form-floating mb-2 pb-0">
                      <input type="text" class="form-control input-custom" id="inputEmail" placeholder="+62877xx / email@gmail.com" required minlength="5" name="contact">
                      <label for="inputEmail"><?= ($user_cc === "ID" ? "Email atau Telepon" : "Email or Phone") ?></label>
                      <div class="position-relative w-100">
                        <div class="px-2 pb-2 w-100 position-absolute bottom-100">
                          <div class="w-100 h-1k2px bg-custom-hex00bcb4"></div>
                        </div>
                      </div>
                    </div>
                    <div class="form-floating mb-2 pb-0">
                      <textarea class="form-control input-custom" id="inputMassage" placeholder="<?= ($user_cc === "ID" ? "Tinggalkan pesan" : "Leave a message") ?>..." required minlength="5" name="massage"></textarea>
                      <label for="inputMassage"><?= ($user_cc === "ID" ? "Pesan" : "Massage") ?></label>
                      <div class="position-relative w-100">
                        <div class="px-2 pb-1 w-100 position-absolute bottom-100">
                          <div class="w-100 h-1k2px bg-custom-hex00bcb4"></div>
                        </div>
                      </div>
                    </div>
                    <div class="p-2 position-relative">
                      <div class="d-inline-block">
                        <a href="https://www.instagram.com/prayoga_brd" target="_blank" class="btn border border-0 position-relative text-light me-1 fs-4 rounded-circle contact-icon p-0 bg-custom-hexFE0079">
                          <i class="fa-brands fa-instagram"></i>
                        </a>
                        <a href="https://discordapp.com/users/1036671636730036255" target="_blank" class="btn border border-0 position-relative text-light mx-1 fs-4 rounded-circle contact-icon p-0 bg-custom-hex5865F2">
                          <i class="fa-brands fa-discord"></i>
                        </a>
                        <a href="https://github.com/prayogabrd" target="_blank" class="btn border border-0 text-light ms-1 fs-4 rounded-circle bg-black position-relative contact-icon p-0">
                          <i class="fa-brands fa-github"></i>
                        </a>
                      </div>
                      <div class="d-inline-block position-absolute end-0 pe-2">
                        <button type="submit" class="btn btn-outline-light submit-form"><?= ($user_cc === "ID" ? "Kirim" : "Send") ?></button>
                      </div>
                    </div>
                  </form>
                  <div class="loading-result text-center position-absolute top-50 start-50 translate-middle d-none">
                    <span class="d-block fs-3 text-light" style="--bs-text-opacity: 0.5;">
                      <i class="fa-duotone fa-spinner-third fa-3x fa-spin"></i>
                    </span>
                  </div>
                  <div class="result-success text-center position-absolute top-50 start-50 translate-middle d-none">
                    <span class="d-block fs-3 text-hex00bcb4">
                      <i class="fa-duotone fa-message-check fa-3x"></i>
                    </span>
                    <span class="d-block fs-6 mt-3">
                      <button class="btn border border-0 p-0 fs-6 text-hex00bcb4" type="button" id="btnSuccess"><?= ($user_cc === "ID" ? "Kirim Lagi" : "Send Again") ?></button>
                    </span>
                  </div>
                  <div class="result-error text-center position-absolute top-50 start-50 translate-middle d-none">
                    <span class="d-block fs-3 text-warning">
                      <i class="fa-duotone fa-message-exclamation fa-3x"></i>
                    </span>
                    <span class="d-block fs-6 mt-3">
                      <button class="btn border border-0 p-0 fs-6 text-warning" type="button" id="btnTryAgain"><?= ($user_cc === "ID" ? "Ulangi" : "Repeat") ?></button>
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    
    <!--Footer-->
    <footer>
      <div class="bg-custom-hex313131">
        <div class="container ptc-3">
          <div class="row">
            <div class="col-6">
              <div class="px-2 text-light">
                <h2 class="fs-3 fw-bold">Section</h2>
                <hr class="m-0" />
                <div class="p-2 fs-6">
                  <span class="mb-2 d-block">
                    <a href="#home" class="text-reset text-decoration-none">Hero</a>
                  </span>
                  <span class="mb-2 d-block">
                    <a href="#about" class="text-reset text-decoration-none"><?= ($user_cc === "ID" ? "Tentang" : "About") ?></a>
                  </span>
                  <span class="mb-2 d-block">
                    <a href="#superiority" class="text-reset text-decoration-none"><?= ($user_cc === "ID" ? "Keunggulan" : "Superiority") ?></a>
                  </span>
                  <span class="mb-2 d-block">
                    <a href="#faq" class="text-reset text-decoration-none">FAQ</a>
                  </span>
                  <span class="mb-2 d-block">
                    <a href="#contact" class="text-reset text-decoration-none"><?= ($user_cc === "ID" ? "Kontak / Bantuan" : "Contact / Help") ?></a>
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
          <div class="px-2 pb-2">
            <div class="d-flex flex-wrap justify-content-center">
              <div class="d-inline-block">
                <div class="fs-6 text-light d-flex flex-wrap ch">
                  <div class="wh-50px full-icon d-inline-block me-2 align-self-center" style="background-image: url('./assets/img/web__icon.png');"></div>
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
    
    <!--Bootstrap JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
  </body>
</html>