<?php
session_start();
include 'connection.php';

// Simple profile partial for the dashboard content area.
if (!isset($_SESSION['user_id'])) {
    exit('<p>Please log in again.</p>');
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT first_name, last_name, username, email, role FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
?>

<h1>Profile Details</h1>
<p>Name: <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></p>
<p>Username: <?php echo htmlspecialchars($user['username']); ?></p>
<p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
<p>Role: <?php echo htmlspecialchars($user['role']); ?></p>

<button class="edit-profile-btn" id="editProfileBtn">Edit Profile</button>

<!-- Modal for editing profile -->
<div id="editProfileModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <h2>Edit Profile</h2>
    <form id="editProfileForm">
      <label for="first_name">First Name:</label>
      <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>   
        <label for="last_name">Last Name:</label>
        <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        <button type="submit" class="save-changes-btn">Save Changes</button>
    </form>
  </div>
</div>

<style>
    .modal {
  display: none;
  position: fixed; z-index: 9999;
  left: 0; top: 0; width: 100vw; height: 100vh;
  background: rgba(0,0,0,0.32);
}
.modal-content {
  background: #fff;
  border-radius: 10px;
  max-width: 370px;
  margin: 7% auto 0 auto; /* Center modal */
  padding: 32px 22px 24px 22px;
  position: relative;
  box-shadow: 0 2px 14px #0002;
}
.close-modal {
  position: absolute; top: 10px; right: 16px; font-size: 24px; cursor: pointer; color: #888;
}
.modal-content label { display: block; margin: 17px 0 7px 2px; font-weight: 500; color: #268636;}
.modal-content input {
  width: 100%; font-size: 1em;
  padding: 7px 10px; border-radius: 5px;
  border: 1px solid #ccc; background: #fafcff;
}
.save-changes-btn {
  display: block; margin: 23px auto 0 auto;
  background: #2d5be3; color: #fff; padding: 9px 28px;
  border: none; border-radius: 7px; font-size: 1.07em; cursor: pointer;
  transition: background .13s;
}
.save-changes-btn:hover { background: #174dbe; }

.edit-profile-btn {
  margin: 26px 0 0 0; padding: 7px 21px;
  background: #268636; color: #fff; border-radius: 6px; border:none; font-size:1em; cursor:pointer;
  transition: background .12s;
}
.edit-profile-btn:hover { background: #165d2d; }
</style>