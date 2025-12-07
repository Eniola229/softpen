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
              <h4 class="page-title">Exams for {{ $class->name }}</h4>
              <div class="ms-auto text-end">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Softpen</a></li>
                    <li class="breadcrumb-item"><a href="#">Classes</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                      {{ $class->name }} - CBT Exams
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
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert"> 
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <h5 class="card-title">Exams</h5>
              <a href="{{ route("staff.exams.create", $class->id) }}">
                <button class="btn btn-primary">
                  <i class="fas fa-plus"></i> Add New Exam
                </button>
              </a>
            </div>

            @if($exams->count() > 0)
              <div class="table-responsive">
                <table id="exam_table" class="table table-striped table-bordered">
                  <thead>
                    <tr>
                      <th>Title</th>
                      <th>Subject</th>
                      <th>Duration (min)</th>
                      <th>Questions</th>
                      <th>Pass Score</th>
                      <th>Status</th>
                      <th>Created At</th>
                      <th>Action</th>
                    </tr> 
                  </thead>
                  <tbody>
                    @foreach($exams as $exam)
                      <tr>
                        <td>{{ $exam->title }}</td>
                        <td>
                            @if(is_string($exam->subject) && isset($subjects[$exam->subject]))
                                {{ $subjects[$exam->subject]->name }}
                            @else
                                {{ $exam->subject ?? 'No Subject' }}
                            @endif
                        </td>
                        <td>{{ $exam->duration }}</td>
                        <td>{{ $exam->total_questions }}</td>
                        <td>{{ $exam->passing_score }}%</td>
                        <td>
                          @if($exam->is_published)
                            Published
                          @else
                            Draft
                          @endif
                        </td>
                        <td>
                          @if($exam->created_at)
                            {{ $exam->created_at->format("M d, Y") }}
                          @else
                            N/A
                          @endif
                        </td>
                        <td>
                          <a href="{{ route("staff.exams.show", [$class->id, $exam->id]) }}">
                            <button class="btn btn-info btn-sm" title="View Exam">
                              <i class="fas fa-eye"></i> View
                            </button>
                          </a>
                          <a href="{{ route("staff.exams.edit", [$class->id, $exam->id]) }}">
                            <button class="btn btn-warning btn-sm" title="Edit Exam">
                              <i class="fas fa-edit"></i> Edit
                            </button>
                          </a>
                          <button class="btn btn-danger btn-sm" onclick="confirmDeleteExam('{{ route("staff.exams.destroy", [$class->id, $exam->id]) }}', '{{ $exam->title }}')" title="Delete Exam">
                            <i class="fas fa-trash"></i> Delete
                          </button>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                  <tfoot>
                    <tr>
                      <th>Title</th>
                      <th>Subject</th>
                      <th>Duration (min)</th>
                      <th>Questions</th>
                      <th>Pass Score</th>
                      <th>Status</th>
                      <th>Created At</th>
                      <th>Action</th>
                    </tr>
                  </tfoot>
                </table>
              </div>
              
              <!-- Pagination -->
              <div class="d-flex justify-content-center mt-3">
                {{ $exams->links() }}
              </div>
            @else
              <div class="alert alert-info">
                <strong>No Exams Found</strong><br>
                There are currently no exams for <strong>{{ $class->name }}</strong>. 
                <a href="{{ route("staff.exams.create", $class->id) }}" class="alert-link">Create one now</a>
              </div>
            @endif
          </div>
        </div>

        <!-- ============================================================== -->
        <!-- footer -->
        <!-- ============================================================== -->
        <footer class="footer text-center">
            All Rights Reserved by SoftPenTech | Developed by SoftpenTech
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
    <script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js') }}"></script>
    <script src="{{ asset('assets/extra-libs/sparkline/sparkline.js') }}"></script>

    <!-- Wave Effects -->
    <script src="{{ asset('dist/js/waves.js') }}"></script>

    <!-- Menu sidebar -->
    <script src="{{ asset('dist/js/sidebarmenu.js') }}"></script>

    <!-- Custom JavaScript -->
    <script src="{{ asset('dist/js/custom.min.js') }}"></script>

    <!-- This page js -->
    <script src="{{ asset('assets/extra-libs/multicheck/datatable-checkbox-init.js') }}"></script>
    <script src="{{ asset('assets/extra-libs/multicheck/jquery.multicheck.js') }}"></script>
    <script src="{{ asset('assets/extra-libs/DataTables/datatables.min.js') }}"></script>

    <!-- External CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>

    
    <script>
      /****************************************
       *       Basic Table                   *
       ****************************************/
      $("#exam_table").DataTable();
    </script>

    <script type="text/javascript">
      function confirmDeleteExam(url, examTitle) {
          Swal.fire({
              title: "Are you sure?",
              text: "You are about to delete the exam: " + examTitle,
              icon: "warning",
              showCancelButton: true,
              confirmButtonColor: "#3085d6",
              cancelButtonColor: "#d33",
              confirmButtonText: "Yes, delete it!"
          }).then((result) => {
              if (result.isConfirmed) {
                  // Create a form and submit it
                  let form = document.createElement("form");
                  form.method = "POST";
                  form.action = url;
                  
                  let csrfToken = document.createElement("input");
                  csrfToken.type = "hidden";
                  csrfToken.name = "_token";
                  csrfToken.value = "{{ csrf_token() }}";
                  
                  let methodInput = document.createElement("input");
                  methodInput.type = "hidden";
                  methodInput.name = "_method";
                  methodInput.value = "DELETE";
                  
                  form.appendChild(csrfToken);
                  form.appendChild(methodInput);
                  document.body.appendChild(form);
                  form.submit();
              }
          });
      }
    </script>
  </body>
</html>