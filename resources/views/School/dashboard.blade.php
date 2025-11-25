@include('components.header') 
  <body>
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
      <div class="lds-ripple">
        <div class="lds-pos"></div>
        <div class="lds-pos"></div>
      </div>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div
      id="main-wrapper"
      data-layout="vertical"
      data-navbarbg="skin5"
      data-sidebartype="full"
      data-sidebar-position="absolute"
      data-header-position="absolute"
      data-boxed-layout="full"
    >
      <!-- ============================================================== -->
      <!-- Topbar header - style you can find in pages.scss -->
      <!-- ============================================================== -->
      @include('components.nav') 
      <!-- ============================================================== -->
      <!-- End Topbar header -->
      <!-- ============================================================== -->
      <!-- ============================================================== -->
      <!-- Left Sidebar - style you can find in sidebar.scss  -->
      <!-- ============================================================== -->
      @include('components.school-nav') 
      <!-- ============================================================== -->
      <!-- End Left Sidebar - style you can find in sidebar.scss  -->
      <!-- ============================================================== -->
      <!-- ============================================================== -->
      <!-- Page wrapper  -->
      <!-- ============================================================== -->
      <div class="page-wrapper">
        <!-- ============================================================== -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <div class="page-breadcrumb">
          <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
              <h4 class="page-title">School Admin Dashboard</h4>
              <div class="ms-auto text-end">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                      SpftPen
                    </li>
                  </ol>
                </nav>
              </div>
            </div>
          </div>
        </div>
        <!-- ============================================================== -->
        <!-- End Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Container fluid  -->
        <!-- ============================================================== -->
        <div class="container-fluid">
          <!-- ============================================================== -->
          <!-- Sales Cards  -->
          <!-- ============================================================== -->
          <div class="row">
            <!-- Column -->
            <div class="col-md-6 col-lg-2 col-xlg-3">
              <div class="card card-hover">
                <div class="box bg-cyan text-center">
                  <h1 class="font-light text-white">
                    <i class="mdi mdi-view-dashboard"></i>
                  </h1>
                  <h6 class="text-white">Dashboard</h6>
                </div>
              </div>
            </div>
              <div class="col-md-6 col-lg-2 col-xlg-3">
              <a href="{{ url('school/student') }}">
              <div class="card card-hover">
                <div class="box bg-success text-center">
                  <h1 class="font-light text-white">
                    <i class="mdi mdi-school"></i>
                  </h1>
                  <h6 class="text-white">Students</h6>
                </div>
              </div>
            </a>
            </div>
          <div class="col-md-6 col-lg-2 col-xlg-3">
              <a href="{{ url('school/teacher') }}">
              <div class="card card-hover">
                <div class="box bg-info text-center">
                  <h1 class="font-light text-white">
                   <i class="mdi mdi-account fs-3 mb-1 font-16"></i>
                  </h1>
                  <h6 class="text-white">Teachers</h6>
                </div>
              </div>
            </a>
            </div>
          <div class="col-md-6 col-lg-2 col-xlg-3">
              <a href="{{ url('school/subject') }}">
              <div class="card card-hover">
                <div class="box bg-primary text-center">
                  <h1 class="font-light text-white">
                  <i class="mdi mdi-book-open fs-3 mb-1 font-16"></i>
                  </h1>
                  <h6 class="text-white">Subjects</h6>
                </div>
              </div>
            </a>
            </div>
          <div class="col-md-6 col-lg-2 col-xlg-3">
              <a href="{{ url('school/class') }}">
              <div class="card card-hover">
                <div class="box bg-info text-center">
                  <h1 class="font-light text-white">
                    <i class="mdi mdi-home fs-3 mb-1 font-16"></i>
                  </h1>
                  <h6 class="text-white">Class</h6>
                </div>
              </div>
            </a>
            </div>
          <div class="col-md-6 col-lg-2 col-xlg-3">
              <a href="{{ url('school/department') }}">
              <div class="card card-hover">
                <div class="box bg-warning text-center">
                  <h1 class="font-light text-white">
                  <i class="mdi-lightbulb-outline fs-3 mb-1 font-16"></i>
                  </h1>
                  <h6 class="text-white">Departments</h6>
                </div>
              </div>
            </a>
            </div>
          </div>

          <!-- ============================================================== -->
          <!-- Sales chart -->
          <!-- ============================================================== -->
          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-body">
                  <div class="d-md-flex align-items-center">
                    <div>
                      <h4 class="card-title">Site Analysis</h4>
                      <h5 class="card-subtitle">Overview of Latest Month</h5>
                    </div>
                  </div>
                  <div class="row">
                    <!-- column -->
                    <div class="col-lg-9">
                      <div class="flot-chart">
                        <div
                          class="flot-chart-content"
                          id="flot-line-chart"
                        ></div>
                      </div>
                    </div>
                    <div class="col-lg-3">
                      <div class="row">
                        <div class="col-6">
                          <div class="bg-dark p-10 text-white text-center">
                           <i class="mdi mdi-school fs-3 font-16"></i>
                            <h5 class="mb-0 mt-1">{{ $studentCount }}</h5>
                            <small class="font-light">Total Students</small>
                          </div>
                        </div>
                        <div class="col-6 mt-3">
                          <div class="bg-dark p-10 text-white text-center">
                           <i class="mdi mdi-account fs-3 mb-1 font-16"></i>
                            <h5 class="mb-0 mt-1">{{ $staffCount }}</h5>
                            <small class="font-light">Total Teachers</small>
                          </div>
                        </div>
                        <div class="col-6 mt-3">
                          <div class="bg-dark p-10 text-white text-center">
                            <i class="mdi mdi-trophy fs-3 mb-1 font-16"></i>
                            <h5 class="mb-0 mt-1">{{ $resultCount }}</h5>
                            <small class="font-light">Total Result</small>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- column -->
                  </div>
                </div>
              </div>
            </div>
          </div>
        <!-- ============================================================== -->
        <!-- footer -->
        <!-- ============================================================== -->
        <footer class="footer text-center">
          All Rights Reserved by SoftPen Technologies | Deleoped by Softpen Tech
        </footer>
        <!-- ============================================================== -->
        <!-- End footer -->
        <!-- ============================================================== -->
      </div>
      <!-- ============================================================== -->
      <!-- End Page wrapper  -->
      <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
