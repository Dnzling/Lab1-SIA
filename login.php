<?php 
session_start();

	include 'php/config.php';

	if($_SERVER['REQUEST_METHOD'] == "POST") {
    // Sanitize inputs
    $id_number = mysqli_real_escape_string($con, $_POST['id_number']);
    $password = $_POST['password'];

    if(!empty($id_number) && !empty($password) && !is_numeric($id_number)) {
        // Use prepared statement to prevent SQL injection
        $query = "SELECT * FROM users WHERE user_id = ? LIMIT 1";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, 's', $id_number);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if($result && mysqli_num_rows($result) > 0) {
            $user_data = mysqli_fetch_assoc($result);
            
            // Verify hashed password
            if(password_verify($password, $user_data['password'])) {
                // Regenerate session ID for security
                session_regenerate_id(true);
                
                $_SESSION['user_id'] = $user_data['user_id'];
                $_SESSION['user_name'] = $user_data['full_name'];
                
                header("Location: dashboard.php");
                exit();
            }
        }
        echo "Invalid username or password!";
    } else {
        echo "Please enter valid credentials!";
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

        body {
            font-family: 'Lexend Deca', sans-serif;
            background: #ececec;
        }

        /*------------ Login container ------------*/
        .box-area {
            width: 930px;
        }

        /*------------ Right box ------------*/
        .right-box {
            padding: 40px 40px 40px 40px;
        }

        /*------------ Custom Placeholder ------------*/
        ::placeholder {
            font-size: 16px;
        }

        .rounded-4 {
            border-radius: 20px;
        }

        .rounded-5 {
            border-radius: 30px;
        }
    </style>
</head>

<b class="link offset-3 link-underline link-underline-ocapacity">
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="row border  rounded-5 p-3 bg-white shadow box-area">
            <div class="col-md-6 rounded-4 d-flex justify-content-center align-items-center flex-column left-box"
                style="background:#afe1af">
                <div class="featured-image mb-3">
                    <img src="images/1.png" alt="Untitled Logo" class="img-fluid" style="width: 250px;">
                </div>
                <p class="text-dark fs-2 fw-bold">Be Verified</p>
                <small class="text-dark text-wrap text-center" style="width: 17rem;"></small>
            </div>
            <div class="col-md-6 right-box">
                <div class="row align-items-center">
                    <div class=" header-text mb-4 text-center">
                        <h2 class="fw-semibold">Untitled Convenience Store!</h2>
                        <p class="mt-3 fw-bold fs-4">AGENT LOGIN</p>
                        <?php if (isset($error))
                            echo "<div class='alert alert-danger'>$error</div>"; ?>
                        <form method="POST">
                            <input type="hidden" name="action" value="login">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control form-control-lg bg-light fs-6" id="id_number"
                                    name="id_number" placeholder="ID Number" required>
                                <label for="id_number" class="text-secondary">ID Number</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control form-control-lg bg-light fs-6" id="password"
                                    name="password" placeholder="Password" required>
                                <label for="password" class="text-secondary">Password</label>
                            </div>
                            <button type="submit" class="btn btn-lg btn-success w-100 fs-6">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        </body>
</html>