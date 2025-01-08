jQuery(document).ready(function ($) {
  // Make these utility functions global
  window.labelErrors = function (selector, e) {
    console.log(e);
    $(selector).each(function () {
      if (e[$(this).attr("name")]) {
        $(this).addClass("is-invalid");
        $(this).next("small.text-danger").text(e[$(this).attr("name")]);
      } else {
        $(this).removeClass("is-invalid");
        $(this).next("small.text-danger").text();
      }
    });
  };

  window.collectData = function (selector) {
    let data = {};
    $(selector).each(function () {
      data[$(this).attr("name")] = $(this).val();
    });
    return data;
  };

  window.loadData = function (selector, data) {
    $(selector).each(function () {
      $(this).val(data[$(this).attr("name")]);
    });
  };
});
