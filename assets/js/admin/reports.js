jQuery(document).ready(function ($) {
  // Chart instances
  let expensesChart = null;
  let categoryChart = null;

  // Initialize charts
  function initCharts() {
    const expensesCtx = document
      .getElementById("expenses-chart")
      .getContext("2d");
    const categoryCtx = document
      .getElementById("category-chart")
      .getContext("2d");

    expensesChart = new Chart(expensesCtx, {
      type: "line",
      data: {
        labels: [],
        datasets: [
          {
            label: "Daily Expenses",
            data: [],
            borderColor: "#2271b1",
            tension: 0.1,
          },
        ],
      },
      options: {
        responsive: true,
        scales: {
          y: {
            beginAtZero: true,
          },
        },
      },
    });

    categoryChart = new Chart(categoryCtx, {
      type: "pie",
      data: {
        labels: [],
        datasets: [
          {
            data: [],
            backgroundColor: [
              "#2271b1",
              "#3498db",
              "#9b59b6",
              "#e74c3c",
              "#f1c40f",
              "#2ecc71",
            ],
          },
        ],
      },
      options: {
        responsive: true,
      },
    });
  }

  // Load report data
  function loadReportData() {
    const filters = {
      period: $('select[name="period"]').val(),
      start_date: $('input[name="start_date"]').val(),
      end_date: $('input[name="end_date"]').val(),
      category: $('select[name="category"]').val(),
      _nonce: expense_tracker.nonce,
    };

    $.ajax({
      url: expense_tracker.ajax_url,
      type: "POST",
      data: {
        action: "get_expense_report",
        ...filters,
      },
      success: function (response) {
        if (response.success) {
          updateCharts(response.data);
          updateSummary(response.data);
        }
      },
    });
  }

  // Update charts with new data
  function updateCharts(data) {
    // Update expenses over time chart
    expensesChart.data.labels = data.timeline.labels;
    expensesChart.data.datasets[0].data = data.timeline.values;
    expensesChart.update();

    // Update category distribution chart
    categoryChart.data.labels = data.categories.labels;
    categoryChart.data.datasets[0].data = data.categories.values;
    categoryChart.update();
  }

  // Update summary cards
  function updateSummary(data) {
    $(".total-amount").text(data.summary.total);
    $(".average-amount").text(data.summary.average);
    $(".highest-amount").text(data.summary.highest);
    $(".transaction-count").text(data.summary.count);
  }

  // Event handlers
  $("form").on("submit", function (e) {
    e.preventDefault();
    loadReportData();
  });

  $('select[name="period"]').on("change", function () {
    if ($(this).val() === "custom") {
      $(".date-range").show();
    } else {
      $(".date-range").hide();
      loadReportData();
    }
  });

  // Initialize on page load
  initCharts();
  loadReportData();
});
