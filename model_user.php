<?php
// model_user.php
include_once 'php/config.php';

class UserModel
{
    private $mysqli;

    public function __construct($mysqli)
    {
        $this->mysqli = $mysqli;
    }

   public function generateNewUserId($conn, $role_id)
{
    switch ($role_id) {
        case 1: $prefix = 'ADM'; break;
        case 2: $prefix = 'INV'; break;
        case 3: $prefix = 'CSH'; break;
        default: $prefix = 'USR';
    }

    $query = "SELECT MAX(CAST(SUBSTRING(user_id, 5) AS UNSIGNED)) as max_num 
              FROM users 
              WHERE user_id LIKE '{$prefix}-%'";
    $result = mysqli_query($conn, $query);

    if ($result && $row = mysqli_fetch_assoc($result)) {
        $new_number = ($row['max_num'] ?? 0) + 1;
        return $prefix . '-' . str_pad($new_number, 3, '0', STR_PAD_LEFT);
    }

    return $prefix . '-001';
}

    public function addUser($full_name, $role_id, $password)
    {
        global $conn; // or use $this->conn if passed in constructor

        $user_id = $this->generateNewUserId($conn, $role_id);
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (user_id, full_name, role_id, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssis", $user_id, $full_name, $role_id, $hashed_password);

        return $stmt->execute();
    }

    public function updateUser($user_id, $full_name, $role_id, $new_password = null)
{
    if ($new_password !== null && $new_password !== '') {
        $hashed = password_hash($new_password, PASSWORD_DEFAULT);
        $query = "UPDATE users SET full_name = ?, role_id = ?, password = ? WHERE user_id = ?";
        $stmt = mysqli_prepare($this->mysqli, $query);
        mysqli_stmt_bind_param($stmt, "siss", $full_name, $role_id, $hashed, $user_id);
    } else {
        $query = "UPDATE users SET full_name = ?, role_id = ? WHERE user_id = ?";
        $stmt = mysqli_prepare($this->mysqli, $query);
        mysqli_stmt_bind_param($stmt, "sis", $full_name, $role_id, $user_id);
    }

    return mysqli_stmt_execute($stmt);
}


    public function softDeleteUser($user_id) {
    $stmt = mysqli_prepare($this->mysqli, "UPDATE users SET is_active = 0 WHERE user_id = ?");
    mysqli_stmt_bind_param($stmt, "s", $user_id);
    return mysqli_stmt_execute($stmt);
}
    public function getAllUsers()
    {
        $query = "SELECT * FROM users WHERE is_active = 1";
        $result = mysqli_query($this->mysqli, $query);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
}
?>