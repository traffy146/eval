<?php include('db_connect.php'); ?>
<?php
function ordinal_suffix1($num)
{
  $num = $num % 100; // protect against large numbers
  if ($num < 11 || $num > 13) {
    switch ($num % 10) {
      case 1:
        return $num . 'st';
      case 2:
        return $num . 'nd';
      case 3:
        return $num . 'rd';
    }
  }
  return $num . 'th';
}
$astat = array("Not Yet Started", "On-going", "Closed");
?>

<!-- Dashboard Styling -->
<style>
  .dashboard-container {
    text-align: center;
    margin-top: 20px;
  }

  .dashboard-academic {
    background: transparent !important;
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    margin-bottom: 30px;
  }

  .dashboard-academic h2 {
    font-size: 2rem;
    font-weight: bold;
    font-family: "Helvetica", "Poppins", sans-serif;
    color: white;
  }

  .dashboard-academic h4 {
    font-size: 1.1rem;
    color: white;
    margin-top: 10px;
  }

  .dashboard-stats {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    max-width: 600px;
    margin: 0 auto;
  }

  .dashboard-card {
    background: transparent !important;
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border-radius: 20px;
    padding: 25px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    text-align: center;
    transition: transform 0.2s;
  }

  .dashboard-card:hover {
    transform: translateY(-5px);
  }

  .dashboard-card i {
    font-size: 40px;
    margin-bottom: 10px;
    color: white;
  }

  .dashboard-card h3 {
    margin: 0;
    font-size: 1.8rem;
    color: #e9ebedff;
  }

  .dashboard-card p {
    margin: 5px 0 0;
    color: #f3ededff;
    font-size: 1rem;
  }

  p {
    color: white;
  }

  .activity-logs-section {
    margin-top: 40px;
    max-width: 1200px;
    margin-left: auto;
    margin-right: auto;
  }

  .activity-logs-card {
    background: transparent !important;
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
  }

  .activity-logs-card h3 {
    color: white;
    margin-bottom: 20px;
    font-size: 1.5rem;
    border-bottom: 2px solid rgba(255, 255, 255, 0.2);
    padding-bottom: 10px;
  }

  .activity-log-item {
    background: rgba(255, 255, 255, 0.1);
    border-left: 4px solid;
    padding: 12px 15px;
    margin-bottom: 10px;
    border-radius: 5px;
    transition: all 0.2s;
  }

  .activity-log-item:hover {
    background: rgba(255, 255, 255, 0.15);
    transform: translateX(5px);
  }

  .activity-log-item.login {
    border-left-color: #28a745;
  }

  .activity-log-item.password {
    border-left-color: #ffc107;
  }

  .activity-log-item.create {
    border-left-color: #17a2b8;
  }

  .activity-log-item.update {
    border-left-color: #007bff;
  }

  .activity-log-item.default {
    border-left-color: #6c757d;
  }

  .activity-log-item .log-user {
    font-weight: bold;
    color: #fff;
  }

  .activity-log-item .log-action {
    color: #e9ecef;
    margin: 5px 0;
  }

  .activity-log-item .log-time {
    color: #adb5bd;
    font-size: 0.85rem;
  }

  .no-logs {
    text-align: center;
    padding: 30px;
    color: #adb5bd;
    font-style: italic;
  }
</style>

<div class="dashboard-container">
  <!-- Academic Year Section -->
  <div class="dashboard-academic">
    <h2>Academic Year:
      <?php echo $_SESSION['academic']['year'] . ' ' . (ordinal_suffix1($_SESSION['academic']['semester'])) ?> Semester</h2>
    <h4>Evaluation Status: <?php echo $astat[$_SESSION['academic']['status']] ?></h4>
    <p><small>Welcome <?php echo $_SESSION['login_name'] ?>!</small></p>
  </div>

  <!-- Statistics Section -->
  <div class="dashboard-stats">
    <div class="dashboard-card">
      <i class="fa fa-user-friends"></i>
      <h3><?php echo $conn->query("SELECT * FROM faculty_list ")->num_rows; ?></h3>
      <p>Total Faculties</p>
    </div>

    <div class="dashboard-card">
      <i class="fa fa-user-graduate"></i>
      <h3><?php echo $conn->query("SELECT * FROM student_list")->num_rows; ?></h3>
      <p>Total Students</p>
    </div>

    <div class="dashboard-card">
      <i class="fa fa-users"></i>
      <h3><?php echo $conn->query("SELECT * FROM users")->num_rows; ?></h3>
      <p>Total Users</p>
    </div>

    <div class="dashboard-card">
      <i class="fa fa-list-alt"></i>
      <h3><?php echo $conn->query("SELECT * FROM class_list")->num_rows; ?></h3>
      <p>Total Classes</p>
    </div>
  </div>

  <!-- Activity Logs Section -->
  <div class="activity-logs-section">
    <div class="activity-logs-card">
      <h3><i class="fa fa-history"></i> Recent Activity Logs</h3>
      <?php
      $logs = $conn->query("SELECT * FROM activity_logs ORDER BY timestamp DESC LIMIT 15");
      if ($logs->num_rows > 0):
        while ($row = $logs->fetch_assoc()):
          // Determine log type for styling
          $log_class = 'default';
          if (stripos($row['action'], 'login') !== false) {
            $log_class = 'login';
          } elseif (stripos($row['action'], 'password') !== false) {
            $log_class = 'password';
          } elseif (stripos($row['action'], 'create') !== false) {
            $log_class = 'create';
          } elseif (stripos($row['action'], 'update') !== false) {
            $log_class = 'update';
          }

          // Format timestamp
          $timestamp = date('M d, Y - h:i A', strtotime($row['timestamp']));
          ?>
          <div class="activity-log-item <?php echo $log_class ?>">
            <div class="log-user">
              <?php echo ucfirst($row['user_type']) ?>: <?php echo $row['username'] ?>
            </div>
            <div class="log-action">
              <?php echo $row['description'] ?>
            </div>
            <div class="log-time">
              <i class="fa fa-clock"></i> <?php echo $timestamp ?>
            </div>
          </div>
        <?php
        endwhile;
      else:
        ?>
        <div class="no-logs">
          <i class="fa fa-info-circle"></i> No activity logs found
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>