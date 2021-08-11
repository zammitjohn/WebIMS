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
      <title><?php echo $title . " | WebIMS"; ?></title>
      <!-- Google Font: Source Sans Pro -->
      <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
      <!-- Font Awesome -->
      <link rel="stylesheet" href="<?php echo $ROOT; ?>plugins/fontawesome-free/css/all.min.css">
      <!-- Theme style -->
      <link rel="stylesheet" href="<?php echo $ROOT; ?>dist/css/adminlte.min.css">
      <!-- overlayScrollbars -->
      <link rel="stylesheet" href="<?php echo $ROOT; ?>plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
      <!-- Toastr -->
      <link rel="stylesheet" href="<?php echo $ROOT; ?>plugins/toastr/toastr.min.css">
      <!-- DataTables -->
      <link rel="stylesheet" href="<?php echo $ROOT; ?>plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
      <link rel="stylesheet" href="<?php echo $ROOT; ?>plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
      <link rel="stylesheet" href="<?php echo $ROOT; ?>plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
      <!-- BS-Stepper -->
      <link rel="stylesheet" href="<?php echo $ROOT; ?>plugins/bs-stepper/css/bs-stepper.min.css">
      <!-- Select2 -->
      <link rel="stylesheet" href="<?php echo $ROOT; ?>plugins/select2/css/select2.min.css">
      <link rel="stylesheet" href="<?php echo $ROOT; ?>plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">     
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
   <body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed layout-navbar-fixed accent-warning">
      <div class="wrapper">
         <!-- Preloader -->
         <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__wobble" src="<?php echo $ROOT; ?>dist/img/logo.svg" alt="Logo" height="60" width="60">
         </div>
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
               <!-- Navbar Search -->
               <li class="nav-item">
                  <a onclick="userLogout()" class="nav-link" onmouseover="" style="cursor: pointer;">
                  <i class="fas fa-sign-out-alt"></i>
                  </a>
               </li>
            </ul>
         </nav>
         <!-- /.navbar -->
         <!-- Main Sidebar Container -->
         <aside class="main-sidebar sidebar-dark-warning elevation-4">
            <!-- Brand Logo -->
            <a href="<?php echo $ROOT; ?>" class="brand-link">
            <img src="<?php echo $ROOT; ?>dist/img/logo.svg" alt="Logo" class="brand-image img-circle elevation-1" style="opacity: .9">
            <span class="brand-text font-weight-light">WebIMS</span>
            </a>
            <!-- Sidebar -->
            <div class="sidebar">
               <!-- Sidebar user panel -->
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
               <!-- SidebarSearch Form -->
               <div class="form-inline">
                  <div class="input-group" data-widget="sidebar-search">
                     <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                     <div class="input-group-append">
                        <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                        </button>
                     </div>
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
                               echo '<li class="nav-item"> <a href="' . $ROOT . 'projects?id=' . $id . '" class="nav-link"><i class="far fa-circle nav-icon text-warning"></i><p>' . $name . '</p></a></li>';
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
                        <p class="login-box-msg"><img src="<?php echo $ROOT; ?>dist/img/logo.svg" alt="Logo" class="img-circle elevation-1" height="70" width="70"></p>
                        <p class="login-box-msg">Log in with your corporate account</p>
                        <div class="form-group">
                           <input class="form-control" id="username" name="username" placeholder="username or email" type="username">
                        </div>
                        <div class="form-group">
                           <input autocomplete="on" class="form-control" id="password" name="password" placeholder="password" type="password">
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
         <footer class="main-footer">
            &copy; <?php echo date('Y'); ?> <a href="https://zammitjohn.com" target="_blank" rel="noreferrer"><strong>John Zammit</strong></a>.
            All rights reserved.
            <div class="float-right d-none d-sm-inline-block">
               <b>Version</b> 2.8.2
            </div>
         </footer>
      </div>
      <!-- ./wrapper -->
      <!-- jQuery -->
      <script src="<?php echo $ROOT; ?>plugins/jquery/jquery.min.js"></script>
      <!-- jQuery UI 1.11.4 -->
      <script src="<?php echo $ROOT; ?>plugins/jquery-ui/jquery-ui.min.js"></script>
      <!-- Page specific script -->
      <script src="<?php echo $ROOT; ?>dist/js/main.js" id="mainScript" root-url="<?php echo $ROOT; ?>"></script>
      <!-- Bootstrap 4 -->
      <script src="<?php echo $ROOT; ?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
      <!-- overlayScrollbars -->
      <script src="<?php echo $ROOT; ?>plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
      <!-- AdminLTE App -->
      <script src="<?php echo $ROOT; ?>dist/js/adminlte.js"></script>
      <!-- jquery-validation -->
      <script src="<?php echo $ROOT; ?>plugins/jquery-validation/jquery.validate.min.js"></script>
      <script src="<?php echo $ROOT; ?>plugins/jquery-validation/additional-methods.min.js"></script>
      <!-- DataTables  & Plugins -->
      <script src="<?php echo $ROOT; ?>plugins/datatables/jquery.dataTables.min.js"></script>
      <script src="<?php echo $ROOT; ?>plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
      <script src="<?php echo $ROOT; ?>plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
      <script src="<?php echo $ROOT; ?>plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
      <script src="<?php echo $ROOT; ?>plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
      <script src="<?php echo $ROOT; ?>plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
      <script src="<?php echo $ROOT; ?>plugins/jszip/jszip.min.js"></script>
      <script src="<?php echo $ROOT; ?>plugins/pdfmake/pdfmake.min.js"></script>
      <script src="<?php echo $ROOT; ?>plugins/pdfmake/vfs_fonts.js"></script>
      <script src="<?php echo $ROOT; ?>plugins/datatables-buttons/js/buttons.html5.min.js"></script>
      <script src="<?php echo $ROOT; ?>plugins/datatables-buttons/js/buttons.print.min.js"></script>
      <script src="<?php echo $ROOT; ?>plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
      <!-- Toastr -->
      <script src="<?php echo $ROOT; ?>plugins/toastr/toastr.min.js"></script>
      <!-- Moment -->
      <script src="<?php echo $ROOT; ?>plugins/moment/moment.min.js"></script>
      <!-- BS-Stepper -->
      <script src="<?php echo $ROOT; ?>plugins/bs-stepper/js/bs-stepper.min.js"></script>
      <!-- Select2 -->
      <script src="<?php echo $ROOT; ?>plugins/select2/js/select2.full.min.js"></script>      
   </body>
</html>