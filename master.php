<?php
// include database and object files
include_once 'api/config/database.php';
include_once 'api/objects/inventory_categories.php';
include_once 'api/objects/inventory_types.php';
include_once 'api/objects/projects_types.php';

// get database connection
$database = new Database();
$db = $database->getConnection();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title><?php echo $title . " | WebIMS"; ?></title>
  <!-- pace-progress -->
  <link rel="stylesheet" href="<?php echo $ROOT; ?>plugins/pace-progress/themes/yellow/pace-theme-minimal.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo $ROOT; ?>plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="<?php echo $ROOT; ?>plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="<?php echo $ROOT; ?>plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <!-- Toastr -->
  <link rel="stylesheet" href="<?php echo $ROOT; ?>plugins/toastr/toastr.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="<?php echo $ROOT; ?>plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo $ROOT; ?>dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <!-- Favicon via realfavicongenerator.net -->
  <link rel="apple-touch-icon" sizes="180x180" href="<?php echo $ROOT; ?>dist/img/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="<?php echo $ROOT; ?>dist/img/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $ROOT; ?>dist/img/favicon-16x16.png">
  <link rel="manifest" href="<?php echo $ROOT; ?>dist/img/site.webmanifest">
  <link rel="mask-icon" href="<?php echo $ROOT; ?>dist/img/safari-pinned-tab.svg" color="#ffc107">
  <link rel="shortcut icon" href="<?php echo $ROOT; ?>dist/img/favicon.ico">
  <meta name="apple-mobile-web-app-title" content="WebIMS">
  <meta name="application-name" content="WebIMS">
  <meta name="theme-color" content="#ffffff">
  
