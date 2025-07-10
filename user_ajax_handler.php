<?php
header('Content-Type: application/json');
require_once 'php/config.php';  // ✅ Make sure this path is correct
require_once 'model_user.php';

$userModel = new UserModel($mysqli);
$action = $_POST['action'] ?? null;

if (!$action) {
    echo json_encode(['success' => false, 'message' => 'No action specified.']);
    exit;
}

// 🔹 GENERATE ID
elseif ($action === 'generate_id') {
    $role_id = (int)($_POST['role_id'] ?? 0);
    $new_id = $userModel->generateNewUserId($mysqli, $role_id); // ✅ correct now

    echo json_encode(['user_id' => $new_id]);
    exit;
}
// 🔹 UPDATE USER
elseif ($action === 'update') {
    $user_id = $_POST['user_id'];
    $full_name = $_POST['full_name'];
    $role_id = $_POST['role_id'];
    $password = $_POST['password'] ?? '';
    $confirmpassword = $_POST['confirmpassword'] ?? '';

    if (!empty($password) && $password !== $confirmpassword) {
        echo json_encode(['success' => false, 'message' => 'Passwords do not match.']);
        exit;
    }

    $success = $userModel->updateUser($user_id, $full_name, $role_id, $password ?: null);
    echo json_encode(['success' => $success]);
    exit;
}

// 🔹 DELETE USER
elseif ($action === 'soft_delete') {
    $user_id = $_POST['user_id'];
    $success = $userModel->softDeleteUser($user_id);
    echo json_encode(['success' => $success]);
}

// 🔹 ADD USER
elseif ($action === 'add') {
    $user_id = $_POST['user_id'];
    $full_name = $_POST['full_name'];
    $role_id = $_POST['role_id'];
    $password = $_POST['password'] ?? '';
    $confirmpassword = $_POST['confirmpassword'] ?? '';

    if ($password !== $confirmpassword) {
        echo json_encode(['success' => false, 'message' => 'Passwords do not match.']);
        exit;
    }

    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $stmt = mysqli_prepare($mysqli, "INSERT INTO users (user_id, full_name, role_id, password) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ssis", $user_id, $full_name, $role_id, $hashed);
    $success = mysqli_stmt_execute($stmt);

    echo json_encode(['success' => $success]);
}
