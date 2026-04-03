<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/aduser.css">
</head>
<body>
    <?php
include 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $user_id = isset($_POST['user_id']) ? (int) $_POST['user_id'] : 0;

    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $role = trim($_POST['role'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Add a new user
    if ($action === 'add') {
        if ($first_name !== '' && $last_name !== '' && $username !== '' && $email !== '' && $role !== '' && $password !== '') {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, username, email, password, role) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $first_name, $last_name, $username, $email, $hashedPassword, $role);

            if ($stmt->execute()) {
                $message = 'User added successfully.';
                $messageType = 'success';
            } else {
                $message = 'Unable to add user.';
                $messageType = 'error';
            }

            $stmt->close();
        } else {
            $message = 'Please fill in all fields.';
            $messageType = 'error';
        }
    }

    // Edit an existing user
    if ($action === 'edit' && $user_id > 0) {
        if ($first_name !== '' && $last_name !== '' && $username !== '' && $email !== '' && $role !== '') {
         if ($stmt->execute()) {
                $message = 'User updated successfully.';
                $messageType = 'success';
            } else {
                $message = 'Unable to update user.';
                $messageType = 'error';
            }

            $stmt->close();
        } else {
            $message = 'Please fill in all required fields.';
            $messageType = 'error';
        }
    }

    // Delete a user
    if ($action === 'delete' && $user_id > 0) {
        $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);

        if ($stmt->execute()) {
            $message = 'User deleted successfully.';
            $messageType = 'success';
        } else {
            $message = 'Unable to delete user.';
            $messageType = 'error';
        }

        $stmt->close();
    }
}

/* Get all users from the database so the table always shows updated data*/

$users = $conn->query("SELECT * FROM users ORDER BY user_id DESC");
?>

<div class="users-container">
    <!-- Page top -->
    <div class="top-bar">
        <div>
            <h2>User Management</h2>
            <p>Manage users here.</p>
        </div>

        <!-- Open popup in Add mode -->
        <button 
            type="button" class="main-btn" onclick="
                document.getElementById('formTitle').textContent = 'Add User';
                document.getElementById('action').value = 'add';
                document.getElementById('user_id').value = '';
                document.getElementById('userForm').reset();
                document.getElementById('userModal').style.display = 'flex';" > Add User
        </button>
    </div>


    <!-- Popup form -->
    <div id="userModal" class="modal" onclick="if(event.target === this){ this.style.display = 'none'; }">
        <div class="modal-box">

            <div class="modal-header">
                <h3 id="formTitle">Add User</h3>
                <button type="button" class="close-btn" onclick="document.getElementById('userModal').style.display='none';"> X </button>
            </div>

            <!-- One form used for both add and edit -->
            <form
                id="userForm"
                method="POST"
                onsubmit="
                    event.preventDefault();
                    fetch('aduser.php', {
                        method: 'POST',
                        body: new FormData(this)
                    })
                    .then(function(response) {
                        return response.text();
                    })
                    .then(function(data) {
                        var target = document.getElementById('dashboard_content_main');
                        if (target) {
                            target.innerHTML = data;
                        } else {
                            document.body.innerHTML = data;
                        }
                    });
                "
            >
                <input type="hidden" name="action" id="action" value="add">
                <input type="hidden" name="user_id" id="user_id">

                <div class="form-group">
                    <label for="first_name">First Name</label>
                    <input type="text" name="first_name" id="first_name" required>
                </div>

                <div class="form-group">
                    <label for="last_name">Last Name</label>
                    <input type="text" name="last_name" id="last_name" required>
                </div>

                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" required>
                </div>

                <div class="form-group">
                    <label for="role">Role</label>
                    <select name="role" id="role" required>
                        <option value="">Select Role</option>
                        <option value="admin">Admin</option>
                        <option value="user">User</option>
                    </select>
                </div>


                <div class="form-buttons">
                    <button
                        type="button"
                        class="cancel-btn"
                        onclick="document.getElementById('userModal').style.display='none';"
                    >
                        Cancel
                    </button>
                    <button type="submit" class="save-btn">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Users table -->
    <div class="table-box">
        <table class="users-table">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php if ($users && $users->num_rows > 0): ?>
                    <?php while ($row = $users->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo (int) $row['user_id']; ?></td>
                            <td><?php echo htmlspecialchars($row['first_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['role']); ?></td>
                            <td>
                                <div class="action-buttons">
                                    <!-- Open popup in Edit mode and fill current values -->
                                    <button
                                        type="button"
                                        class="edit-btn"
                                        data-id="<?php echo (int) $row['user_id']; ?>"
                                        data-firstname="<?php echo htmlspecialchars($row['first_name'], ENT_QUOTES); ?>"
                                        data-lastname="<?php echo htmlspecialchars($row['last_name'], ENT_QUOTES); ?>"
                                        data-username="<?php echo htmlspecialchars($row['username'], ENT_QUOTES); ?>"
                                        data-email="<?php echo htmlspecialchars($row['email'], ENT_QUOTES); ?>"
                                        data-role="<?php echo htmlspecialchars($row['role'], ENT_QUOTES); ?>"
                                        onclick="
                                            document.getElementById('formTitle').textContent = 'Edit User';
                                            document.getElementById('action').value = 'edit';
                                            document.getElementById('user_id').value = this.dataset.id;
                                            document.getElementById('first_name').value = this.dataset.firstname;
                                            document.getElementById('last_name').value = this.dataset.lastname;
                                            document.getElementById('username').value = this.dataset.username;
                                            document.getElementById('email').value = this.dataset.email;
                                            document.getElementById('role').value = this.dataset.role;
                                            document.getElementById('userModal').style.display = 'flex';"> Edit
                                    </button>

                                    <!-- Delete user -->
                                    <form method="POST" onsubmit="
                                            event.preventDefault();
                                            if (!confirm('Are you sure you want to delete this user?')) {
                                                return;
                                            }
                                            fetch('aduser.php', {
                                                method: 'POST',
                                                body: new FormData(this)
                                            })
                                            .then(function(response) {
                                                return response.text();
                                            })
                                            .then(function(data) {
                                                var target = document.getElementById('dashboard_content_main');
                                                if (target) {
                                                    target.innerHTML = data;
                                                } else {
                                                    document.body.innerHTML = data;
                                                }
                                            }); " >
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="user_id" value="<?php echo (int) $row['user_id']; ?>">
                                        <button type="submit" class="delete-btn">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="empty-row">No users found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>