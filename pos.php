<?php
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'add') {
        $id = $_POST['item_id'];
        $name = $_POST['item_name'];
        $price = floatval($_POST['item_price']);
        $qty = intval($_POST['quantity']);

        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['quantity'] += $qty;
        } else {
            $_SESSION['cart'][$id] = [
                'name' => $name,
                'price' => $price,
                'quantity' => $qty
            ];
        }
        header("Location: pos.php");
        exit();
    }

    if ($action === 'delete') {
        unset($_SESSION['cart'][$_POST['item_id']]);
        header("Location: pos.php");
        exit();
    }

    if ($action === 'buy') {
        $_SESSION['cart'] = [];
        $purchaseMessage = "Purchase successful!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Point of Sale</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
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
            background-color: #0d6efd;
            color: white;
            border-radius: 10px 10px 0 0 !important;
            padding: 1rem 1.5rem;
        }
        h4 {
            font-weight: 600;
            margin: 0;
        }
        .form-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
        }
        .form-control {
            border-radius: 5px;
            padding: 0.5rem 1rem;
            border: 1px solid #ced4da;
        }
        .form-control:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
        .btn-success {
            background-color: #198754;
            border: none;
            padding: 0.5rem 1.5rem;
            font-weight: 500;
            border-radius: 5px;
        }
        .btn-success:hover {
            background-color: #157347;
        }
        .required-field::after {
            content: " *";
            color: red;
        }

    </style>
</head>
<body>
    <?php 
include 'sidebar.php';
?>
    <main class="container" id="main">


<div class="container my-5">
    <h3 class="mb-4 text-center">🛒 Point of Sale</h3>

    <div class="card card-custom mb-4">
        <div class="card-header bg-primary text-white fw-bold">Available Products</div>
        <div class="card-body">
            <?php
            $items = [
                1 => ['name' => 'Coke', 'price' => 20],
                2 => ['name' => 'Bread', 'price' => 15],
                3 => ['name' => 'Chips', 'price' => 10],
                4 => ['name' => 'Water', 'price' => 8]
            ];

            foreach ($items as $id => $item): ?>
                <form method="POST" class="row g-2 align-items-center mb-2">
                    <div class="col-md-3">
                        <span class="form-icon"><i class="bi bi-box"></i></span>
                        <input type="text" class="form-control form-input" value="<?= htmlspecialchars($item['name']) ?>" disabled>
                        <input type="hidden" name="item_name" value="<?= htmlspecialchars($item['name']) ?>">
                    </div>
                    <div class="col-md-2">
                        <span class="form-icon"><i class="bi bi-cash-coin"></i></span>
                        <input type="text" class="form-control form-input" value="<?= number_format($item['price'], 2) ?>" disabled>
                        <input type="hidden" name="item_price" value="<?= $item['price'] ?>">
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="quantity" value="1" class="form-control" min="1" required>
                    </div>
                    <input type="hidden" name="item_id" value="<?= $id ?>">
                    <input type="hidden" name="action" value="add">
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-success w-100"><i class="bi bi-cart-plus"></i> Add to Cart</button>
                    </div>
                </form>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="card card-custom">
        <div class="card-header bg-success text-white fw-bold">🧾 Cart</div>
        <div class="card-body">
            <?php if (!empty($_SESSION['cart'])): ?>
                <form method="POST">
                    <table class="table table-bordered table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>Product</th>
                                <th>Qty</th>
                                <th>Price</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $grand = 0;
                        foreach ($_SESSION['cart'] as $id => $item):
                            $total = $item['price'] * $item['quantity'];
                            $grand += $total;
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($item['name']) ?></td>
                                <td><?= $item['quantity'] ?></td>
                                <td>₱<?= number_format($item['price'], 2) ?></td>
                                <td>₱<?= number_format($total, 2) ?></td>
                                <td>
                                    <form method="POST" onsubmit="return confirm('Remove this item?');">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="item_id" value="<?= $id ?>">
                                        <button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td colspan="3" class="text-end"><strong>Grand Total</strong></td>
                            <td colspan="2"><strong>₱<?= number_format($grand, 2) ?></strong></td>
                        </tr>
                        </tbody>
                    </table>
                    <button type="submit" name="action" value="buy" class="btn btn-primary w-100">
                        <i class="bi bi-bag-check-fill"></i> Buy Now
                    </button>
                </form>
            <?php else: ?>
                <p>Your cart is empty.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>