<script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
<!-- Bootstrap tether Core JavaScript -->
<script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js') }}"></script>
<script src="{{ asset('assets/extra-libs/sparkline/sparkline.js') }}"></script>
<!-- Wave Effects -->
<script src="{{ asset('dist/js/waves.js') }}"></script>
<!-- Menu sidebar -->
<script src="{{ asset('dist/js/sidebarmenu.js') }}"></script>
<!-- Custom JavaScript -->
<script src="{{ asset('dist/js/custom.min.js') }}"></script>
<!-- This page JavaScript -->
<!-- <script src="{{ asset('dist/js/pages/dashboards/dashboard1.js') }}"></script> -->
<!-- Charts js Files -->
<script src="{{ asset('assets/libs/flot/excanvas.js') }}"></script>
<script src="{{ asset('assets/libs/flot/jquery.flot.js') }}"></script>
<script src="{{ asset('assets/libs/flot/jquery.flot.pie.js') }}"></script>
<script src="{{ asset('assets/libs/flot/jquery.flot.time.js') }}"></script>
<script src="{{ asset('assets/libs/flot/jquery.flot.stack.js') }}"></script>
<script src="{{ asset('assets/libs/flot/jquery.flot.crosshair.js') }}"></script>
<script src="{{ asset('assets/libs/flot.tooltip/js/jquery.flot.tooltip.min.js') }}"></script>
<script src="{{ asset('dist/js/pages/chart/chart-page-init.js') }}"></script>

  </body>
</html>
