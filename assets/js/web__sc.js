$(document).ready(() => {
  // Collecting data
  const hIM = $('#inputMassage').height();
  const saveHeight = $('.save-height').css('height');
  const formElement = document.forms['form-contact'];
  const _s_id = atob($('[__data_s_id]').attr('__data_s_id'));
  const _get_s_rsim = atob($('[__data_get_s_rsim]').attr('__data_get_s_rsim'));
  const _rm_s_rsim = atob($('[__data_rm_s_rsim]').attr('__data_rm_s_rsim'));
  const _signout = atob($('[__data_signout_account]').attr('__data_signout_account'));
  const _get_logined = atob($('[__data_get_logined]').attr('__data_get_logined'));
  const _data_lang = {
    __id: ['Selamat datang di BeerdeMods!'],
    __en: ['Welcome to BeerdeMods!']
  };
  
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
  
  // Function to display message input
  let showForm = (def_name = '', def_contact = '', def_massage = '') => {
    $('.save-height').removeClass('keep-height');
    $('[name="form-contact"]').show();
    $('#inputName').val(def_name);
    $('#inputEmail').val(def_contact);
    $('#inputMassage').val(def_massage);
  };
  
  // Use the Bootstrap tooltips
  $('[data-bs-toggle="tooltip"]').tooltip();
  
  // Beritahu jika proses masuk berhasil atau gagal
  fetch(_get_s_rsim)
    .then(result => result.json())
    .then(msg => {
      if (msg.rsim != '') {
        Toast.fire({
          icon: 'success',
          title: msg.rsim
        });
        fetch(_rm_s_rsim);
      }
    })
    .catch(() => {
      Toast.fire({
        icon: 'error',
        title: (_lang == 'ID' ? 'Terjadi kesalahan!' : 'There is an error!')
      });
    });
  
  // Animation of typing on the hero section
  new TypeIt('.typing', {
    strings: (_lang == 'ID' ? _data_lang.__id : _data_lang.__en),
    breakLines: false,
    lifeLike: true,
    speed: 100,
    startDelay: 500,
    loop: true,
    cursorChar: ''
  }).go();
  
  // When scrolled
  $(window).on('scroll', () => {
    // Actions on the navbar
    if (document.documentElement.scrollTop > 10) {
      $('.navbar-expand').addClass('bg-custom-hex313131');
      $('.navbar-expand').addClass('shadow-sm');
    } else {
      $('.navbar-expand').removeClass('bg-custom-hex313131');
      $('.navbar-expand').removeClass('shadow-sm');
    }
    
    // Actions on swipe note
    if (document.documentElement.scrollTop > 470.5333251953125) {
      $('.swipe-down').addClass('opacity-0');
    } else {
      $('.swipe-down').removeClass('opacity-0');
    }
  });
  
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
  $('[data-bs-custom-class="account-manager"]').on('click', () => {
    setTimeout(() => {
      $('.account-manager').html(
        '<div class="p-2 d-inline-block bg-custom-hex1C2021 rounded shadow-sm">' +
          '<span class="d-block">' +
            '<a class="btn border border-0 p-0 fw-bold text-light">' +
              '<i class="fa-duotone fa-spinner-third fa-spin fa-2x" style="--fa-primary-color: #00bcb4;"></i>' +
            '</a>' +
          '</span>' +
        '</div>'
      );
      fetch(_get_logined)
        .then(result => result.json())
        .then(logined => {
          if (logined.status) {
            $('.account-manager').html(
              '<div class="p-2 d-inline-block bg-custom-hex1C2021 rounded shadow-sm">' +
                '<span class="d-block mb-1">' +
                  '<a class="btn border border-0 p-0 font-poppins fw-bold text-light" href="./profile">' +
                    (_lang == 'ID' ? 'Pengaturan' : 'Settings') +
                  '</a>' +
                '</span>' +
                '<span class="d-block">' +
                  '<a onclick="$(\'.btn-signout\').click();" class="btn border border-0 p-0 font-poppins fw-bold text-light">' +
                    (_lang == 'ID' ? 'Keluar' : 'Sign Out') +
                  '</a>' +
                '</span>' +
              '</div>'
            );
          } else {
            $('.account-manager').html(
              '<div class="p-2 d-inline-block bg-custom-hex1C2021 rounded shadow-sm">' +
                '<span class="d-block mb-1">' +
                  '<a href="./sign_up" class="btn border border-0 p-0 font-poppins fw-bold text-light">' +
                    (_lang == 'ID' ? 'Daftar' : 'Sign Up') +
                  '</a>' +
                '</span>' +
                '<span class="d-block">' +
                  '<a href="./sign_in" class="btn border border-0 p-0 font-poppins fw-bold text-light">' +
                    (_lang == 'ID' ? 'Masuk' : 'Sign In') +
                  '</a>' +
                '</span>' +
              '</div>'
            );
          }
        })
        .catch(() => {
          $('.account-manager').html(
            '<div class="p-2 d-inline-block bg-custom-hex1C2021 rounded shadow-sm">' +
              '<span class="d-block">' +
                '<a class="btn border border-0 p-0 text-light">' +
                  '<i class="fa-regular fa-face-weary fa-beat" style="--fa-beat-scale: 1.1; --fa-animation-duration: 1.5s;"></i>' +
                '</a>' +
              '</span>' +
            '</div>'
          );
        });
    }, 10);
  });
  
  // Ketika tombol masuk ditekan
  $('.btn-signin').on('click', () => {
    document.location.href = '../sign_in';
  });
  
  // Ketika tombol daftar ditekan
  $('.btn-signup').on('click', () => {
    document.location.href = '../sign_up';
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
      confirmButtonColor: '#dc3545'
    }).then(where => {
      if (where.isConfirmed) {
        fetch(_signout)
          .then(result => result.json())
          .then(() => {
            Toast.fire({
              icon: 'success',
              title: (_lang == 'ID' ? 'Berhasil keluar!' : 'Made it out!'),
              willClose: () => {
                document.location.href = '';
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
  
  // When an action occurs on the message input
  $('#inputMassage').on('focus', () => {
    if ($('#inputMassage').height() <= hIM) {
      $('#inputMassage').css('height', '120px');
    }
  });
  $('#inputMassage').on('blur', () => {
    if ($('#inputMassage').val().length < 2 && $('#inputMassage').height() >= hIM) {
      $('#inputMassage').height(hIM);
    }
  });
  $('[name="form-contact"]').on('submit', (e) => {
    e.preventDefault();
    $('.save-height').addClass('keep-height');
    $('[name="form-contact"]').blur();
    let is_name = $('#inputName').val(),
      is_contact = $('#inputEmail').val(),
      is_massage = $('#inputMassage').val();
    $('.keep-height').css('height', saveHeight);
    $('[name="form-contact"]').hide();
    $('.loading-result').removeClass('d-none');
    $('.loading-result').addClass('d-inline-block');
    fetch(_s_id, {
      method: 'POST',
      body: new FormData(formElement)
    }).then(() => {
      $('#inputMassage').height(hIM);
      formElement.reset();
      $('.loading-result').removeClass('d-inline-block');
      $('.loading-result').addClass('d-none');
      $('.result-success').removeClass('d-none');
      $('.result-success').addClass('d-inline-block');
      $('#btnSuccess').on('click', () => {
        $('.result-success').removeClass('d-inline-block');
        $('.result-success').addClass('d-none');
        showForm();
      });
    }).catch(() => {
      $('.loading-result').removeClass('d-inline-block');
      $('.loading-result').addClass('d-none');
      $('.result-error').removeClass('d-none');
      $('.result-erroe').addClass('d-inline-block');
      $('#btnTryAgain').on('click', () => {
        $('.result-error').removeClass('d-inline-block');
        $('.result-error').addClass('d-none');
        showForm(is_name, is_contact, is_massage);
        if ($('#inputMassage').val().length < 2 && $('#inputMassage').height() <= hIM) {
          $('#inputMassage').css('height', '120px');
        }
      });
    });
  });
  
  // Footer
  $('.ph').css('height', $('.ch').css('height'));
  
  // hide loader
  $('#loader').fadeOut();
  
  // Inisialisasi AOS
  setTimeout(() => {
    AOS.init();
  }, 250);
});