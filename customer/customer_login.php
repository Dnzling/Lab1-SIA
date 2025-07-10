<?php 
session_start();

include '../php/config.php';

if($_SERVER['REQUEST_METHOD'] == "POST") {
    $id_number = mysqli_real_escape_string($con, $_POST['id_number']);
    $password = $_POST['password'];

    if(!empty($id_number) && !empty($password) && !is_numeric($id_number)) {
        $query = "SELECT * FROM users WHERE user_id = ? LIMIT 1";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, 's', $id_number);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if($result && mysqli_num_rows($result) > 0) {
            $user_data = mysqli_fetch_assoc($result);
            if(password_verify($password, $user_data['password'])) {
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user_data['user_id'];
                $_SESSION['user_name'] = $user_data['full_name'];
                header("Location: dashboard.php");
                exit();
            }
        }
        $error = "Invalid username or password!";
    } else {
        $error = "Please enter valid credentials!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Login - Quick Go Mart</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@300;500;700&display=swap');

        body {
            font-family: 'Lexend Deca', sans-serif;
            background: linear-gradient(135deg, #e3f2fd, #bbdefb);
        }

        .box-area {
            width: 950px;
        }

        .left-box {
            background: #1565c0;
            color: white;
        }

        .right-box {
            padding: 40px;
        }

        .logo-img {
            width: 200px;
        }

        .rounded-4 {
            border-radius: 20px;
        }

        .rounded-5 {
            border-radius: 30px;
        }

        .form-control:focus {
            border-color: #1976d2;
            box-shadow: 0 0 0 0.25rem rgba(25, 118, 210, 0.25);
        }

        .btn-blue {
            background-color: #1976d2;
            color: white;
        }

        .btn-blue:hover {
            background-color: #1565c0;
        }
    </style>
</head>

<body>
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="row border rounded-5 p-3 bg-white shadow box-area">
            <div class="col-md-6 rounded-4 d-flex justify-content-center align-items-center flex-column left-box">
                <div class="text-center">
                    <img src="images/quickgo_logo.png" alt="Quick Go Mart Logo" class="img-fluid logo-img mb-3">
                    <h2 class="fw-bold">Welcome to Quick Go Mart</h2>
                    <p class="text-light text-center">Your everyday convenience store, now online!</p>
                </div>
            </div>

            <div class="col-md-6 right-box">
                <div class="row align-items-center">
                    <div class="header-text mb-4 text-center">
                        <h3 class="fw-bold">Customer Login</h3>
                        <p class="text-muted">Shop smarter and faster with your Quick Go Mart account</p>

                        <?php if (isset($error))
                            echo "<div class='alert alert-danger'>$error</div>"; ?>

                        <form method="POST">
                            <input type="hidden" name="action" value="login">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control bg-light" id="id_number" name="id_number" placeholder="ID Number" required>
                                <label for="id_number">ID Number</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control bg-light" id="password" name="password" placeholder="Password" required>
                                <label for="password">Password</label>
                            </div>
                            <button type="submit" class="btn btn-blue w-100 py-2">Login</button>
                        </form>

                        <div class="mt-3 text-center">
                            <small>Don't have an account? <a href="customer_register.php" class="text-primary fw-semibold">Register Now</a></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
