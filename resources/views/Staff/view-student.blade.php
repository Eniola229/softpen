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
                      View Student Profile
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

      <div class="container my-5">
        @if(session('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
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

          
        <!-- School Info Section -->
        <div class="card mb-4 shadow">
          <div class="row g-0">
            <div class="col-md-4">
              <img src="{{ $student->avatar }}" class="img-fluid rounded-start" alt="Student Image">
            </div>
            <div class="col-md-8">
              <div class="card-body">
                <h2 class="card-title">{{ $student->name }}</h2>
                <p class="card-text"><strong>Email:</strong> {{ $student->email }}</p>
                <p class="card-text"><strong>Address:</strong> {{ $student->address }}</p>
                <p class="card-text"><strong>Class:</strong> {{ $student->class }}</p>
                <p class="card-text"><strong>Department:</strong> {{ $student->department ?? 'N/A' }}</p>
                <p class="card-text">
                  @if ($student->status === 'ACTIVE')
                      <a onclick="confirmStatusChange('{{ url('admin/change', $student->id) }}')">
                      <button class="btn btn-danger">Disactivate Account</button>
                      </a>
                  @elseif ($student->status === 'DISACTIVATE')
                     <a onclick="confirmStatusChange('{{ url('admin/change', $student->id) }}')">
                      <button class="btn btn-success" style="color: white;">Activate Account</button>
                    </a>
                  @endif
                     <button class="btn btn-success" style="color: white;" data-bs-toggle="modal" data-bs-target="#addResultModal">
                        Add This Session Result
                    </button>                   
                  </p>
              </div>
            </div>
          </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="addResultModal" tabindex="-1" aria-labelledby="addResultModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="addResultModalLabel">Add Result for {{ $student->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <form action="{{ route('staff.upload.result') }}" method="POST">
                  @csrf
                  <input type="hidden" name="student_id" value="{{ $student->id }}">
                  <input type="hidden" name="school_id" value="{{ $student->school_id }}">
                  <input type="hidden" name="class" value="{{ $student->class }}">

                  <div class="mb-3">
                    <label for="subject_name" class="form-label">Subject</label>
                    <select class="form-control" name="subject_name" required>
                      @forelse($subjects as $subject)
                        <option value="{{ $subject->name }}">{{ $subject->name }}</option>
                      @empty
                        <option disabled>No subjects available for you in this class</option>
                      @endforelse
                    </select>
                  </div>

                  <div class="mb-3">
                    <label class="form-label">Session</label>
                    <input type="text" name="session" class="form-control" placeholder="e.g. 2024/2025" required>
                  </div>

                  <div class="mb-3">
                    <label class="form-label">Term</label>
                    <select name="term" class="form-control" required>
                      <option value="First Term">First Term</option>
                      <option value="Second Term">Second Term</option>
                      <option value="Third Term">Third Term</option>
                    </select>
                  </div>

                  <div class="mb-3">
                    <label class="form-label">CA1</label>
                    <input type="number" name="ca1" class="form-control" min="0" max="30">
                  </div>

                  <div class="mb-3">
                    <label class="form-label">CA2</label>
                    <input type="number" name="ca2" class="form-control" min="0" max="30">
                  </div>

                  <div class="mb-3">
                    <label class="form-label">Exam</label>
                    <input type="number" name="exam" class="form-control" min="0" max="70">
                  </div>

                  <button type="submit" class="btn btn-success">Submit Result</button>
                </form>
              </div>
            </div>
          </div>
        </div>

      </div>
              <ul class="nav nav-tabs" id="profileTabs" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="result-tab" data-bs-toggle="tab" data-bs-target="#result" type="button" role="tab" aria-controls="result" aria-selected="false">Result</button>
          </li>
          
        </ul>
                <div class="tab-content" id="profileTabsContent">
          <!-- result Section -->
              <div class="tab-pane fade" id="result" role="tabpanel" aria-labelledby="result-tab">
              <div class="card">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="card-title">{{ $student->name }}'s Results ({{ $student->class }})</h5>
                    <a href="{{ url('staff/add/result/' . $student->id) }}">
                      <button class="btn btn-primary">Add New Result</button>
                    </a>
                  </div>

                  <div class="table-responsive">
                    <table id="zero_config" class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th>Session</th>
                          <th>Term</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      @foreach($results as $session => $terms)
                        @foreach($terms as $term => $records)
                          <tr>
                            <td>{{ $session }}</td>
                            <td>{{ $term }}</td>
                            <td>
                              @if($records->isNotEmpty())
                                <a href="{{ route('staff.result.report', $records->first()->id) }}" class="btn btn-info btn-sm">
                                  View Report
                                </a>
                              @else
                                <span class="text-muted">No data</span>
                              @endif
                            </td>
                          </tr>
                        @endforeach
                      @endforeach
                      <tfoot>
                        <tr>
                          <th>Session</th>
                          <th>Term</th>
                          <th>Action</th>
                        </tr>
                      </tfoot>
                    </table>
                  </div>
                </div>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="{{ asset('assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js') }}"></script>
    <script src="{{ asset('assets/extra-libs/sparkline/sparkline.js') }}"></script>
    <!-- Wave Effects -->
    <script src="{{ asset('dist/js/waves.js') }}"></script>
    <!-- Menu sidebar -->
    <script src="{{ asset('dist/js/sidebarmenu.js') }}"></script>
    <!-- Custom JavaScript -->
    <script src="{{ asset('dist/js/custom.min.js') }}"></script>
    <!-- this page js -->
    <script src="{{ asset('assets/extra-libs/multicheck/datatable-checkbox-init.js') }}"></script>
    <script src="{{ asset('assets/extra-libs/multicheck/jquery.multicheck.js') }}"></script>
    <script src="{{ asset('assets/extra-libs/DataTables/datatables.min.js') }}"></script>

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
              text: "You are about to change the status of this school.",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, change it!'
          }).then((result) => {
              if (result.isConfirmed) {
                  window.location.href = url; // Redirect to the link URL
              }
          });
      }
    </script>
  </body>
</html>
