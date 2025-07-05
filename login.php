<?php
// session_start();
// include 'php/config.php';



// if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"]) && $_POST["action"] === "login") {
//     $id_number = $_POST["id_number"];
//     $password = $_POST["password"];

//     $sql = "SELECT * FROM users WHERE id_number = '$id_number' AND password = '$password'";
//     $result = $conn->query($sql);

//     if ($result->num_rows > 0) {
//         $user = $result->fetch_assoc();
//         $_SESSION["user"] = $user;
//         header("Location: index.php?page=users_table");
//         exit();
//     } else {
//         $error = "Invalid credentials.";
//     }
// }

// if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"]) && $_POST["action"] === "register") {
//     $name = $_POST["name"];
//     $id_number = $_POST["id_number"];
//     $password = $_POST["password"];
//     $email = $_POST["email"];
//     $function = $_POST["function"];
//     $designation = $_POST["designation"];
//     $employed = $_POST["employed"]; 

//     $stmt = $conn->prepare("INSERT INTO users (name, id_number, password, email, function, designation, employed)
//                             VALUES (?, ?, ?, ?, ?, ?, ?)");
//     $stmt->bind_param("sssssss", $name, $id_number, $password, $email, $function, $designation, $employed);

//     if ($stmt->execute()) {
//         $success = "Registration successful! You can now log in.";
//     } else {
//         $error = "Error registering user: " . $stmt->error;
//     }
// }

// if (isset($_GET['page']) && $_GET['page'] === 'forgot_password') {
//     if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"]) && $_POST["action"] === "reset_password") {
//         $email = $_POST["email"];
//         $sql = "SELECT * FROM users WHERE email = '$email'";
//         $result = $conn->query($sql);

//         if ($result->num_rows > 0) {
//             $user = $result->fetch_assoc();
//             $new_password = "newpassword";
//             $update_sql = "UPDATE users SET password = '$new_password' WHERE email = '$email'";

//             if ($conn->query($update_sql) === TRUE) {
//                 $success = "Password reset successful. Your new password is: $new_password";
//             } else {
//                 $error = "Error resetting password.";
//             }
//         } else {
//             $error = "No account found with that email.";
//         }
//     }
// }
// ?>
<!-- para sa usertable naman to -->
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
                        <form method="post">
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