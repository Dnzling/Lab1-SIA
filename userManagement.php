<?php
include 'admin_sidebar.php';
include 'php/config.php';
include 'model_user.php';

$userModel = new UserModel($mysqli);
$users = $userModel->getAllUsers();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            border-radius: 10px 10px 0 0 !important;
            padding: 1rem 1.5rem;
        }
    </style>
</head>

<body class="bg-light">
    <main class="container py-5">
        <div class="card shadow">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>Account Management</h4>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">Add
                    User</button>
            </div>
            <div class="card-body">
                <table class="table table-transparent table-hover">
                    <thead class="text-uppercase color-secondary">
                        <tr>
                            <th>ID Number</th>
                            <th>Full Name</th>
                            <th>Role</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= htmlspecialchars($user['user_id']) ?></td>
                                <td class="text-capitalize"><?= htmlspecialchars($user['full_name']) ?></td>
                                <td>
                                    <?= $user['role_id'] == 1 ? 'Admin' : ($user['role_id'] == 2 ? 'Inventory Clerk' : 'Cashier') ?>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#editUserModal<?= $user['user_id'] ?>">Edit</button>
                                    <button onclick="deleteUser('<?= $user['user_id'] ?>')">Remove</button>
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editUserModal<?= $user['user_id'] ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit User</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="edit-user-form" data-user-id="<?= $user['user_id'] ?>">
                                                <input type="hidden" value="<?= $user['user_id'] ?>">
                                                <div class="mb-3">
                                                    <label>Full Name</label>
                                                    <input type="text" class="form-control full-name"
                                                        value="<?= htmlspecialchars($user['full_name']) ?>">
                                                </div>
                                                <div class="mb-3">
                                                    <label>Role</label>
                                                    <select class="form-select role-id">
                                                        <option value="1" <?= $user['role_id'] == 1 ? 'selected' : '' ?>>Admin
                                                        </option>
                                                        <option value="2" <?= $user['role_id'] == 2 ? 'selected' : '' ?>>
                                                            Inventory Clerk</option>
                                                        <option value="3" <?= $user['role_id'] == 3 ? 'selected' : '' ?>>
                                                            Cashier</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label>New Password (optional)</label>
                                                    <input type="password" class="form-control password">
                                                </div>
                                                <div class="mb-3">
                                                    <label>Confirm Password</label>
                                                    <input type="password" class="form-control confirmpassword">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-success"
                                                onclick="saveUser('<?= $user['user_id'] ?>')">Save Changes</button>
                                            <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addUserForm">
                        <!-- User ID Preview (read-only) -->
                        <div class="form-floating mb-3">
                            <input type="text" id="previewUserId" class="form-control form-control-lg bg-light fs-6"
                                placeholder="User ID" readonly>
                            <label for="previewUserId" class="text-secondary">User ID (auto-generated)</label>
                        </div>

                        <!-- Full Name -->
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control form-control-lg bg-light fs-6" id="addFullName"
                                name="addFullName" placeholder="Full Name" required>
                            <label for="addFullName" class="text-secondary">Full Name <i>Firstname/Lastname</i></label>
                        </div>

                        <!-- Role -->
                        <div class="form-floating mb-3">
                            <select name="role" id="addRole" class="form-select form-control-lg bg-light fs-6" required>
                                <option value="" disabled selected>Select role</option>
                                <option value="1">Admin</option>
                                <option value="2">Inventory Clerk</option>
                                <option value="3">Cashier</option>
                            </select>
                            <label for="addRole" class="text-secondary">Role</label>
                        </div>

                        <!-- Password -->
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control form-control-lg bg-light fs-6" id="addPassword"
                                name="addPassword" placeholder="Password" required>
                            <label for="addPassword" class="text-secondary">Password</label>
                        </div>

                        <!-- Confirm Password -->
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control form-control-lg bg-light fs-6"
                                id="addConfirmPassword" name="addConfirmPassword" placeholder="Confirm Password"
                                required>
                            <label for="addConfirmPassword" class="text-secondary">Confirm Password</label>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-primary" onclick="addUser()">Add User</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function saveUser(userId) {
            const form = document.querySelector(`.edit-user-form[data-user-id="${userId}"]`);
            const fullName = form.querySelector('.full-name').value;
            const roleId = form.querySelector('.role-id').value;
            const password = form.querySelector('.password').value;
            const confirmpassword = form.querySelector('.confirmpassword').value;

            // Client-side validation (optional)
            if (!fullName || !roleId) {
                return Swal.fire('Missing fields', 'Please fill in all required fields.', 'warning');
            }

            if (password !== confirmpassword) {
                return Swal.fire('Password Mismatch', 'Passwords do not match.', 'error');
            }

            Swal.fire({
                title: 'Saving changes...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            fetch('user_ajax_handler.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({
                    action: 'update',
                    user_id: userId,
                    full_name: fullName,
                    role_id: roleId,
                    password: password,
                    confirmpassword: confirmpassword
                })
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Updated!', 'User information has been updated.', 'success')
                            .then(() => location.reload());
                    } else {
                        Swal.fire('Update Failed', data.message || 'Could not update user.', 'error');
                    }
                })
                .catch(() => {
                    Swal.fire('Error', 'Something went wrong.', 'error');
                });
        }

        function deleteUser(userId) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'This user will be deactivated (not deleted).',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, deactivate it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('user_ajax_handler.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: new URLSearchParams({
                            action: 'soft_delete',
                            user_id: userId
                        })
                    })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire('Deactivated!', 'User has been deactivated.', 'success')
                                    .then(() => location.reload());
                            } else {
                                Swal.fire('Failed', data.message || 'Deactivation failed.', 'error');
                            }
                        });
                }
            });
        }
        document.getElementById('addRole').addEventListener('change', function () {
            const roleId = this.value;

            fetch('user_ajax_handler.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({
                    action: 'generate_id',
                    role_id: roleId
                })
            })
                .then(res => res.json())
                .then(data => {
                    document.getElementById('previewUserId').value = data.user_id || 'USER-???';
                });
        });

        document.getElementById('addUserModal').addEventListener('shown.bs.modal', function () {
            const roleSelect = document.getElementById('addRole');
            if (roleSelect.value) {
                roleSelect.dispatchEvent(new Event('change')); // trigger generation
            }
        });

        function addUser() {
            const fullName = document.getElementById('addFullName').value;
            const roleId = document.getElementById('addRole').value;
            const password = document.getElementById('addPassword').value;
            const confirmpassword = document.getElementById('addConfirmPassword').value;
            const userId = document.getElementById('previewUserId').value;

            // Simple client-side validation
            if (!fullName || !roleId || !password || !confirmpassword) {
                return Swal.fire('All fields are required.', '', 'warning');
            }

            if (password !== confirmpassword) {
                return Swal.fire('Passwords do not match.', '', 'error');
            }

            // Optional: Show loading
            Swal.fire({
                title: 'Adding user...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            fetch('user_ajax_handler.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({
                    action: 'add',
                    user_id: userId,
                    full_name: fullName,
                    role_id: roleId,
                    password: password,
                    confirmpassword: confirmpassword
                })
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('User added!', '', 'success').then(() => location.reload());
                    } else {
                        Swal.fire('Error', data.message || 'Failed to add user.', 'error');
                    }
                })
                .catch(err => {
                    Swal.fire('Error', 'Something went wrong.', 'error');
                    console.error(err);
                });
        }

    </script>
</body>

</html>