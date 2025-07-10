<?php
class SalesReport
{
    private $mysqli;

    public function __construct(mysqli $mysqli)
    {
        $this->mysqli = $mysqli;
    }
    //---- WEEKLY REPORT ----
    public function generateWeeklyReport($start, $end, $cashier_id = '')
    {
        $query = "
        SELECT 
            YEARWEEK(transaction_date, 1) AS week,
            COUNT(*) AS transactions,
            SUM(total_items) AS items_sold,
            SUM(subtotal) AS total_sales,
            SUM(total_discount) AS total_discounts,
            SUM(total_profit) AS gross_profit
        FROM sales
        WHERE DATE(transaction_date) BETWEEN ? AND ?
    ";

        $types = "ss";
        $params = [$start, $end];

        if (!empty($cashier_id)) {
            $query .= " AND cashier_id = ?";
            $types .= "s";
            $params[] = $cashier_id;
        }

        $query .= " GROUP BY YEARWEEK(transaction_date, 1) ORDER BY week ASC";

        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // ----- MONTHLY REPORT -----
    public function generateMonthlyReport($start, $end, $cashier_id = '')
    {
        $query = "
        SELECT 
            DATE_FORMAT(transaction_date, '%Y-%m') AS month,
            COUNT(*) AS transactions,
            SUM(total_items) AS items_sold,
            SUM(subtotal) AS total_sales,
            SUM(total_discount) AS total_discounts,
            SUM(total_profit) AS gross_profit
        FROM sales
        WHERE DATE(transaction_date) BETWEEN ? AND ?
    ";

        $types = "ss";
        $params = [$start, $end];

        if (!empty($cashier_id)) {
            $query .= " AND cashier_id = ?";
            $types .= "sss";
            $params[] = $cashier_id;
        }

        $query .= " GROUP BY DATE_FORMAT(transaction_date, '%Y-%m') ORDER BY month ASC";

        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // ----- DAILY REPORT ------
    public function generateDailyReport()
{
    $query = "
        SELECT 
            DATE(transaction_date) AS date,
            COUNT(*) AS transactions,
            SUM(total_items) AS items_sold,
            SUM(subtotal) AS total_sales,
            SUM(total_discount) AS total_discounts,
            SUM(total_profit) AS gross_profit
        FROM sales
        GROUP BY DATE(transaction_date)
        ORDER BY date ASC
    ";

    $stmt = $this->mysqli->prepare($query);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}


// ---- FOR DATE FILTER -----
    public function generateReport($start_date, $end_date, $cashier_id = '', $payment_method = '')
    {
        $query = "
            SELECT 
                DATE(transaction_date) AS date,
                COUNT(*) AS transactions,
                SUM(total_items) AS items_sold,
                SUM(subtotal) AS total_sales,
                SUM(total_discount) AS total_discounts,
                SUM(total_profit) AS gross_profit
            FROM sales
            WHERE DATE(transaction_date) BETWEEN ? AND ?
        ";

        $types = "ss";
        $params = [$start_date, $end_date];

        if (!empty($cashier_id)) {
            $query .= " AND cashier_id = ?";
            $types .= "s";
            $params[] = $cashier_id;
        }

        if (!empty($payment_method)) {
            $query .= " AND payment_method = ?";
            $types .= "s";
            $params[] = $payment_method;
        }

        $query .= " GROUP BY DATE(transaction_date) ORDER BY DATE(transaction_date) ASC";

        $stmt = $this->mysqli->prepare($query);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $this->mysqli->error);
        }

        $stmt->bind_param($types, ...$params);
        $stmt->execute();

        $result = $stmt->get_result();
        $data = [];

        while ($row = $result->fetch_assoc()) {
            // Fill missing profit with 0 if null
            $row['gross_profit'] = $row['gross_profit'] ?? 0;
            $data[] = $row;
        }

        $stmt->close();
        return $data;
    }
}
