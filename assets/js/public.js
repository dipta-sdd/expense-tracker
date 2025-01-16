jQuery(document).ready(function ($) {
  // Modal functionality
  const modal = $("#et-create-group-modal");
  const openModalBtn = $(".et-create-group-btn"); // Updated selector
  const closeModalBtn = $(".et-modal-close");
  const cancelBtn = $(".et-modal-cancel");

  // Open modal
  openModalBtn.on("click", function () {
    modal.addClass("show");
  });

  // Close modal functions
  function closeModal() {
    modal.removeClass("show");
  }

  // Close modal when clicking close button
  closeModalBtn.on("click", closeModal);
  cancelBtn.on("click", closeModal);

  // Close modal when clicking outside
  $(window).on("click", function (event) {
    if ($(event.target).is(modal)) {
      closeModal();
    }
  });

  // Handle form submission
  $("#et-create-group-form").on("submit", function (e) {
    e.preventDefault();

    const formData = {
      name: $("#group-name").val(),
      description: $("#group-description").val(),
    };

    // Add your AJAX call here to handle form submission
    // After successful submission, close the modal
    closeModal();
  });

  // Handle search form submission
  $("#et-search-groups-form").on("submit", function (e) {
    e.preventDefault();
    const searchTerm = $("#group-search").val();
    console.log("Search term:", searchTerm);

    // Add your AJAX call here to handle search
    // Update the groups list with the search results
  });
});
