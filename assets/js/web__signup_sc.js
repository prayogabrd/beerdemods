$(document).ready(() => {
  // Mengumpulan data
  const _ip_user = atob($('[__data_user_ip]').attr('__data_user_ip'));
  const _previous_url = atob($('[__data_previous_url]').attr('__data_previous_url'));
  
  // Config Toast
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
  
  // Use the Bootstrap tooltips
  new bootstrap.Tooltip($('[data-bs-toggle="tooltip"]'));
  
  // Bootstrap custom tooltip
  $('[data-bs-custom-class="tooltip-custom"').on('click', () => {
    setTimeout(() => {
      $('.tooltip-custom').html(
        '<div class="p-2 d-inline-block bg-dark rounded shadow-sm">' +
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
  
  // Ketika Tombol masuk di pencet
  $('.btn-signin').on('click', () => {
    document.location.href = '../sign_in';
  });
  
  // Ketika form di submit
  $('#form-signup').on('submit', e => {
    e.preventDefault();
    // Data yang akan dikirimkan
    let user = {
      '__utilization_genre': 'initiate_a_request',
      'user_ip': _ip_user,
      'user_cc': _lang,
      'name': $('#name').val(),
      'user_name': $('#username').val().toLowerCase(),
      'user_email': $('#email').val().toLowerCase(),
      'user_pass': $('#password').val(),
      'conf_pass': $('#passwordConfirm').val(),
      'i_agree': $('#checkConfirm').prop('checked')
    };
    
    // Tampilkan loading
    $('.btn-submit-text').addClass('opacity-0');
    $('.btn-submit-loading').removeClass('opacity-0');
    
    // Kirimkan data
    fetch('_signup.php', {method: 'POST', body: JSON.stringify(user)})
      .then(result => result.json())
      .then(signup => {
        $('.btn-submit-loading').addClass('opacity-0');
        $('.btn-submit-text').removeClass('opacity-0');
        if (signup.status) {
          $('.form-control').val('');
          Toast.fire({
            icon: 'success',
            title: signup.massage,
            willClose: () => {
              document.location.href = "../sign_in";
            }
          });
        } else {
          Toast.fire({
            icon: 'error',
            title: signup.massage
          });
        }
      }).catch((err) => {
        $('.btn-submit-loading').addClass('opacity-0');
        $('.btn-submit-text').removeClass('opacity-0');
        Toast.fire({
          icon: 'error',
          title: (_lang === 'ID' ? 'Pendaftar gagal!' : 'Signup failed!')
        });
      });
  });
  
  // Fix Footer
  $('#fixFooter').css('height', $('#myFooter').css('height'));
  $('.ph').css('height', $('.ch').css('height'));
  
  // hide loader
  $('#loader').fadeOut();
});