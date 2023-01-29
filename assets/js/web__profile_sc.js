$(document).ready(() => {
  // Kumpulkan data
  const _signout = atob($('[__data_signout_account]').attr('__data_signout_account'));
  const _get_logined = atob($('[__data_get_logined]').attr('__data_get_logined'));
  const _del_profile = atob($('[__data_del_profile]').attr('__data_del_profile'));
  const _get_uploaded_image = atob($('[__data_get_uploaded_image]').attr('__data_get_uploaded_image'));
  const _change_profile = atob($('[__data_change_profile]').attr('__data_change_profile'));
  const _get_before_data = atob($('[__data_get_before_data]').attr('__data_get_before_data'));
  const _run_matching = atob($('[__data__ludo_matching]').attr('__data__ludo_matching'));
  const _change_new = atob($('[__data_change_new]').attr('__data_change_new'));
  
  // Gunakan Bootstrap tooltips
  $('[data-bs-toggle="tooltip"]').tooltip();
  
  // Config SweetAlert2
  const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    customClass: {
      container: 'z-2010'
    },
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: toast => {
      $(toast).on('mouseenter', Swal.stopTimer);
      $(toast).on('mouseleave', Swal.resumeTimer);
    }
  });
  
  // Dari pada nulis 2 kali
  let biarSimpel = () => {
    let form = document.forms['profile-change'];
    form.reset();
    $('.box-icon').removeClass('fa-box');
    $('.box-icon').removeClass('fa-box-circle-check');
    $('.box-icon').addClass('fa-box-open');
    $('.box-msg').html((_lang === 'ID' ? 'Pilih file gambar atau seret ke sini.' : 'Choose an image file or drag it here.'));
    $('.btn-upload').addClass('disabled');
    $('.del-icon').removeClass('opacity-0');
    $('.cancel-icon').addClass('opacity-0');
  };
  
  // Change profile
  let changeProfile = data => {
    if (atob(data.user_call).length < 7) {
      $('.submit-text').removeClass('opacity-0');
      $('.submit-load').addClass('opacity-0');
      Toast.fire({
        icon: 'error',
        title: (_lang === 'ID' ? 'Nomor telepon terlalu pendek!' : 'The phone number is too short!')
      });
    } else if (atob(data.user_call).length > 16) {
      $('.submit-text').removeClass('opacity-0');
      $('.submit-load').addClass('opacity-0');
      Toast.fire({
        icon: 'error',
        title: (_lang === 'ID' ? 'Nomor telepon terlalu panjang!' : 'The phone number is too long!')
      });
    } else {
      $('.submit-text').addClass('opacity-0');
      $('.submit-load').removeClass('opacity-0');
      fetch(_change_profile, {method: 'POST', body: JSON.stringify(data)})
        .then(result => result.json())
        .then(resdat => {
          $('.submit-text').removeClass('opacity-0');
          $('.submit-load').addClass('opacity-0');
          Toast.fire({
            icon: (resdat.status ? 'success' : 'error'),
            title: resdat.massage
          });
        })
        .catch(() => {
          $('.submit-text').removeClass('opacity-0');
          $('.submit-load').addClass('opacity-0');
          Toast.fire({
            icon: 'error',
            title: (_lang == 'ID' ? 'Terjadi kesalahan!' : 'There is an error!')
          });
        });
    }
  };
  
  // Bootstrap custom tooltip
  $('[data-bs-custom-class="tooltip-custom"]').on('click', () => {
    setTimeout(() => {
      $('.tooltip-custom').html(
        '<div class="p-2 d-inline-block bg-custom-hex1C2021 rounded shadow-sm">' +
          '<span class="d-block mb-1">' +
            '<a onclick="changeLang(\'id\');" class="btn border border-0 p-0 font-poppins fw-bold ' + (_lang == 'ID' ? 'text-hex00bcb4' : 'text-light') + '">' +
              'Indonesia (ID)' +
            '</a>' +
          '</span>' +
          '<span class="d-block">' +
            '<a onclick="changeLang(\'en\');" class="btn border border-0 p-0 font-poppins fw-bold ' + (_lang == 'ID' ? 'text-light' : 'text-hex00bcb4') + '">' +
              (_lang == 'ID' ? 'Inggris' : 'English') + ' (EN)' +
            '</a>' +
          '</span>' +
        '</div>'
      );
    }, 10);
  });
  
  // Ketika tombol keluar di tekan
  $('.btn-signout').on('click', () => {
    Swal.fire({
      icon: 'question',
      title: (_lang === 'ID' ? 'Apakah anda yakin?' : 'Are you sure?'),
      text: (_lang === 'ID' ? 'Anda akan keluar dari akun anda.' : 'You will be logged out of your account.'),
      confirmButtonText: (_lang === 'ID' ? 'Ya, Keluar' : 'Yes, Sign Out'),
      showCancelButton: true,
      cancelButtonText: (_lang === 'ID' ? 'Batal' : 'Cancel'),
      confirmButtonColor: $('.btn-outline-danger').css('--bs-btn-color')
    }).then(where => {
      if (where.isConfirmed) {
        fetch(_signout)
          .then(result => result.json())
          .then(() => {
            Toast.fire({
              icon: 'success',
              title: (_lang == 'ID' ? 'Berhasil keluar!' : 'Made it out!'),
              willClose: () => {
                document.location.href = '../';
              }
            });
          })
          .catch(() => {
            Toast.fire({
              icon: 'error',
              title: (_lang == 'ID' ? 'Terjadi kesalahan!' : 'There is an error!')
            });
          });
      }
    });
  });
  
  // Ketika input file di hover
  $('[name="profile"]').on('mouseenter', () => {
    $('.hover-opacity-100').removeClass('opacity-50');
    $('.hover-opacity-100').addClass('opacity-75');
  });
  $('[name="profile"]').on('mouseleave', () => {
    $('.hover-opacity-100').removeClass('opacity-75');
    $('.hover-opacity-100').addClass('opacity-50');
  });
  
  // Ketika ada gambar yg di pilih atau di hapus
  $('[name="profile"]').on('change', function() {
    if (typeof this.files[0] == 'undefined') {
      biarSimpel();
    } else {
      $('.box-icon').removeClass('fa-box-open');
      $('.box-icon').removeClass('fa-box-circle-check');
      $('.box-icon').addClass('fa-box');
      $('.box-msg').html((_lang === 'ID' ? 'File dipilih!' : 'Files selected!'));
      $('.btn-upload').removeClass('disabled');
      $('.del-icon').addClass('opacity-0');
      $('.cancel-icon').removeClass('opacity-0');
    }
  });
  
  // Hapus atau batalkan tindakan mengganti foto profil
  $('.del-or-cancel').on('click', () => {
    if (typeof $('[name="profile"]')[0].files[0] == 'undefined') {
      Swal.fire({
        icon: 'question',
        title: (_lang === 'ID' ? 'Apakah anda yakin?' : 'Are you sure?'),
        text: (_lang === 'ID' ? 'Tindakan ini akan menghapus foto profil anda.' : 'This action will delete your profile photo.'),
        confirmButtonText: (_lang === 'ID' ? 'Ya, Hapus profil saya' : 'Yes, Delete my profile'),
        showCancelButton: true,
        cancelButtonText: (_lang === 'ID' ? 'Batal' : 'Cancel'),
        confirmButtonColor: $('.btn-outline-danger').css('--bs-btn-color')
      }).then(where => {
        if (where.isConfirmed) {
          $('.del-icon').addClass('opacity-0');
          $('.loading-icon').removeClass('opacity-0');
          fetch(_del_profile)
            .then(result => result.json())
            .then(del => {
              $('.loading-icon').addClass('opacity-0');
              $('.del-icon').removeClass('opacity-0');
              if (del.status === 0) {
                Toast.fire({
                  icon: 'question',
                  iconColor: '#0000',
                  iconHtml: '<i class="fa-regular fa-face-thinking opacity-100 text-warning"></i>',
                  title: (_lang === 'ID' ? 'Apanya yang mau dihapus?' : 'What do you want to delete?')
                });
              } else {
                Toast.fire({
                  icon: (del.status === 1 ? 'success' : 'error'),
                  title: (del.status === 1 ?
                     (_lang === 'ID' ? 'Berhasil menghapus profil!' : 'Successfully deleted profile!') :
                      (_lang === 'ID' ? 'Gagal menghapus profil!' : 'Failed to delete profile!')
                    )
                });
                if (del.status === 1) {
                  $('.photo-profile').css('background-image', 'url(\'../assets/img/user_icon.png\')');
                }
              }
            })
            .catch(() => {
              $('.loading-icon').addClass('opacity-0');
              $('.del-icon').removeClass('opacity-0');
              Toast.fire({
                icon: 'error',
                title: (_lang === 'ID' ? 'Gagal menghapus profil!' : 'Failed to delete profile!')
              });
            });
        }
      });
    } else {
      biarSimpel();
    }
  });
  
  // Cek apakah berhasil mengubah gambar
  fetch(_get_uploaded_image)
    .then(result => result.json())
    .then(resdat => {
      if (resdat.success) {
        $('.box-icon').removeClass('fa-box-open');
        $('.box-icon').removeClass('fa-box');
        if (resdat.data.status) {
          $('.box-icon').addClass('fa-box-circle-check');
          $('.box-msg').html((_lang === 'ID' ? 'Berhasil di ubah!' : 'Successfully changed!'));
        } else {
          $('.box-icon').addClass('fa-triangle-exclamation');
          $('.box-msg').html((_lang === 'ID' ? 'Gagal di ubah!' : 'Failed to change!'));
        }
        Toast.fire({
          icon: (resdat.data.status ? 'success' : 'error'),
          title: resdat.data.massage
        });
      }
    });
  
  // Ketika form edit profil di submit
  $('#profileInfo').on('submit', e => {
    e.preventDefault();
    $('.submit-text').addClass('opacity-0');
    $('.submit-load').removeClass('opacity-0');
    let tdata = {
      lang: btoa(_lang),
      name: btoa($('#inputName').val()),
      user_email: btoa($('#inputEmail').val()),
      user_call: btoa($('#inputCall').val())
    };
    fetch(_get_before_data)
      .then(resbef => resbef.json())
      .then(res => {
        if (res.user_email == atob(tdata.user_email)) {
          if (res.user_call != atob(tdata.user_call) || res.name != atob(tdata.name)) {
            $('.submit-text').removeClass('opacity-0');
            $('.submit-load').addClass('opacity-0');
            changeProfile({
              lang: tdata.lang,
              name: tdata.name,
              user_call: tdata.user_call
            });
          } else {
            $('.submit-text').removeClass('opacity-0');
            $('.submit-load').addClass('opacity-0');
            Toast.fire({
              icon: 'question',
              iconColor: '#0000',
              iconHtml: '<i class="fa-regular fa-face-thinking opacity-100 text-warning"></i>',
              title: (_lang === 'ID' ? 'Apanya yang mau diubah?' : 'Apanya yang mau diubah?')
            });
          }
        } else {
          $('.submit-text').removeClass('opacity-0');
          $('.submit-load').addClass('opacity-0');
          Swal.fire({
            title:
              '<h4 class="fs-4 text-hex00bcb4">' +
                (_lang === 'ID' ? 'Masukan Kata Sandi!' : 'Enter Password!') +
              '</h4>',
            html:
              '<div class="px-1">' +
                '<div class="form-floating mb-2 pb-0">' +
                  '<input type="password" class="form-control input-custom" id="confirmAction" placeholder="' + (_lang === 'ID' ? 'Kata Sandi' : 'Password') + '" minlength="3" autocomplete="off">' +
                  '<label for="confirmAction">' +
                    (_lang === 'ID' ? 'Kata Sandi' : 'Password') +
                  '</label>' +
                  '<div class="position-relative w-100">' +
                    '<div class="px-2 pb-2 w-100 position-absolute bottom-100">' +
                      '<div class="w-100 h-1k2px bg-custom-hex00bcb4"></div>' +
                    '</div>' +
                  '</div>' +
                '</div>' +
              '</div>',
            confirmButtonText: (_lang === 'ID' ? 'Simpan' : 'Save'),
            showCancelButton: true,
            cancelButtonText: (_lang === 'ID' ? 'Batal' : 'Cancel'),
            confirmButtonColor: '#00bcb4',
            showLoaderOnConfirm: true,
            preConfirm: () => {
              let pass = $('#confirmAction').val();
              return fetch(_run_matching, {
                  method: 'POST',
                  body: JSON.stringify({user_pass: btoa(pass)})
                })
                .then(response => {
                  if (!response.ok) {
                    throw new Error(response.statusText);
                  }
                  return response.json();
                })
                .catch(() => {
                  Swal.showValidationMessage(
                    (_lang === 'ID' ? 'Terjadi kesalahan!' : 'There is an error!')
                  );
                });
            },
            allowOutsideClick: () => !Swal.isLoading()
          }).then(result => {
            if (result.isConfirmed) {
              if (result.value.status) {
                changeProfile({
                  lang: tdata.lang,
                  name: tdata.name,
                  user_email: tdata.user_email,
                  user_call: tdata.user_call
                });
              } else {
                $('.submit-text').removeClass('opacity-0');
                $('.submit-load').addClass('opacity-0');
                Toast.fire({
                  icon: 'error',
                  title: (_lang == 'ID' ? 'Kata sandi salah!' : 'Wrong password!')
                });
              }
            }
          });
        }
      })
      .catch(() => {
        $('.submit-text').removeClass('opacity-0');
        $('.submit-load').addClass('opacity-0');
        Toast.fire({
          icon: 'error',
          title: (_lang == 'ID' ? 'Terjadi kesalahan!' : 'There is an error!')
        });
      });
  });
  
  // Mungculkan pengaturan
  $('[data-bs-custom-class="profile-settings"]').on('click', () => {
    setTimeout(() => {
      $('.profile-settings').html(
        '<div class="p-2 d-inline-block bg-custom-hex1C2021 rounded shadow-sm">' +
          '<span class="d-block">' +
            '<a class="btn border border-0 p-0 font-poppins fw-bold text-light" show="changePassword">' +
              (_lang === 'ID' ? 'Ubah Kata Sandi' : 'Change Password') +
            '</a>' +
          '</span>' +
        '</div>'
      );
      $('[show="changePassword"]').on('click', e => {
        e.preventDefault();
        $('#changePassword').fadeIn();
      });
    }, 10);
  });
  
  // Ketika form ganti kata sandi di submit
  $('#formpw').on('submit', e => {
    e.preventDefault();
    let datapw = {
      old_pass: $('#oldPassword').val(),
      new_pass: $('#newPassword').val(),
      conf_new_pass: $('#cnewPassword').val()
    };
    if (datapw.new_pass.length >= 8) {
      if (datapw.new_pass === datapw.conf_new_pass) {
        $('.save-pw-text').addClass('opacity-0');
        $('.save-pw-load').removeClass('opacity-0');
        fetch(_change_new, {
          method: 'POST',
          body: JSON.stringify({
            lang: btoa(_lang),
            old_pass: btoa(datapw.old_pass),
            new_pass: btoa(datapw.new_pass),
            conf_new_pass: btoa(datapw.conf_new_pass)
          })
        })
        .then(response => response.json())
        .then(result => {
          $('.save-pw-text').removeClass('opacity-0');
          $('.save-pw-load').addClass('opacity-0');
          Toast.fire({
            icon: (result.status ? 'success' : 'error'),
            title: result.massage
          });
          if (result.status) {
            const formPw = document.forms['form-pw'];
            formPw.reset();
          }
        })
        .catch((err) => {
          console.log(err);
          Toast.fire({
            icon: 'error',
            title: (_lang == 'ID' ? 'Terjadi kesalahan!' : 'There is an error!')
          });
        });
      } else {
        Toast.fire({
          icon: 'error',
          title: (_lang == 'ID' ? 'Konfirmasi kata sandi baru salah!' : 'Confirm new password wrong!')
        });
      }
    } else {
      Toast.fire({
        icon: 'info',
        title: (_lang == 'ID' ? 'Kata sandi terlalu pendek!' : 'Password is too short!')
      });
    }
  });
  
  // Hide form edit password
  $('[hide="changePassword"]').on('click', e => {
    e.preventDefault();
    $('#changePassword').fadeOut();
  });
  
  // Fix Footer
  $('#fixFooter').css('height', $('#myFooter').css('height'));
  $('.ph').css('height', $('.ch').css('height'));
  
  // hide loader
  $('#loader').fadeOut();
});