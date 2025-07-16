@include('components.header') 
    <link
      rel="stylesheet"
      type="text/css"
      href="../assets/extra-libs/multicheck/multicheck.css"
    />
    <link
      href="../assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css"
      rel="stylesheet"
    />
    <link href="../dist/css/style.min.css" rel="stylesheet" />
  <body>
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <!-- <div class="preloader">
      <div class="lds-ripple">
        <div class="lds-pos"></div>
        <div class="lds-pos"></div>
      </div>
    </div> -->
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
      @include('components.staff-nav') 
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
              <h4 class="page-title"></h4>
              <div class="ms-auto text-end">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Softpen</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                      Students
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

          
        @if(session('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

         <div class="card">
        @php
            function getGrade($score) {
                if ($score >= 70) return 'A';
                if ($score >= 60) return 'B';
                if ($score >= 50) return 'C';
                if ($score >= 45) return 'D';
                return 'F';
            }

            $totalScore = 0;
            $subjectCount = is_countable($results) ? count($results) : 0;
        @endphp

    <div class="card mt-4">
    <div class="card-header text-center">
        <h4 class="mb-0">Report Card</h4>
        <small>{{ $student->name }} | Class: {{ $student->class }} | {{ $session }} - {{ $term }}</small>
    </div>

    <div class="card-body">
        <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Subject</th>
                <th>CA1 (30)</th>
                <th>CA2 (30)</th>
                <th>Exam (70)</th>
                <th>Total (100)</th>
                <th>Grade</th>
            </tr>
            </thead>
            <tbody>
            @forelse($results as $index => $result)
                @php
                    $ca1 = $result->ca1 ?? 0;
                    $ca2 = $result->ca2 ?? 0;
                    $exam = $result->exam ?? 0;
                    $total = $ca1 + $ca2 + $exam;
                    $totalScore += $total;
                    $grade = getGrade($total);
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $result->subject->name ?? 'N/A' }}</td>
                    <td>{{ $ca1 }}</td>
                    <td>{{ $ca2 }}</td>
                    <td>{{ $exam }}</td>
                    <td>{{ $total }}</td>
                    <td>{{ $grade }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">No results found.</td>
                </tr>
            @endforelse
            </tbody>

            @if($subjectCount > 0)
            @php $average = round($totalScore / $subjectCount, 2); @endphp
            <tfoot>
                <tr>
                <th colspan="5" class="text-end">Total Score</th>
                <th colspan="2">{{ $totalScore }}</th>
                </tr>
                <tr>
                <th colspan="5" class="text-end">Average Score</th>
                <th colspan="2">{{ $average }}</th>
                </tr>
            </tfoot>
            @endif
        </table>
        </div>
    </div>
    </div>


          <!-- ============================================================== -->
          <!-- End PAge Content -->
          <!-- ============================================================== -->
          <!-- ============================================================== -->
          <!-- Right sidebar -->
          <!-- ============================================================== -->
          <!-- .right-sidebar -->
          <!-- ============================================================== -->
          <!-- End Right sidebar -->
          <!-- ============================================================== -->
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
    <script src="../assets/libs/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="../assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>
    <script src="../assets/extra-libs/sparkline/sparkline.js"></script>
    <!--Wave Effects -->
    <script src="../dist/js/waves.js"></script>
    <!--Menu sidebar -->
    <script src="../dist/js/sidebarmenu.js"></script>
    <!--Custom JavaScript -->
    <script src="../dist/js/custom.min.js"></script>
    <!-- this page js -->
    <script src="../assets/extra-libs/multicheck/datatable-checkbox-init.js"></script>
    <script src="../assets/extra-libs/multicheck/jquery.multicheck.js"></script>
    <script src="../assets/extra-libs/DataTables/datatables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
 <script>
      /****************************************
       *       Basic Table                   *
       ****************************************/
      $("#zero_config").DataTable();
    </script>
    <script type="text/javascript">
      function confirmStatusChange(url) {
          Swal.fire({
              title: 'Are you sure?',
              text: "You are about to delete this schol.",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, delete it!'
          }).then((result) => {
              if (result.isConfirmed) {
                  window.location.href = url; // Redirect to the link URL
              }
          });
      }
    </script>
  </body>
</html>
