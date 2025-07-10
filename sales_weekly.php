<?php
require_once 'php/config.php';
require_once 'model_salesReport.php';

$salesReport = new SalesReport($mysqli);

$start_date = $_GET['start_date'] ?? date('Y-m-01');
$end_date = $_GET['end_date'] ?? date('Y-m-d');
$cashier_id = $_GET['cashier_id'] ?? '';

$cashiers = [];
$cashierQuery = $mysqli->query("SELECT user_id, full_name FROM users WHERE role_id = 2");
while ($row = $cashierQuery->fetch_assoc()) {
    $cashiers[] = $row;
}

try {
    $reportData = $salesReport->generateWeeklyReport($start_date, $end_date, $cashier_id);

    if (isset($_GET['export']) && $_GET['export'] === 'csv') {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="weekly_sales_report.csv"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['Week', 'Transactions', 'Items Sold', 'Gross Sales', 'Discounts', 'Net Sales', 'Profit']);

        foreach ($reportData as $row) {
            fputcsv($output, [
                'Week ' . $row['week'],
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
    <title>Weekly Sales Report</title>
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
                <h4>Weekly Sales Report</h4>
            </div>
            <div class="m-3">
                <div class="mb-3">
                    <a href="?<?= http_build_query(array_merge($_GET, ['export' => 'csv'])) ?>"
                        class="btn btn-secondary">Export to CSV</a>
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

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Week</th>
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
                                    <td>Week <?= htmlspecialchars($row['week']) ?></td>
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
                    <p>No weekly sales data found for the selected period.</p>
                <?php endif; ?>
            </div>
        </div>
        </div>
    </main>
</body>

</html>