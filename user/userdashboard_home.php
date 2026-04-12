<?php
session_start();
include 'connection.php';

// Default dashboard partial loaded into #content_area.
if (!isset($_SESSION['user_id'])) {
    exit('<p>Please log in again.</p>');
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT COUNT(*) FROM rentals WHERE user_id = ? AND return_datetime IS NULL");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($active_count);
$stmt->fetch();
$stmt->close();

$stmt = $conn->prepare("SELECT COUNT(*) FROM rentals WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($total_rentals_count);
$stmt->fetch();
$stmt->close();

$stmt = $conn->prepare("SELECT COUNT(*) FROM rentals WHERE user_id = ? AND return_datetime IS NULL AND due_datetime < NOW()");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($overdue_count);
$stmt->fetch();
$stmt->close();
?>

<h1>Dashboard Overview</h1>
<div class="dashboard-cards">
  <div class="dashboard-card active-card">
    <h2><?php echo (int)$active_count; ?></h2>
    <p>Active Rentals</p>
  </div>
  <div class="dashboard-card total-card">
    <h2><?php echo (int)$total_rentals_count; ?></h2>
    <p>Total Rentals</p>
  </div>
  <div class="dashboard-card overdue-card">
    <h2><?php echo (int)$overdue_count; ?></h2>
    <p>Overdue Rentals</p>
  </div>
</div>

<style>
.dashboard-cards {
  display: flex;
  gap: 24px;
  justify-content: center;
  margin: 34px auto 20px auto;
}
.dashboard-card {
  flex: 1 1 0;
  background: #fff;
  padding: 26px 20px 18px 20px;
  border-radius: 12px;
  text-align: center;
  box-shadow: 0 2px 14px #0001;
  transition: transform 0.16s, box-shadow 0.16s;
  cursor: pointer;
  min-width: 130px;
}
.dashboard-card:hover {
  transform: translateY(-7px) scale(1.06);
  box-shadow: 0 4px 22px #2d5be380;
  background: #f7faff;
}
.dashboard-card.active-card { border-top: 4px solid #28a745; }
.dashboard-card.total-card { border-top: 4px solid #007bff; }
.dashboard-card.overdue-card { border-top: 4px solid #c23b22; }
.dashboard-card h2 { font-size: 2.8em; margin-bottom:6px; }
.dashboard-card p { color:#666; font-size:1.04em; margin:0; }

#toast {
    display: none;
    position: fixed;
    bottom: 30px;
    left: 50%;
    transform: translateX(-50%);
    background: #333;
    color: #fff;
    padding: 14px 24px;
    border-radius: 7px;
    z-index: 9999;
    font-size: 17px;
    min-width: 140px;
    text-align: center;
    opacity: 0.95;
}
#toast.show {
    display: block;
    animation: fadein 0.3s, fadeout 0.3s 2.7s;
}
#toast.success { background: #198754; }
#toast.error { background: #c23b22; }
@keyframes fadein { from{opacity:0;} to{opacity:0.95;} }
@keyframes fadeout { from{opacity:0.95;} to{opacity:0;} }
</style>