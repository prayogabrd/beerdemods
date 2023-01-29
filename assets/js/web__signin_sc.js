$(document).ready(() => {
  // Kumpulan data
  const ressta = atob($('[__data_result_status]').attr('__data_result_status'));
  const resmas = atob($('[__data_result_massage]').attr('__data_result_massage'));
  
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
  
  // Ketika Tombol daftar di pencet
  $('.btn-signup').on('click', () => {
    document.location.href = '../sign_up';
  });
  
  // Jika error
  if (ressta == 'false') {
    setTimeout(() => {
      Toast.fire({
        icon: 'error',
        title: resmas
      });
    }, 500);
  }
  
  // Fix Footer
  $('#fixFooter').css('height', $('#myFooter').css('height'));
  $('.ph').css('height', $('.ch').css('height'));
  
  // hide loader
  $('#loader').fadeOut();
});