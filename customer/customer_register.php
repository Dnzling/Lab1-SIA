<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register - Quick Go Mart</title>
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
          <img src="images/quickgo_logo.png" alt="Quick Go Mart Logo" class="img-fluid logo-img mb-3" style="width:200px;">
          <h2 class="fw-bold">Join Quick Go Mart</h2>
          <p class="text-light text-center">Your everyday convenience store, now online!</p>
        </div>
      </div>
      <div class="col-md-6 right-box">
        <h3 class="fw-bold text-center mb-4">Create an Account</h3>
        <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
        <?php if (isset($success)) echo "<div class='alert alert-success'>$success</div>"; ?>
        <form method="POST" action="register_process.php">
          <div class="form-floating mb-3">
            <input type="text" class="form-control bg-light" name="name" placeholder="Full Name" required>
            <label>Full Name</label>
          </div>
          <div class="form-floating mb-3">
            <input type="text" class="form-control bg-light" name="phone" placeholder="Phone Number" required>
            <label>Phone Number</label>
          </div>
          <div class="form-floating mb-3">
            <input type="password" class="form-control bg-light" name="password" placeholder="Password" required>
            <label>Password</label>
          </div>
          <div class="form-floating mb-3">
            <input type="password" class="form-control bg-light" name="confirm" placeholder="Confirm Password" required>
            <label>Confirm Password</label>
          </div>
          <button type="submit" class="btn btn-blue w-100 py-2">Register</button>
          <div class="mt-3 text-center">
            <small>Already have an account? <a href="login.php" class="text-primary fw-semibold">Login Here</a></small>
          </div>
        </form>
      </div>
    </div>
  </div>
</body>

</html>
