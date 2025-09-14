<aside class="main-sidebar elevation-4">
  <div class="dropdown">
    <a href="./" class="brand-link d-flex justify-content-center align-items-center">
      <img src="eljmcnobglogo.png" 
           alt="School Logo" 
           class="brand-image img-circle elevation-3"
           style="opacity: .9; width:50px; height: 110px;">
    </a>
  </div>

  <div class="sidebar">
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column nav-flat" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item dropdown">
          <a href="./" class="nav-link nav-home">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
          </a>
        </li>
        <li class="nav-item dropdown">
          <a href="./index.php?page=evaluate" class="nav-link nav-evaluate">
            <i class="nav-icon fas fa-th-list"></i>
            <p>Evaluate</p>
          </a>
        </li> 
      </ul>
    </nav>
  </div>
</aside>

<style>
.main-sidebar .nav-link {
  background:transparent !important;
  color: #fff !important; 
}

.main-sidebar .nav-link.active {
  background: rgba(255, 255, 255, 0.2) !important;
  border-radius: 8px;
}

.main-sidebar {
  background: #212842 !important;
  box-shadow: none !important;
}

.brand-link {
  height: auto !important;
  display: flex !important;
  justify-content: center;
  align-items: center;
  padding: 12.8px 0 !important;
  box-shadow: none !important;
  background: #212842 !important;
  border-bottom: 1px solid #fff !important;
}

.brand-link .brand-image {
  width: 50px !important;
  height: 120px !important;
  object-fit: contain !important;
  transition: none !important;
   box-shadow: none !important;
  background: #212842 !important;
}
.sidebar-mini.sidebar-collapse .brand-link .brand-image {
  width: 50px !important;
  height: 120px !important;
  margin: 0 auto !important;
  background: #212842 !important;
}
</style>



  <script>
  	$(document).ready(function(){
      var page = '<?php echo isset($_GET['page']) ? $_GET['page'] : 'home' ?>';
  		var s = '<?php echo isset($_GET['s']) ? $_GET['s'] : '' ?>';
      if(s!='')
        page = page+'_'+s;
  		if($('.nav-link.nav-'+page).length > 0){
             $('.nav-link.nav-'+page).addClass('active')
  			if($('.nav-link.nav-'+page).hasClass('tree-item') == true){
            $('.nav-link.nav-'+page).closest('.nav-treeview').siblings('a').addClass('active')
  				$('.nav-link.nav-'+page).closest('.nav-treeview').parent().addClass('menu-open')
  			}
        if($('.nav-link.nav-'+page).hasClass('nav-is-tree') == true){
          $('.nav-link.nav-'+page).parent().addClass('menu-open')
        }

  		}
     
  	})
  </script>