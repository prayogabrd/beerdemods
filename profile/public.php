<?php
session_start();
ob_start();

// Required
require("../assets/php/.func__get_user_data.php");
require("../assets/php/.func__upload_image.php");
require("../assets/php/.mysql__funcs.php");

// Check previous position
if (!isset($_SESSION["data_url"])) {
  $_SESSION["data_url"] = "";
}
$data_url = ($_SESSION["data_url"] === "" ? "../" : $_SESSION["data_url"]);

// Cek apakah user sudah masuk
$_data = get_main_data();
if ($_data != false) {
  $user_name = $_data["user_name"];
  $user_email = $_data["user_email"];
  $user_pass = $_data["user_pass"];
  $user_account = mysqli_get_data("SELECT * FROM `account` WHERE `user_name` = '$user_name' AND `user_email` = '$user_email' AND `user_pass` = '$user_pass'");
}

// Jika belum masuk
if (!isset($user_account[0])) {
  header("Location: $data_url");
  exit();
}
  
// Set user data
if (!isset($_COOKIE["user_ip"]) || !isset($_COOKIE["user_cc"])) {
  setcookie("user_ip", $user_account[0]["user_ip"], time() + (60*60*24*7), "/");
  setcookie("user_cc", $user_account[0]["user_cc"], time() + (60*60*24*7), "/");
  header("Location: ./");
  exit();
}

