<?php
require_once 'php/config.php';
require_once 'model_salesReport.php';

// Initialize SalesReport with the database connection
$salesReport = new SalesReport(mysqli: $mysqli);

// Get report parameters
$start_date = $_GET['start_date'] ?? date('Y-m-01');
$end_date = $_GET['end_date'] ?? date('Y-m-d');
$cashier_id = $_GET['cashier_id'] ?? '';
$payment_method = $_GET['payment_method'] ?? '';

// Get cashier list
$cashiers = [];
$cashierQuery = $mysqli->query("SELECT user_id, full_name FROM users WHERE role_id = 2");
while ($row = $cashierQuery->fetch_assoc()) {
  $cashiers[] = $row;
}

try {
  $reportData = $salesReport->generateReport($start_date, $end_date, $cashier_id, $payment_method);

  if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="sales_report.csv"');

    $output = fopen('php://output', 'w');
    fputcsv($output, ['Date', 'Transactions', 'Items Sold', 'Gross Sales', 'Discounts', 'Net Sales', 'Profit']);

    foreach ($reportData as $row) {
      fputcsv($output, [
        $row['date'],
        $row['transactions'],
        $row['items_sold'],
        number_format($row['total_sales'], 2),
        number_format($row['total_discounts'], 2),
        number_format($row['total_sales'] - $row['total_discounts'], 2),
        number_format($row['gross_profit'], 2)
      ]);
    }
    fclose($output);
    exit;
  }
} catch (Exception $e) {
  $error = $e->getMessage();
}

$mysqli->close();
include 'clerk_sidebar.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sales Report</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      display: flex;
      justify-content: flex-end;
      margin-top: 70px;
    }

    .card {
      border-radius: 10px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      border: none;
      width: 1170px;
    }

    .card-header {
      color: white;
      border-radius: 10px 10px 0 0 !important;
      padding: 1rem 1.5rem;
    }
  </style>
</head>

<body class="bg-light">
  <main class="main container py-5" id="main">
    <div class="card shadow">
      <div class="card-header d-flex justify-content-between align-items-center bg-primary">
        <h4><i class="fas fa-house-damage me-2"></i>Sales Report</h4>
      </div>
      <div class="card-body p-4 ">
        <form method="GET" class="mb-4">
          <!-- Filter Buttons -->
          <label for="" class="mb-5">Filter:</label>
          <a href="sales_daily.php" class="btn btn-outline-primary">Daily</a>
          <a href="sales_weekly.php" class="btn btn-outline-primary">Weekly</a>
          <a href="sales_monthly.php" class="btn btn-outline-primary">Monthly</a>
          <div class="col-md">
            <div class="row">
              <div class="col-md-3">
                <label>Date From</label>
                <input type="date" name="start_date" class="form-control" value="<?= htmlspecialchars($start_date) ?>">
              </div>
              <div class="col-md-3">
                <label>Date To</label>
                <input type="date" name="end_date" class="form-control" value="<?= htmlspecialchars($end_date) ?>">
              </div>
              <div class="col-md-3">
                <label>Cashier</label>
                <select name="cashier_id" class="form-control">
                  <option value="">All Cashiers</option>
                  <?php foreach ($cashiers as $cashier): ?>
                    <option value="<?= $cashier['user_id'] ?>" <?= ($cashier_id == $cashier['user_id']) ? 'selected' : '' ?>>
                      <?= htmlspecialchars($cashier['full_name']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Generate Report</button>
              </div>
            </div>
        </form>

        <div class="mb-3">
          <a href="?<?= http_build_query(array_merge($_GET, ['export' => 'csv'])) ?>"
            class="btn btn-secondary mt-3">Export
            to CSV</a>
        </div>

        <?php if (isset($error)): ?>
          <div class="alert alert-danger">Error: <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if (!empty($reportData)): ?>
          <?php
          $totalSales = array_sum(array_column($reportData, 'total_sales'));
          $totalTransactions = array_sum(array_column($reportData, 'transactions'));
          $avgSale = $totalTransactions > 0 ? $totalSales / $totalTransactions : 0;
          ?>
          <div class="mb-4">
            <div class="row">
              <div class="col-md-3">
                <h5>Total Sales: ₱<?= number_format($totalSales, 2) ?></h5>
              </div>
              <div class="col-md-3">
                <h5>Total Transactions: <?= $totalTransactions ?></h5>
              </div>
              <div class="col-md-3">
                <h5>Average Sale: ₱<?= number_format($avgSale, 2) ?></h5>
              </div>
            </div>
          </div>
          
          <!-- CHART -->
          <div>
            <canvas id="myChart"></canvas>
          </div>

          <table class="table table-striped">
            <thead>
              <tr>
                <th>Date</th>
                <th>Transactions</th>
                <th>Items Sold</th>
                <th>Gross Sales</th>
                <th>Discounts</th>
                <th>Net Sales</th>
                <th>Profit</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($reportData as $row): ?>
                <tr>
                  <td><?= htmlspecialchars($row['date']) ?></td>
                  <td><?= htmlspecialchars($row['transactions']) ?></td>
                  <td><?= htmlspecialchars($row['items_sold']) ?></td>
                  <td>₱<?= number_format($row['total_sales'], 2) ?></td>
                  <td>₱<?= number_format($row['total_discounts'], 2) ?></td>
                  <td>₱<?= number_format($row['total_sales'] - $row['total_discounts'], 2) ?></td>
                  <td>₱<?= number_format($row['gross_profit'], 2) ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php else: ?>
          <p>No sales data found for the selected period.</p>
        <?php endif; ?>
      </div>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <script>
    const chartLabels = <?= json_encode(array_column($reportData, 'date')) ?>;
    const salesData = <?= json_encode(array_column($reportData, 'total_sales')) ?>;

    const ctx = document.getElementById('myChart').getContext('2d');
    new Chart(ctx, {
      type: 'line',
      data: {
        labels: chartLabels,
        datasets: [{
          label: 'Daily Sales (₱)',
          data: salesData,
          borderColor: 'rgba(75, 192, 192, 1)',
          backgroundColor: 'rgba(75, 192, 192, 0.2)',
          tension: 0.3,
          fill: true
        }]
      },
      options: {
        responsive: true,
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });
  </script>
</body>

</html>