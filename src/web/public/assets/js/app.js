"use strict";
$(function () {
  let sidebar = $(".sidebar");
  $("#sidebarToggle, #sidebarToggleTop").on('click', function () {
    $("body").toggleClass("sidebar-toggled");
    sidebar.toggleClass("toggled");
    if (sidebar.hasClass("toggled")) {
      $('.sidebar .collapse').collapse('hide');
    }
  });

  bsCustomFileInput.init();
  let is = $("input[type='number']");
  if (is.length) {
    is.inputSpinner();
  }
  let sw = $('#smartWizard');
  if (sw.length) {
    sw.smartWizard();
  }
  let track = $('.selectpicker');
  if (track.length) {
    $('.selectpicker').selectpicker();
  }
});