</head>
<!--
BODY TAG OPTIONS:
=================
Apply one or more of the following classes to to the body tag
to get the desired effect
|---------------------------------------------------------|
|LAYOUT OPTIONS | sidebar-collapse                        |
|               | sidebar-mini                            |
|---------------------------------------------------------|
-->
<body class="hold-transition sidebar-mini layout-navbar-fixed layout-fixed accent-orange">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-light">

    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="https://github.com/zammitjohn/WebIMS" class="nav-link" target="_blank" rel="noreferrer"><i class="fab fa-github"></i></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <a onclick="userLogout()" class="nav-link" onmouseover="" style="cursor: pointer;">
        <i class="fas fa-sign-out-alt"></i>
      </a>
    </ul>

  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar elevation-4 sidebar-light-warning">
    <!-- Brand Logo -->
    <a href="<?php echo $ROOT; ?>" class="brand-link navbar-warning">
      <img src="<?php echo $ROOT; ?>dist/img/logo.svg" class="brand-image img-circle elevation-1" style="opacity: .9">
      <span class="brand-text font-weight-light"><b>WebIMS</b></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="<?php echo $ROOT; ?>dist/img/generic-user.png" class="img-circle elevation-1" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">
            <?php
              if (isset($_COOKIE['UserSession'])) {
                echo json_decode(base64_decode($_COOKIE['UserSession'])) -> {'FullName'};
              } else {
                echo "Not logged in";
              }
            ?>
          </a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="<?php echo $ROOT; ?>" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-book"></i>
              <p>
                Inventory
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?php echo $ROOT; ?>inventory/create" class="nav-link">
                  <i class="fas fa-plus nav-icon"></i>
                  <p>Add item</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo $ROOT; ?>inventory/register" class="nav-link">
                  <i class="fas fa-edit nav-icon"></i>
                  <p>Register item</p>
                </a>
              </li> 
              <li class="nav-item">
                <a href="<?php echo $ROOT; ?>inventory" class="nav-link">
                  <i class="fas fa-circle nav-icon"></i>
                  <p>All items</p>
                </a>
              </li>
              <?php
              ## Building inventory sidebar tree
              $inventory_category_object = new Inventory_Categories($db);
              $inventory_types_object = new Inventory_Types($db);

              $inventory_category_stmt = $inventory_category_object->read();
              if ($inventory_category_stmt != false){
                while ($inventory_category_row = $inventory_category_stmt->fetch(PDO::FETCH_ASSOC)){ // first loop categories
                  extract($inventory_category_row);
                  echo '<li class="nav-item has-treeview"><a href="#" class="nav-link"><i class="far fa-dot-circle nav-icon"></i><p>' . $name .  '<i class="right fas fa-angle-left"></i></p></a><ul class="nav nav-treeview"><li class="nav-item"><a href="' . $ROOT . 'inventory/category?id=' . $id . '" class="nav-link"><i class="fas fa-circle nav-icon"></i><p>All items</p></a></li></ul>';
                  
                  $inventory_types_object->category = $id;
                  $inventory_types_stmt = $inventory_types_object->read();
                  while ($inventory_types_row = $inventory_types_stmt->fetch(PDO::FETCH_ASSOC)){ // ...then loop types
                    extract($inventory_types_row);
                    $type_name = $name;
                    $type_id = $id;
                    echo '<ul class="nav nav-treeview"><li class="nav-item"><a href="' . $ROOT . 'inventory/type?id=' . $type_id . '" class="nav-link"><i class="far fa-circle nav-icon"></i><p>' . $type_name . '</p></a></li></ul>';
                  }
                  echo '</li>';
                }
              }
              ?>
            </ul>
          </li>

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-tools"></i>
              <p>
                Fault Reports
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?php echo $ROOT; ?>reports/create" class="nav-link">
                  <i class="fas fa-plus nav-icon"></i>
                  <p>New report</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo $ROOT; ?>reports" class="nav-link">
                  <i class="fas fa-circle nav-icon"></i>
                  <p>All reports</p>
                </a>
              </li>
            </ul>
          </li>          

          <li class="nav-item has-treeview">
            <?php
            ## Building projects sidebar tree
            $projects_types_object = new Projects_Types($db);
            $projects_types_stmt = $projects_types_object->read();

            if ($projects_types_stmt != false){
              $projects_types_row_count = 1;
              while ($projects_types_row = $projects_types_stmt->fetch(PDO::FETCH_ASSOC)){
                if($projects_types_row_count == 1) {
                  echo '<li class="nav-header">PROJECTS</li>';
                } 
                extract($projects_types_row);
                echo '<li class="nav-item"> <a href="' . $ROOT . 'projects?id=' . $id . '" class="nav-link"><i class="far fa-circle nav-icon"></i><p>' . $name . '</p></a></li>';
                ++$projects_types_row_count;
              }
            }
            ?>
          </li>

        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Main content -->
    
      <!-- page content ->
      <?php echo $content; ?>
      <!-- /.content -->

  </div>
  <!-- /.content-wrapper -->

  <!-- Login Modal -->
	<div class="modal fade" data-backdrop="static" data-keyboard="false" id="modal-login" role="dialog" style="overflow-y: hidden" tabindex="-1">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
        <!-- form start -->
				<form id="loginForm" name="loginForm" role="form">
					<div class="modal-body">
						<p class="login-box-msg">Log in using your corporate account</p>
						<div class="form-group">
							<input class="form-control" id="username" name="username" placeholder="username or email" type="username">
						</div>
						<div class="form-group">
							<input autocomplete="on" class="form-control" id="password" name="password" placeholder="password" type="password">
						</div>
						<div class="row">
							<p class="login-box-msg"><small><i class="fas fa-exclamation-triangle text-warning"></i> <b>Your credentials are not stored on WebIMS.</b> Credentials are verified using <a href="https://en.wikipedia.org/wiki/Lightweight_Directory_Access_Protocol" rel="noreferrer" target="_blank">LDAP</a> authentication.</small></p>
						</div>
					</div>
					<div class="card-footer">
						<div class="row">
							<div class="col-8">
								<input id="remember_me_checkbox" name="remember_me_checkbox" type="checkbox"> <label for="remember_me_checkbox">Remember Me</label>
							</div>
              <!-- /.col -->
							<div class="col-4">
								<button class="btn btn-default btn-block" type="submit">Log in</button>
							</div>
              <!-- /.col -->
						</div>
					</div>
				</form>
        <!-- /.form -->
			</div>
		</div>
	</div>
  <!-- /.login-modal -->

  <!-- Main Footer -->
  <footer class="main-footer">
  &copy; <?php echo date('Y'); ?> <a href="https://zammitjohn.com" target="_blank" rel="noreferrer"><strong>John Zammit</strong></a>.
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 2.2.0
    </div>
  </footer>
  
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="<?php echo $ROOT; ?>plugins/jquery/jquery.min.js"></script>
<!-- jquery-validation -->
<script src="<?php echo $ROOT; ?>plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="<?php echo $ROOT; ?>plugins/jquery-validation/additional-methods.min.js"></script>
<!-- Bootstrap 4 -->
<script src="<?php echo $ROOT; ?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo $ROOT; ?>dist/js/adminlte.min.js"></script>
<!-- pace-progress -->
<script src="<?php echo $ROOT; ?>plugins/pace-progress/pace.min.js"></script>
<!-- DataTables -->
<script src="<?php echo $ROOT; ?>plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo $ROOT; ?>plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo $ROOT; ?>plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?php echo $ROOT; ?>plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<!-- Toastr -->
<script src="<?php echo $ROOT; ?>plugins/toastr/toastr.min.js"></script>
<!-- Moment -->
<script src="<?php echo $ROOT; ?>plugins/moment/moment.min.js"></script>
<!-- overlayScrollbars -->
<script src="<?php echo $ROOT; ?>plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
</body>
</html>

