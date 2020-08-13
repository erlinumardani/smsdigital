<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{title}</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="referrer" content="origin">

  <!-- START: Template CSS-->
  <link rel="stylesheet" href="{base_url}assets/pick/dist/vendors/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="{base_url}assets/pick/dist/vendors/jquery-ui/jquery-ui.min.css">
  <link rel="stylesheet" href="{base_url}assets/pick/dist/vendors/jquery-ui/jquery-ui.theme.min.css">
  <link rel="stylesheet" href="{base_url}assets/pick/dist/vendors/simple-line-icons/css/simple-line-icons.css">        
  <link rel="stylesheet" href="{base_url}assets/pick/dist/vendors/flags-icon/css/flag-icon.min.css">         
  <!-- END Template CSS-->

  <!-- START: Page CSS-->   
  <link rel="stylesheet" href="{base_url}assets/pick/dist/vendors/morris/morris.css"> 
  <link rel="stylesheet" href="{base_url}assets/pick/dist/vendors/weather-icons/css/pe-icon-set-weather.min.css"> 
  <link rel="stylesheet" href="{base_url}assets/pick/dist/vendors/chartjs/Chart.min.css"> 
  <link rel="stylesheet" href="{base_url}assets/pick/dist/vendors/starrr/starrr.css"> 
  <link rel="stylesheet" href="{base_url}assets/pick/dist/vendors/fontawesome/css/all.min.css">
  <link rel="stylesheet" href="{base_url}assets/pick/dist/vendors/ionicons/css/ionicons.min.css"> 
  <link rel="stylesheet" href="{base_url}assets/pick/dist/vendors/jquery-jvectormap/jquery-jvectormap-2.0.3.css">
  <link rel="stylesheet" href="{base_url}assets/pick/dist/vendors/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <link rel="stylesheet" href="{base_url}assets/pick/dist/vendors/jquery-datetimepicker/jquery.datetimepicker.min.css">
  <!-- END: Page CSS-->

  <!-- START: Custom CSS-->
  <link rel="stylesheet" href="{base_url}assets/pick/dist/vendors/select2/css/select2.min.css"/>
  <link rel="stylesheet" href="{base_url}assets/pick/dist/vendors/select2/css/select2-bootstrap.min.css"/>
  <link rel="stylesheet" href="{base_url}assets/pick/dist/css/main.css">
  <!-- END: Custom CSS-->
  <style  type="text/css">
    .compact-menu:not(.horizontal-menu) #header-fix .logo-bar {
      width: 250px;
   }
  </style>

    
  <!-- START: Template JS-->
  <script src="{base_url}assets/pick/dist/vendors/jquery/jquery-3.3.1.min.js"></script>
  <script src="{base_url}assets/pick/dist/vendors/jquery-ui/jquery-ui.min.js"></script>
  <script src="{base_url}assets/pick/dist/vendors/moment/moment.js"></script>
  <script src="{base_url}assets/pick/dist/vendors/bootstrap/js/bootstrap.bundle.min.js"></script>    
  <script src="{base_url}assets/pick/dist/vendors/slimscroll/jquery.slimscroll.min.js"></script>
  <!-- END: Template JS-->

  <!-- START: APP JS-->
  <!-- <script src="{base_url}assets/pick/dist/js/app.js"></script> -->
  <script src="{base_url}assets/pick/dist/vendors/select2/js/select2.full.min.js"></script>
  <script src="{base_url}assets/pick/dist/vendors/jquery-validation/jquery.validate.min.js"></script>
  <script src="{base_url}assets/pick/dist/vendors/jquery-validation/additional-methods.min.js"></script>
  <script src="{base_url}assets/pick/dist/vendors/sweetalert2/sweetalert2.min.js"></script>
  <script src="{base_url}assets/js/jquery.cookie.js"></script>
  <script src="{base_url}assets/js/jquery.PrintArea.js"></script> 
  <script src="{base_url}assets/pick/dist/vendors/jquery-datetimepicker/jquery.datetimepicker.full.js"></script> 
  <!-- END: APP JS-->



  <script>

    $(window).on("load", function () {
        // Animate loader off screen
        $(".se-pre-con").fadeOut("slow");
        ;
    });

    $(document).ready(function() {

        $(".select2").select2();
        $('.datepicker').datepicker({dateFormat:'yy-mm-dd'});
        $('.datetimepicker').datetimepicker({format:'Y-m-d H:i:s'});

        $('.sidebarCollapse').on('click', function () {
            $('body').toggleClass('compact-menu');
            $('.sidebar').toggleClass('active');
        });

        $('.mobilesearch').on('click', function () {
            $('.search-form').toggleClass('d-none');

        });
    });


  </script>

  {extrascript}
