document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.btn-back-home').forEach(function (el) { el.setAttribute('href', window.wpThemeHome || '/'); });
});
