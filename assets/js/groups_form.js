jQuery(document).ready(function ($) {
  $("#group-form .submit").on("click", function (e) {
    e.preventDefault();

    const formData = collectData("#group-form .et-input");

    $.ajax({
      url: "/wp-json/expense-tracker/v1/groups",
      method: "POST",
      beforeSend: function (xhr) {
        xhr.setRequestHeader("X-WP-Nonce", wpApiSettings.nonce);
      },
      data: JSON.stringify(formData),
      contentType: "application/json",
      success: function (response) {
        console.log(response);
        window.location.href = "?page=expense-tracker-groups";
      },
      error: function (xhr) {
        labelErrors("#group-form .et-input", xhr.responseJSON.errors);
      },
    });
  });
});
