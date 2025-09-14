<?php include('db_connect.php'); ?>
<?php 
function ordinal_suffix1($num){
    $num = $num % 100; // protect against large numbers
    if($num < 11 || $num > 13){
         switch($num % 10){
            case 1: return $num.'st';
            case 2: return $num.'nd';
            case 3: return $num.'rd';
        }
    }
    return $num.'th';
}
$astat = array("Not Yet Started","On-going","Closed");
?>

<!-- Dashboard Styling -->
<style>
.dashboard-container {
  text-align: center;
  margin-top: 20px;
}

.dashboard-academic {
  background: transparent!important;
  backdrop-filter: blur(10px);    
  -webkit-backdrop-filter: blur(10px);
  padding: 30px;
  border-radius: 15px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
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
  color:white;
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
  background: transparent!important;
  backdrop-filter: blur(10px);    
  -webkit-backdrop-filter: blur(10px);
  border-radius: 20px;
  padding: 25px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
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
p{
  color:white;
}
</style>

<div class="dashboard-container">
  <!-- Academic Year Section -->
  <div class="dashboard-academic">
    <h2>Academic Year: <?php echo $_SESSION['academic']['year'].' '.(ordinal_suffix1($_SESSION['academic']['semester'])) ?> Semester</h2>
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
</div>
