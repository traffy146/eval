<?php include('db_connect.php');
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
$astat = array("Not Yet Started","Started","Closed");
?>

<!-- Dashboard Styling (reuse from admin) -->
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
  color: black;
}

.dashboard-academic h4 {
  font-size: 1.1rem;
  color: black;
  margin-top: 10px;
}

.dashboard-academic p {
  margin-top: 5px;
  font-size: 1rem;
  color: ;
}

</style>

<div class="dashboard-container">
  <!-- Academic Year Section -->
  <div class="dashboard-academic">
    <h2>Academic Year: <?php echo $_SESSION['academic']['year'].' '.(ordinal_suffix1($_SESSION['academic']['semester'])) ?> Semester</h2>
    <h4>Evaluation Status: <?php echo $astat[$_SESSION['academic']['status']] ?></h4>
    <p><small>Welcome <?php echo $_SESSION['login_name'] ?>!</small></p>
  </div>
</div>
