jQuery(document).ready(function ($) {
  $("#group-form .submit").on("click", function (e) {
    e.preventDefault();

    const formData = collectData("#group-form .et-input");
    const group_id = $("#group-form input[name='group_id']").val();

    $.ajax({
      url:
        "/wp-json/expense-tracker/v1/groups" + (group_id ? "/" + group_id : ""),
      method: group_id ? "PUT" : "POST",
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