<script>
// service worker for PWA
if ('serviceWorker' in navigator) {
  navigator.serviceWorker.register('<?php echo $ROOT; ?>service-worker.js');
}

$(document).ready(function validate() {
  // Validate session characteristics
  $.ajax({
  type: "GET",
  cache: false,
  url: '<?php echo $ROOT; ?>api/users/validate_session',
  dataType: 'json',
    success: function(data) {
      if (data['status'] == false) {
        $(".card").addClass("collapsed-card"); // hide card content
        $('#modal-login').modal('toggle'); // toggle modal login
      } else {
        setTimeout(validate, 10000); // session validate every 10 seconds and upon page refresh

        // show/hide dashboard user_permission_alert accordingly
        if ( (data['canUpdate'] == false) || (data['canCreate'] == false) || (data['canImport'] == false) || (data['canDelete'] == false) ) {
          $("#user_permission_alert").show();
        } else {
          $("#user_permission_alert").hide();
        }
    
        // ...and disable/hide buttons accordingly
        if (data['canUpdate'] == false) {
          $(".button_action_update").prop("disabled",true);
        }
        if (data['canCreate'] == false) {
          $(".button_action_create").prop("disabled",true);
        }
        if (data['canImport'] == false) {
          $(".button_action_import").hide(); // card tools
        }
        if (data['canDelete'] == false) {
          $(".button_action_delete").prop("disabled",true);
          $(".button_action_delete").hide(); // card tools
        }
      }
    },
    error: function(data) {
      alert("Failed to validate session!");
      location.reload();
    }
  });

  // login form validation
  $.validator.setDefaults({
    submitHandler: function () {
      $.ajax({
        type: "POST",
        cache: false,
        url: '<?php echo $ROOT; ?>api/users/login',
        dataType: 'json',
        data: {
          username: $("#username").val(),
          password: $("#password").val(),
          remember: $("#remember_me_checkbox").is(':checked')  ? 1 : 0
        },
        error: function(data) {
          alert(data['message']);
        },
        success: function(data) {
          if (data['status'] == true) {
            location.reload();
          } else {
            alert(data['message']);
          }
        }
      });
    }
  });
  $('#loginForm').validate({
    rules: {
      username: {
        required: true
      },
      password: {
        required: true,
      }
    },
    messages: {
      username: {
        required: "Please enter a username or email",
      },
      password: {
        required: "Please provide a password",
      }
    },
    errorElement: 'span',
    errorPlacement: function (error, element) {
      error.addClass('invalid-feedback');
      element.closest('.form-group').append(error);
    },
    highlight: function (element, errorClass, validClass) {
      $(element).addClass('is-invalid');
    },
    unhighlight: function (element, errorClass, validClass) {
      $(element).removeClass('is-invalid');
    }
  });

});
</script>

<!-- Log out user -->
<script>
function userLogout(){
  $.ajax({
    type: "POST",
    url: '<?php echo $ROOT; ?>api/users/logout',
    dataType: 'json',
    error: function(data) {
      alert(data.responseText);
    },
    success: function(data) {
      if (data['status'] == true) {
        location.reload();
      } else {
        alert(data['message']);
      }
    }
  });
}
</script>