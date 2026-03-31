<style>
.user-img {
    border-radius: 50%;
    height: 25px;
    width: 25px;
    object-fit: cover;
}

.main-header.navbar,
.main-header.navbar a,
.main-header.navbar .nav-link,
.main-header.navbar i,
.main-header.navbar span,
.main-header.navbar b {
  color: white !important;
}

.main-header .dropdown-menu {
  background-color: #6d7694ff; 
  border: none;
}
.main-header .dropdown-menu a {
  color: black !important;
}
.main-header .dropdown-menu a:hover {
  background-color: #6d7694ff; 
  
}
.main-header{
  background: #6d7694ff;
  color: white;

}

.main-header .navbar-nav {
  align-items: center;
}

.main-header .user-name {
  max-width: 145px;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
  display: inline-block;
  vertical-align: middle;
}

@media (max-width: 576px) {
  .main-header .role-label {
    display: none;
  }

  .main-header .user-name {
    max-width: 90px;
  }
}




</style>

<!-- Navbar -->
<nav class="main-header navbar navbar-expand-sm" style="background-color: #6d7694ff;" >
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <?php if(isset($_SESSION['login_id'])): ?>
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button">
        <i class="fas fa-bars"></i>
      </a>
    </li>
    <?php endif; ?>
    <li>
      <a class="nav-link text-white" href="./" role="button">
        <?php if($_SESSION['login_type'] == 'Administrator'): ?>
          <b class="role-label">Administrator</b>
        <?php endif; ?>
      </a>
    </li>
  </ul>

  <!-- Right navbar -->
  <ul class="navbar-nav ml-auto">
    <li class="nav-item">
      <a class="nav-link" data-widget="fullscreen" href="#" role="button">
        <i class="fas fa-expand-arrows-alt"></i>
      </a>
    </li>
    <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" href="javascript:void(0)">
        <span>
          <div class="d-flex align-items-center">
            <span><img src="assets/uploads/<?php echo $_SESSION['login_avatar'] ?>" alt="" class="user-img border"></span>
            <?php if($_SESSION['login_type'] == 'Administrator'): ?>
              <span class="user-name"><b><?php echo ucwords($_SESSION['login_firstname']) ?></b></span>
            <?php endif; ?>
            <span class="fa fa-angle-down ml-2"></span>
          </div>
        </span>
      </a>
      <div class="dropdown-menu dropdown-menu-right">
        <a class="dropdown-item" href="javascript:void(0)" id="manage_account">
          <i class="fa fa-cog"></i> Manage Account
        </a>
        <a class="dropdown-item" href="ajax.php?action=logout">
          <i class="fa fa-power-off"></i> Logout
        </a>
      </div>
    </li>
  </ul>
</nav>

<script>
$('#manage_account').click(function(){
  uni_modal('Manage Account','manage_user.php?id=<?php echo $_SESSION['login_id'] ?>')
})


</script>