</head>
<body id="main-container" class="default">

<!-- START: Pre Loader-->
<div class="se-pre-con">
    <div class="loader"></div>
</div>
<!-- END: Pre Loader-->

<!-- START: Header-->
<div id="header-fix" class="header fixed-top">
  <div class="site-width">
      <nav class="navbar navbar-expand-lg  p-0">
          <div class="navbar-header  h-100 h4 mb-0 align-self-center logo-bar text-left">  
              <a href="#" class="horizontal-logo text-left">
                  <svg height="20pt" preserveAspectRatio="xMidYMid meet" viewBox="0 0 512 512" width="20pt" xmlns="http://www.w3.org/2000/svg">
                  <g transform="matrix(.1 0 0 -.1 0 512)" fill="#1e3d73">
                  </g>
                  </svg> <span class="h4 font-weight-bold align-self-center mb-0 ml-auto"><img src="{base_url}{logo}" height="100%"></span>              
              </a>                   
          </div>
          <div class="navbar-header h4 mb-0 text-center h-100 collapse-menu-bar">
              <a href="#" class="sidebarCollapse" id="collapse"><i class="icon-menu"></i></a>
          </div>
          <div class="navbar-right ml-auto h-100">
              <ul class="ml-auto p-0 m-0 list-unstyled d-flex top-icon h-100">
                  <li class="dropdown user-profile align-self-center d-inline-block">
                      <a href="#" class="nav-link py-0" data-toggle="dropdown" aria-expanded="false"> 
                          <div class="media">                                   
                              <img src="{base_url}assets/images/user.jpg" alt="" class="d-flex img-fluid rounded-circle" width="29">
                          </div>
                      </a>

                      <div class="dropdown-menu border dropdown-menu-right p-0">
                          <a href="{base_url}profile/data/update" class="dropdown-item px-2 align-self-center d-flex">
                              <span class="icon-user mr-2 h6 mb-0"></span> My Profile</a>
                          <div class="dropdown-divider"></div>
                          <a href="{base_url}auth/logout" class="dropdown-item px-2 text-danger align-self-center d-flex">
                              <span class="icon-logout mr-2 h6  mb-0"></span> Sign Out</a>
                      </div>

                  </li>

              </ul>
          </div>
      </nav>
  </div>
</div>
<!-- END: Header-->

<!-- START: Main Menu-->
<div class="sidebar">
  <div class="site-width">
      <!-- START: Menu-->
      <ul id="side-menu menu" class="sidebar-menu">
          
        {menus}
           
      </ul>
  </div>
</div>
<!-- END: Main Menu-->

<!-- START: Main Content-->
<main>
  <div class="container-fluid site-width" style="padding-top:25px;">
      <!-- START: Breadcrumbs-->
      <!-- <div class="row">
          <div class="col-12  align-self-center">
              <div class="sub-header mt-3 py-3 align-self-center d-sm-flex w-100 rounded">
                  <div class="w-sm-100 mr-auto"><h4 class="mb-0">{content_title}</h4> <p>Simple Admin Panel</p></div>
              </div>
          </div>
      </div> -->
      <!-- END: Breadcrumbs-->
      <!-- Main content -->
      {content}
      <!-- /.content -->         
  </div>
</main>
<!-- END: Content-->
<!-- START: Footer-->
<footer class="site-footer">
  2020 &copy;
</footer>
<!-- END: Footer-->


<!-- START: Back to top-->
<a href="#" class="scrollup text-center"> 
  <i class="icon-arrow-up"></i>
</a>
<!-- END: Back to top-->
<script src="{base_url}assets/js/custom_validation.js"></script>
</body>
</html>
