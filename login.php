<!DOCTYPE html>
<html lang="en">
<?php
session_start();
include('./db_connect.php');
ob_start();
// if(!isset($_SESSION['system'])){

$system = $conn->query("SELECT * FROM system_settings")->fetch_array();
foreach ($system as $k => $v) {
  $_SESSION['system'][$k] = $v;
}
// }
ob_end_flush();
?>
<?php
if (isset($_SESSION['login_id']))
  header("location:index.php?page=home");

?>
<?php include 'header.php' ?>
<?php include 'header.php' ?>
<style>
  body {
    margin: 0;
    font-family: Arial, Helvetica, sans-serif;
    background: url('loginbg1.jpg') no-repeat center center fixed;
    background-size: cover;
  }

  /* Dark overlay */
  .overlay {
    background: rgba(0, 0, 0, 0.6);
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
  }

  .login-box,
  .card,
  .card-body,
  .login-card-body {
    background: transparent;
    /* transparent white */
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border-radius: 15px;
    border: none;
    /* subtle glass border */
    color: #fff;

  }

  /* Input fields and select dropdown */
  .form-control,
  .custom-select {
    background: rgba(255, 255, 255, 0.1);
    /* transparent glass look */
    border: 1px solid rgba(255, 255, 255, 0.6);
    /* white border */
    color: #fff;
    /* white text */
    border-radius: 8px;
  }

  /* Placeholder text */
  .form-control::placeholder {
    color: rgba(255, 255, 255, 0.7);
  }

  /* On focus (when typing/selected) */
  .form-control:focus,
  .custom-select:focus {
    background: rgba(255, 255, 255, 0.15);
    border: 1px solid #fff;
    /* brighter border on focus */
    color: #fff;
    box-shadow: none;
    /* remove default blue glow */
  }

  .custom-select {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.6);
    color: #fff;
    border-radius: 8px;
  }

  /* Dropdown options */
  .custom-select option {
    background: rgba(0, 0, 0, 0.8);
    /* dark background for dropdown list */
    color: #fff;
    /* white text */
  }

  /* Center container */
  .content {
    position: relative;
    z-index: 2;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    height: 100vh;
    text-align: center;
    color: #fff;
    background-color: transparent;
  }

  .content h1 {
    font-size: 2.5rem;
    font-weight: bold;
    margin-bottom: 15px;
  }

  .content p {
    font-size: 1rem;
    color: #ccc;
    margin-bottom: 40px;
    background-color: transparent;
  }

  /* Login box styling */
  .login-box {

    color: #000;
    padding: 30px;
    border-radius: 15px;
    width: 350px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5);
    background-color: transparent;
  }

  .login-box h2 {
    margin-bottom: 20px;
    font-size: 1.2rem;
    color: #fff;
  }

  .btn-primary {
    border-radius: 25px;
    padding: 10px 0;
    font-weight: bold;
  }
</style>

<body>
  <div class="overlay"></div>
  <div class="content">
    <img src="guidance.png" alt="School Logo" width="150" height="150">
    <h1>Eduardo L. Joson Memorial College</h1>
    <p>Excellence • Leadership • Justice</p>

    <div class="login-box">
      <h2><b> <?php echo $_SESSION['system']['name'] ?> </b></h2>

      <div class="login-logo">
        <a href="#" class="text-white"></a>
      </div>

      <!-- /.login-logo -->
      <div class="card">
        <div class="card-body login-card-body">
          <form action="" id="login-form">
            <div class="input-group mb-3">
              <input type="text" class="form-control" name="username" required placeholder="Username">
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-user"></span>
                </div>
              </div>
            </div>
            <div class="input-group mb-3">
              <input type="password" class="form-control" name="password" required placeholder="Password">
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-lock"></span>
                </div>
              </div>
            </div>
            <div class="form-group mb-3">
              <label for="">Login As</label>
              <select name="login" id="" class="custom-select custom-select-sm">
                <option value="3">Student</option>
                <option value="2">Faculty</option>
                <option value="1">Admin</option>
              </select>
            </div>
            <div class="row">
              <div class="col-8">
                <div class="icheck-primary">
                  <input type="checkbox" id="remember">
                  <label for="remember">
                    Remember Me
                  </label>
                </div>
              </div>
              <!-- /.col -->
              <div class="col-4">
                <button type="submit" class="btn btn-primary btn-block">Sign In</button>
              </div>
              <!-- /.col -->
            </div>
          </form>
        </div>
        <!-- /.login-card-body -->
      </div>
    </div>
    <!-- /.login-box -->
    <script>
      $(document).ready(function () {
        $('#login-form').submit(function (e) {
          e.preventDefault()
          start_load()
          if ($(this).find('.alert-danger').length > 0)
            $(this).find('.alert-danger').remove();
          $.ajax({
            url: 'ajax.php?action=login',
            method: 'POST',
            data: $(this).serialize(),
            error: err => {
              console.log(err)
              end_load();

            },
            success: function (resp) {
              if (resp == 1) {
                location.href = 'index.php?page=home';
              } else {
                $('#login-form').prepend('<div class="alert alert-danger">Username or password is incorrect.</div>')
                end_load();
              }
            }
          })
        })
      })
    </script>
    <?php include 'footer.php' ?>

</body>

</html>