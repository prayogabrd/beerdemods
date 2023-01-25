$(document).ready(() => {
  // Kumpulkan data
  const _signout = atob($('[__data_signout_account]').attr('__data_signout_account'));
  const _get_logined = atob($('[__data_get_logined]').attr('__data_get_logined'));
  const _del_profile = atob($('[__data_del_profile]').attr('__data_del_profile'));
  const _get_uploaded_image = atob($('[__data_get_uploaded_image]').attr('__data_get_uploaded_image'));
  
  // Gunakan Bootstrap tooltips
  $('[data-bs-toggle="tooltip"]').tooltip();
  
  // Config SweetAlert2
  const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
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
      $('.box-msg').html(
        (_lang === 'ID' ? 'Gambar yang dipilih: ' : 'Selected image: ') +
        '<b>' + this.files[0].name + '</b>'
      );
      $('.btn-upload').removeClass('disabled');
      $('.del-icon').addClass('opacity-0');
      $('.cancel-icon').removeClass('opacity-0');
    }
  });
  
  // Hapus atau batalkan tindakan mengganti foto profil
  $('.del-or-cancel').on('click', function() {
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
      biarSimpel($('[name="profile"]')[0]);
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
  
  // Fix Footer
  $('#fixFooter').css('height', $('#myFooter').css('height'));
  $('.ph').css('height', $('.ch').css('height'));
});