// Get user data
$user_cc = $_COOKIE["user_cc"];
$account = $user_account[0];
// Upload gambar
if (isset($_FILES["profile"])) {
  $image = $_FILES["profile"];
  $validExs = ["jpg", "jpeg"];
  $fileUpload = [
    "status" => false,
    "massage" => ($user_cc === "ID" ? "Gagal mengunggah gambar!" : "Failed to upload image!"),
  ];
  if ($image["error"] === 0 && $image["name"] != "" && $image["tmp_name"] != "") {
    $arrayFileName = explode('.', $image["name"]);
    $actExs = strtolower(end($arrayFileName));
    if (in_array($actExs, $validExs)) {
      if ($image["size"] <= 1000000) {
        $file_data = [
          "image" => base64_encode(file_get_contents($image["tmp_name"])),
          "name" => uniqid().$actExs
        ];
        $data_result = upload_image($file_data, "0e5122db32bba1f171df7a922344cb0b");
        if ($data_result != false && $data_result["success"] && $data_result["status"] == 200) {
          mysqli_query($_conn, "UPDATE `account` SET `user_profile` = '".$data_result["data"]["thumb"]["url"]."' WHERE `user_id` = '".$user_account[0]["user_id"]."'");
          if (mysqli_affected_rows($_conn) > 0) {
            $fileUpload["status"] = true;
            $fileUpload["massage"] = ($user_cc === "ID" ? "Berhasil mengubah foto profil!" : "Successfully changed profile photo!");
          } else {
            $fileUpload["massage"] = ($user_cc === "ID" ? "Gagal mengubah foto profil!" : "Failed to change profile photo!");
          }
        } else {
          $fileUpload["massage"] = ($user_cc === "ID" ? "Terjadi kesalahan saat mengunggah gambar!" : "An error occurred while uploading the image!");
        }
      } else {
        $fileUpload["massage"] = ($user_cc === "ID" ? "Pilih gambar dengan maksimal ukuran 1MB!" : "Choose an image with a maximum size of 1MB!");
      }
    } else {
      $fileUpload["massage"] = ($user_cc === "ID" ? "Gunakan ekstensi gambar .jpg atau .jpeg!" : "Use the .jpg or .jpeg image extension!");
    }
  }
  $_SESSION["uploaded_image"] = $fileUpload;
}
?>
<!DOCTYPE html>
<html lang="<?= $user_cc === "ID" ? "id" : "en" ?>">
  <head>
    <meta name=”robots” content="noindex, nofollow" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= ($user_cc === "ID" ? "Profile Kamu" : "Your Profile") ?> - BeerdeMods</title>
    <link rel="shortcut icon" href="../assets/img/web__icon.png" />
    <!--Preconnect-->
    <link rel="preconnect" href="https://cdn.jsdelivr.net" />
    <link rel="preconnect" href="https://code.jquery.com" />
    <link rel="preconnect" href="https://site-assets.fontawesome.com" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
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
    <link rel="stylesheet" href="../assets/css/web__profile_style.css">
    <!--JQuery-->
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
    <!--JS-->
    <script
      src="../assets/js/web__funcs.js"
      __data_lang="<?= base64_encode($user_cc) ?>"
      __data_change_lang_id="<?= base64_encode("../__use/_web_lang.php") ?>"
      __data_signout_account="<?= base64_encode("../__use/_signout.php") ?>"
      __data_get_logined="<?= base64_encode("../__use/_logined.php") ?>"
      __data_del_profile="<?= base64_encode("../__use/_del_profile.php") ?>"
      __data_get_uploaded_image="<?= base64_encode("../__use/_get_uploaded_image.php") ?>"
      __data_change_profile="<?= base64_encode("../__use/_change_profile.php") ?>"
      __data_get_before_data="<?= base64_encode("../__use/_get_before_data.php") ?>"
      __data__ludo_matching="<?= base64_encode("../__use/_ludo_matching.php") ?>"
      __data_change_new="<?= base64_encode("../__use/_change_new.php") ?>"
    ></script>
    <script src="../assets/js/web__profile_sc.js"></script>
  </head>
  <body class="min-h-100 bg-custom-hex1C2021 text-light position-relative">
    <!--If the browser doesn't support JavaScript-->
    <noscript>
      <?= ($user_cc === "ID" ? "Browser kamu tidak mendukung JavaScript. :(" : "Your browser does not support JavaScript. :(") ?>
    </noscript>
    <!--end If the browser doesn't support JavaScript-->
    
    <!--NavBar-->
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
            <button type="button" class="btn btn-outline-signout btn-signout"><?= $user_cc === "ID" ? "Keluar" : "Sign Out" ?></button>
          </h5>
          <button type="button" class="btn border border-0 p-0 text-light fs-4" data-bs-dismiss="offcanvas" style="transform: translate(-8.5px, -5.5px);"><i class="fa-solid fa-xmark-large"></i></button>
        </div>
        <div class="offcanvas-body">
          <ul class="navbar-nav justify-content-end flex-grow-1 pe-3 font-ubuntu-500">
            <li class="nav-item py-3 ps-2">
              <a class="nav-link" href="../#about"><?= ($user_cc === "ID" ? "Tentang" : "About") ?></a>
            </li>
            <li class="nav-item py-3 ps-2">
              <a class="nav-link" href="../#faq">
                FAQ
              </a>
            </li>
            <li class="nav-item py-3 ps-2">
              <a class="nav-link" href="../#contact">
                <?= ($user_cc === "ID" ? "Kontak / Bantuan" : "Contact / Help") ?>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    
    <!--Profile-->
    <section id="change-profile">
      <div class="container pt-5rem pb-5">
        <div class="px-2">
          <div class="text-bg-dark rounded p-2">
            <div class="clearfix">
              <div class="col-md-4 float-md-end">
                <div class="px-2 py-2">
                  <form action="" method="POST" enctype="multipart/form-data" name="profile-change">
                    <div class="position-relative mb-3">
                      <div class="photo-profile ratio ratio-1x1 full-icon" style="background-image: url('<?= (isset($user_account[0]) && $user_account[0]["user_profile"] != "-" ? $user_account[0]["user_profile"] : "../assets/img/user_icon.png") ?>');"></div>
                      <div class="w-100 h-100 position-absolute top-50 start-50 translate-middle">
                        <div class="position-relative bg-secondary opacity-50 hover-opacity-100 rounded-circle w-100 h-100">
                          <span class="d-inline-block text-light position-absolute top-50 start-50 translate-middle text-center text-shadow">
                            <span class="d-block fs-custom">
                              <i class="box-icon fa-regular fa-box-open"></i>
                            </span>
                            <span class="d-block fs-text-custom mt-1 box-msg">
                              <?= ($user_cc === "ID" ? "Pilih file gambar atau seret ke sini." : "Choose an image file or drag it here.") ?>
                            </span>
                          </span>
                        </div>
                        <input type="file" accept="image/jpeg" name="profile" class="position-absolute top-50 start-50 translate-middle w-100 h-100 opacity-0 outline-none cursor-cell rounded-circle input-image" required />
                      </div>
                    </div>
                    <div class="btn-group w-100" role="group">
                      <button type="submit" class="btn btn-outline-submit btn-upload disabled">
                        <i class="fa-regular fa-cloud-arrow-up notransition"></i>
                        <?= ($user_cc === "ID" ? "Unggah" : "Upload") ?>
                      </button>
                      <button type="button" class="btn btn-outline-danger position-relative del-or-cancel">
                        <span class="del-icon notransition">
                          <i class="fa-regular fa-trash-can notransition"></i>
                        </span>
                        <span class="cancel-icon notransition position-absolute top-50 start-50 translate-middle opacity-0">
                          <i class="fa-regular fa-trash-can-xmark notransition"></i>
                        </span>
                        <span class="loading-icon notransition position-absolute top-50 start-50 translate-middle opacity-0">
                          <i class="fa-regular fa-spinner-third fa-spin notransition"></i>
                        </span>
                      </button>
                    </div>
                  </form>
                </div>
              </div>
              <div class="col-md-8">
                <div class="px-1 py-2 pt-md-0 pe-md-2">
                  <form id="profileInfo">
                    <div class="form-floating mb-2 pb-0">
                      <input type="text" class="form-control input-custom" id="inputName" placeholder="<?= ($user_cc === "ID" ? "Nama kamu" : "Your name") ?>" required minlength="3" value="<?= $account["name"] ?>">
                      <label for="inputName"><?= ($user_cc === "ID" ? "Nama" : "Name") ?></label>
                      <div class="position-relative w-100">
                        <div class="px-2 pb-2 w-100 position-absolute bottom-100">
                          <div class="w-100 h-1k2px bg-custom-hex00bcb4"></div>
                        </div>
                      </div>
                    </div>
                    <div class="form-floating mb-2 pb-0">
                      <input type="text" class="form-control input-custom" placeholder="Username" required readonly minlength="3" value="<?= $account["user_name"] ?>">
                      <label>Username</label>
                      <div class="position-relative w-100">
                        <div class="px-2 pb-2 w-100 position-absolute bottom-100">
                          <div class="w-100 h-1k2px bg-custom-hex00bcb4"></div>
                        </div>
                      </div>
                    </div>
                    <div class="form-floating mb-2 pb-0">
                      <input type="text" class="form-control input-custom" id="inputEmail" placeholder="Email" required minlength="5" value="<?= $account["user_email"] ?>">
                      <label for="inputEmail">Email</label>
                      <div class="position-relative w-100">
                        <div class="px-2 pb-2 w-100 position-absolute bottom-100">
                          <div class="w-100 h-1k2px bg-custom-hex00bcb4"></div>
                        </div>
                      </div>
                    </div>
                    <div class="form-floating mb-2 pb-0">
                      <input type="number" class="form-control input-custom" id="inputCall" placeholder="<?= ($user_cc === "ID" ? "Nomor telepon" : "Phone number") ?>" required minlength="7" maxlength="16" value="<?= $account["user_call"] ?>">
                      <label for="inputCall"><?= ($user_cc === "ID" ? "Nomor telepon" : "Phone number") ?></label>
                      <div class="position-relative w-100">
                        <div class="px-2 pb-2 w-100 position-absolute bottom-100">
                          <div class="w-100 h-1k2px bg-custom-hex00bcb4"></div>
                        </div>
                      </div>
                    </div>
                    <div class="p-2 clearfix">
                      <div class="d-inline-block float-end pe-1">
                        <button type="submit" class="btn btn-outline-save-change position-relative">
                          <span class="submit-text notransition">
                            <?= ($user_cc === "ID" ? "Simpan" : "Save") ?>
                          </span>
                          <span class="submit-load notransition position-absolute top-50 start-50 translate-middle opacity-0">
                            <i class="fa-regular fa-spinner-third fa-spin notransition"></i>
                          </span>
                        </button>
                      </div>
                      <div class="d-inline-block ps-1">
                        <button
                          type="button"
                          class="btn p-0 border border-0 fs-4 text-light"
                          data-bs-toggle="tooltip"
                          data-bs-placement="right"
                          data-bs-html="true"
                          data-bs-title="<?= ($user_cc === "ID" ? "Pengaturan" : "Settings") ?>"
                          data-bs-custom-class="profile-settings"
                        >
                          <i class="fa-regular fa-gear"></i>
                        </button>
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
                    <a href="../#about" class="text-reset text-decoration-none"><?= ($user_cc === "ID" ? "Tentang" : "About") ?></a>
                  </span>
                  <span class="mb-2 d-block">
                    <a href="../#contact" class="text-reset text-decoration-none"><?= ($user_cc === "ID" ? "Kontak / Bantuan" : "Contact / Help") ?></a>
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
    
    <!--Form edit kata sandi-->
    <div class="position-fixed top-0 start-0 end-0 bottom-0 bg-custom-blur-10px notransition" style="display: none;" id="changePassword">
      <div class="position-absolute top-50 start-50 translate-middle p-2 bg-dark shadow-sm rounded width-auto">
        <form id="formpw" name="form-pw">
          <div class="form-floating mb-2 pb-0">
            <input type="password" name="old-pass" class="form-control input-custom" id="oldPassword" placeholder="<?= ($user_cc === "ID" ? "Kata Sandi Lama" : "Old Password") ?>" required minlength="8" autocomplete="off">
            <label for="oldPassword"><?= ($user_cc === "ID" ? "Kata Sandi Lama" : "Old Password") ?></label>
            <div class="position-relative w-100">
              <div class="px-2 pb-2 w-100 position-absolute bottom-100">
                <div class="w-100 h-1k2px bg-custom-hex00bcb4"></div>
              </div>
            </div>
          </div>
          <div class="form-floating mb-2 pb-0">
            <input type="password" name="new-pass" class="form-control input-custom" id="newPassword" placeholder="<?= ($user_cc === "ID" ? "Kata Sandi Baru" : "New Password") ?>" required minlength="8" autocomplete="off">
            <label for="newPassword"><?= ($user_cc === "ID" ? "Kata Sandi Baru" : "New Password") ?></label>
            <div class="position-relative w-100">
              <div class="px-2 pb-2 w-100 position-absolute bottom-100">
                <div class="w-100 h-1k2px bg-custom-hex00bcb4"></div>
              </div>
            </div>
          </div>
          <div class="form-floating mb-2 pb-0">
            <input type="password" name="confirm-new-pass" class="form-control input-custom" id="cnewPassword" placeholder="<?= ($user_cc === "ID" ? "Konfirmasi Kata Sandi Baru" : "Confirm New Password") ?>" required minlength="8" autocomplete="off">
            <label for="cnewPassword"><?= ($user_cc === "ID" ? "Konfirmasi Kata Sandi Baru" : "Confirm New Password") ?></label>
            <div class="position-relative w-100">
              <div class="px-2 pb-2 w-100 position-absolute bottom-100">
                <div class="w-100 h-1k2px bg-custom-hex00bcb4"></div>
              </div>
            </div>
          </div>
          <div class="clearfix">
            <div class="d-inline-block pe-2 pb-1 float-end">
              <button type="submit" class="btn btn-outline-save-change position-relative">
                <span class="save-pw-text notransition">
                  <?= ($user_cc === "ID" ? "Simpan" : "Save") ?>
                </span>
                <span class="save-pw-load notransition position-absolute top-50 start-50 translate-middle opacity-0">
                  <i class="fa-regular fa-spinner-third fa-spin notransition"></i>
                </span>
              </button>
            </div>
          </div>
        </form>
        <div class="d-inline-block shadow-sm wh-30px fsc-15px position-absolute top-0 start-100 translate-middle bg-dark rounded-circle text-light" hide="changePassword">
          <span class="position-absolute top-50 start-50 translate-middle">
            <i class="fa-solid fa-xmark-large"></i>
          </span>
        </div>
      </div>
    </div>
    
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