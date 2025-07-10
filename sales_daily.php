<?php
require_once 'php/config.php';
require_once 'model_salesReport.php';

$salesReport = new SalesReport($mysqli);

$start_date = $_GET['start_date'] ?? '';
$end_date = $_GET['end_date'] ?? '';

$cashiers = [];
$cashierQuery = $mysqli->query("SELECT user_id, full_name FROM users WHERE role_id = 2");
while ($row = $cashierQuery->fetch_assoc()) {
    $cashiers[] = $row;
}

try {
    $reportData = $salesReport->generateDailyReport();

    if (isset($_GET['export']) && $_GET['export'] === 'csv') {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="daily_sales_report.csv"');

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
    <title>Daily Sales Report</title>
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
            background-color: lightgreen;
            color: white;
            border-radius: 10px 10px 0 0 !important;
            padding: 1rem 1.5rem;
        }
    </style>
</head>

<body class="bg-light">
    <main class="main container py-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4>Daily Sales Report</h4>
            </div>
            <div class="card-body">
                <form method="GET" class="mb-4">
                    <div class="row">
                        <div class="col-md-3">
                            <label>Date From</label>
                            <input type="date" name="start_date" class="form-control" value="<?= htmlspecialchars($start_date) ?>">
                        </div>
                        <div class="col-md-3">
                            <label>Date To</label>
                            <input type="date" name="end_date" class="form-control" value="<?= htmlspecialchars($end_date) ?>">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">Filter Dates</button>
                        </div>
                    </div>
                </form>

                <div class="mb-3">
                    <a href="?<?= http_build_query(array_merge($_GET, ['export' => 'csv'])) ?>" class="btn btn-secondary">Export to CSV</a>
                </div>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger">Error: <?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <?php if (!empty($reportData)): ?>
                    <?php
                    $filteredData = array_filter($reportData, function($row) use ($start_date, $end_date) {
                        if (!$start_date && !$end_date) return true;
                        $date = $row['date'];
                        if ($start_date && $date < $start_date) return false;
                        if ($end_date && $date > $end_date) return false;
                        return true;
                    });

                    $totalSales = array_sum(array_column($filteredData, 'total_sales'));
                    $totalTransactions = array_sum(array_column($filteredData, 'transactions'));
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
                    <div>
            <canvas id="salesChart" class="container p-5 mx-auto my-0" style="max-width: 1100px; min-height: 300px;"></canvas>
            
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
                            <?php foreach ($filteredData as $row): ?>
                                <tr>
                                    <td><?= $row['date'] ?></td>
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
                    <p>No daily sales data found for the selected period.</p>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
 const chartLabels = <?= json_encode(array_column($reportData, 'date')) ?>;
    const salesData = <?= json_encode(array_column($reportData, 'total_sales')) ?>;

    const ctx = document.getElementById('salesChart').getContext('2d');
    new Chart(ctx, {
    type: 'line',
    data: {
      labels: chartLabels,
      datasets: [{
        label: 'Total Sales (₱)',
        data: salesData,
        fill: true,
        backgroundColor: 'rgba(54, 162, 235, 0.2)',
        borderColor: 'rgba(54, 162, 235, 1)',
        tension: 0.3
      }]
    },
    options: {
      responsive: true,
      plugins: {
        title: {
          display: true,
          text: 'Daily Total Sales Chart'
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            callback: value => '₱' + value.toLocaleString()
          }
        }
      }
    }
  });
</script>

</body>

</html>
