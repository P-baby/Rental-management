<?php
session_start();
include 'connection.php';

// Get user id from session
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}
$user_id = $_SESSION['user_id'];

// Fetch user information
$user_query = "SELECT email, first_name FROM users WHERE user_id = ?";
$stmt = $conn->prepare($user_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($email, $first_name);
$stmt->fetch();
$stmt->close();

// Get current active rentals (not yet returned)
$active_sql = "SELECT COUNT(*) FROM rentals WHERE user_id = ? AND return_datetime IS NULL";
$stmt = $conn->prepare($active_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($active_count);
$stmt->fetch();
$stmt->close();

// Get total rentals for user
$total_sql = "SELECT COUNT(*) FROM rentals WHERE user_id = ?";
$stmt = $conn->prepare($total_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($total_rentals_count);
$stmt->fetch();
$stmt->close();

// Get overdue rentals (not returned and due passed)
$overdue_sql = "SELECT COUNT(*) FROM rentals WHERE user_id = ? AND return_datetime IS NULL AND due_datetime < NOW()";
$stmt = $conn->prepare($overdue_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($overdue_count);
$stmt->fetch();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User dashboard</title>
    <link rel="stylesheet" href="../css/userdashboards.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        fetch('due_soon.php')
            .then(response => response.json())
            .then(data => {
                console.log(data);
                if (data.due_soon > 0) {
                    document.querySelector('.topnav_right').innerHTML += `<span class="due_soon_alert">${data.due_soon} items due soon</span>`;
                }
            });
    </script>
</head>
<body>
    <div class="userdashboard_maincontainer">
        <div class="userdashboard_sidebar">
            <h2 class="userdashboard_userinfo">Hi, <?php echo htmlspecialchars($first_name); ?></h2>
            <div class="userdashboard_sidebar_user">
                <img src="../images/profile.jpeg" alt="User Image" class="userdashboard_sidebar_user_image">
            </div>
            
            <div class="userdashboard_sidebar_menu">
                <ul class="userdashboard_sidebar_menu_list">
                    <li><a href="#" onclick="loadPage('userdashboard_home.php'); return false;" class="fas fa-dashboard">Dashboard</a></li>

                    <li><a href="#" onclick="loadPage('user_search.php'); return false;" class="fas fa-search">Search equipments</a></li>

                    <li ><a href="#" onclick="loadPage('rent.php'); return false;" class="fas fa-shopping-cart">Rent Equipment</a></li>

                    <li><a href="#" onclick="loadPage('currentrentals.php'); return false;" class="fas fa-wrench">Current rentals</a></li>

                    <li><a href="#" onclick="loadPage('rentalhistory.php'); return false;" class="fas fa-history">Rental history</a></li>

                    <li><a href="#" onclick="loadPage('accountsettings.php'); return false;" class="fas fa-user">Profile</a></li>

                    <li><a href="../logout.php" class="fas fa-power-off">Logout</a></li>
                </ul>
            </div>
        </div>
    
        <div class="userdashboard_content_container">
            <div class="userdashboard__topnav">
                <span class="userdashboard_welcome">Welcome <?php echo htmlspecialchars($first_name); ?>!</span>
            
                <div class="topnav_right"><a href="#"><i class="fas fa-bell"></i></a></div>
            </div>
            <div class="userdashboard_content" >
                <div id="content_area">
                    
                </div>
            </div>
        </div>
        <div class="userdashboard_footer">
            <p>&copy; 2026 FieldGear. All rights reserved.</p>
        </div>
    </div>
    <div id="toast"></div>

 <!-- JavaScript for AJAX content loading -->
    <script>
        function loadPage(page) {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', page, true);
    xhr.onload = function() {
        if (this.status === 200) {
            document.getElementById('content_area').innerHTML = this.responseText;
        } else {
            document.getElementById('content_area').innerHTML = '<p>Error loading page.</p>';
        }
    };
    xhr.send();
}

    // --------- Delegated form submissions (RENT / RETURN / PROFILE) ----------
    document.addEventListener('submit', function(e) {
    // Rent
    if (e.target && e.target.id === 'rentForm') {
        e.preventDefault();
        const form = e.target;
        const tool_id = form.querySelector('input[name="tool_id"]').value;
        fetch('rent.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ tool_id }),
        })
        .then(response => response.json())
        .then(data => {
            showToast(data.message, data.success ? 'success' : 'error');
            if (data.success) {
                form.closest('.rent_confirm_card').remove();
                loadPage('currentrentals.php');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('An error occurred while processing your rental.', 'error');
        });
    }

    // Return
    if (e.target && e.target.classList.contains('return_form')) {
        e.preventDefault();
        const form = e.target;
        const tool_id = form.querySelector('input[name="tool_id"]').value;
        fetch('return_tool.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ tool_id }),
        })
        .then(response => response.json())
        .then(data => {
            showToast(data.message, data.success ? 'success' : 'error');
            if (data.success) loadPage('currentrentals.php');
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('An error occurred while returning the tool.', 'error');
        });
    }

    // Profile Save
    if (e.target && e.target.id === 'editProfileForm') {
        e.preventDefault();
        const formData = new FormData(e.target);
        fetch('update_profile.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            showToast(data.message, data.success ? 'success' : 'error');
            if (data.success) {
                document.getElementById('editProfileModal').style.display = 'none';
                loadPage('accountsettings.php'); // Optionally refresh profile section
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('An error occurred while updating your profile.', 'error');
        });
    }
});

// --------- Delegated click events (MODAL) ----------
document.addEventListener('click', function(e) {
    // Show edit profile modal
    if (e.target && e.target.id === 'editProfileBtn') {
        document.getElementById('editProfileModal').style.display = 'block';
    }
    // Close modal
    if (e.target && e.target.classList.contains('close')) {
        document.getElementById('editProfileModal').style.display = 'none';
    }
});

// --------- Toast UI ----------
function showToast(message, type = 'info') {
    const toast = document.getElementById('toast');
    toast.textContent = message;
    toast.className = 'show ' + type;
    setTimeout(() => {
        toast.className = toast.className.replace('show', '').trim();
    }, 3000);
}

// --------- DOMContentLoaded ONLY for static setup ---------
document.addEventListener('DOMContentLoaded', function() {
    var rightNav = document.querySelector('.topnav_right');
    if (rightNav) {
        rightNav.addEventListener('click', function() {
            alert('You have ' + <?php echo $overdue_count; ?> + ' overdue rentals and ' + <?php echo $active_count; ?> + ' active rentals.');
        });
    }
    loadPage('userdashboard_home.php');
    const navLinks = document.querySelectorAll('.userdashboard_sidebar_menu_list a');
    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            navLinks.forEach(l => l.classList.remove('active'));
            this.classList.add('active');
        });
    });
});
    </script>
</body>
</html>
