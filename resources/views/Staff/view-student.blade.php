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
            <div class="col-md-4 p-2 p-md-0">
              <img src="{{ $student->avatar }}" class="img-fluid rounded-start h-100" alt="Student Image">
            </div>
            <!-- Student Result Details Section -->
            <div class="col-md-8">
              <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                  <h5 class="card-title mb-0">Student Details</h5>
                </div>
                <div class="card-body p-4">
                  
                  <!-- Student Name -->
                  <div class="row mb-3">
                    <div class="col-12">
                      <div class="d-flex">
                        <span class="text-muted" style="min-width: 150px;">Student Name</span>
                        <strong>{{ $student->name }}</strong>
                      </div>
                    </div>
                  </div>

                  <!-- Email -->
                  <div class="row mb-3">
                    <div class="col-12">
                      <div class="d-flex">
                        <span class="text-muted" style="min-width: 150px;">Email</span>
                        <strong>{{ $student->email }}</strong>
                      </div>
                    </div>
                  </div>

                  <!-- Address -->
                  <div class="row mb-3">
                    <div class="col-12">
                      <div class="d-flex">
                        <span class="text-muted" style="min-width: 150px;">Address</span>
                        <strong>{{ $student->address }}</strong>
                      </div>
                    </div>
                  </div>

                  <!-- Class -->
                  <div class="row mb-3">
                    <div class="col-12">
                      <div class="d-flex">
                        <span class="text-muted" style="min-width: 150px;">Class</span>
                        <strong>{{ $student->class }}</strong>
                      </div>
                    </div>
                  </div>

                  <!-- Department -->
                  <div class="row mb-4">
                    <div class="col-12">
                      <div class="d-flex">
                        <span class="text-muted" style="min-width: 150px;">Department</span>
                        <strong>{{ $student->department ?? 'N/A' }}</strong>
                      </div>
                    </div>
                  </div>

                  <!-- Action Button -->
                  <div class="row">
                    <div class="col-12">
                      <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addResultModal">
                        Add This Session Result
                      </button>
                    </div>
                  </div>

                </div>
              </div>
            </div>
          </div> 
        </div>

@php
    // Ensure staff subjects is an array
    $staff = Auth::guard('staff')->user();
    $teacherSubjects = is_array($staff->subject) ? $staff->subject : json_decode($staff->subject, true) ?? [];
@endphp

<!-- Add Result Modal -->
<div class="modal fade" id="addResultModal" tabindex="-1" aria-labelledby="addResultModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Add Result for {{ $student->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <form action="{{ route('staff.upload.result') }}" method="POST">
                    @csrf

                    <input type="hidden" name="student_id" value="{{ $student->id }}">
                    <input type="hidden" name="school_id" value="{{ $student->school_id }}">
                    <input type="hidden" name="class" value="{{ $student->class }}">

                    <!-- Subjects Multi-Select -->
                    <div class="mb-3">
                        <label class="form-label">Subjects</label>
                        <select name="subjects[]" class="form-control" multiple size="6" style="height:auto;" required>
                            @forelse($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                            @empty
                                <option disabled>No subjects available</option>
                            @endforelse
                        </select>
                        <small class="text-muted">Hold CTRL to select multiple subjects.</small>
                    </div>

                    <!-- Session / Term -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Session</label>
                            <input type="text" name="session" class="form-control" placeholder="2024/2025" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Term</label>
                            <select name="term" class="form-control" required>
                                <option value="" selected disabled>Select a term</option>
                                <option value="First Term">First Term</option>
                                <option value="Second Term">Second Term</option>
                                <option value="Third Term">Third Term</option>
                            </select>
                        </div>
                    </div>

                    <!-- Scores Table -->
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Subject</th>
                                <th>Test (40)</th>
                                <th>Exam (60)</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($subjects as $subject)
                                <tr>
                                    <td>{{ $subject->name }}</td>

                                    <td>
                                        <input type="number"
                                               name="test[{{ $subject->id }}]"
                                               class="form-control"
                                               min="0" max="40">
                                    </td>

                                    <td>
                                        <input type="number"
                                               name="exam[{{ $subject->id }}]"
                                               class="form-control"
                                               min="0" max="60">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Teacher's Comment (Only for Class Teacher)</label>
                        <textarea name="teachers_comment" class="form-control" maxlength="500" rows="4" placeholder="Enter your comment (max 500 characters)"></textarea>
                    </div>
                    <button type="submit" class="btn btn-success">Submit Results</button>

                </form>

            </div>
        </div>
    </div>
</div>


      </div>
        <ul class="nav nav-tabs" id="profileTabs" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="result-tab" data-bs-toggle="tab" data-bs-target="#result" type="button" role="tab" aria-controls="result" aria-selected="false">Result's</button>
          </li>
           @if ($cbt)
            @if ($cbt->status === 'ACTIVE')
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="cbt-result-tab" data-bs-toggle="tab" data-bs-target="#cbt-result" type="button" role="tab" aria-controls="cbt-result" aria-selected="false">CBT Exam Results</button>
          </li>
          @endif
          @endif
        </ul>
                <div class="tab-content" id="profileTabsContent">
          <!-- result Section -->
              <div class="tab-pane fade" id="result" role="tabpanel" aria-labelledby="result-tab">
              <div class="card">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="card-title">{{ $student->name }}'s Results ({{ $student->class }})</h5>
                   
                      <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addResultModal">Add New Result</button>
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
            <!-- CBT Results Tab Content -->
            <div class="tab-pane fade" id="cbt-result" role="tabpanel" aria-labelledby="cbt-result-tab">
              <div class="card">
                <div class="card-body">
                  <h5 class="card-title mb-4">{{ $student->name }}'s CBT Exam Results</h5>
                  
                  @if($cbtResults->count() > 0)
                  <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th>Session</th>
                          <th>Term</th>
                          <th>Exam Title</th>
                          <th>Subject</th>
                          <th>Date Taken</th>
                          <th>Score</th>
                          <th>Percentage</th>
                          <th>Status</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($cbtResults as $result)
                        <tr>
                          <td>{{ $result->exam->session ?? 'N/A' }}</td>
                          <td>{{ $result->exam->term ?? 'N/A' }}</td>
                          <td>{{ $result->exam->title }}</td>
                          <td>{{ $subjectsMap[$result->exam->subject] ?? $result->exam->subject }}</td>
                          <td>{{ $result->submitted_at ? $result->submitted_at->format('M d, Y h:i A') : 'In Progress' }}</td>
                          <td>
                            <strong>{{ $result->total_score ?? 0 }}</strong> / {{ $result->exam->questions->sum('mark') }}
                          </td>
                          <td>
                            @if($result->percentage)
                              <span class="badge {{ $result->percentage >= $result->exam->passing_score ? 'bg-success' : 'bg-danger' }}">
                                {{ number_format($result->percentage, 1) }}%
                              </span>
                            @else
                              <span class="badge bg-secondary">N/A</span>
                            @endif
                          </td>
                          <td>
                            @if(!$result->submitted_at)
                              <span class="badge bg-warning">In Progress</span>
                            @elseif($result->percentage >= $result->exam->passing_score)
                              <span class="badge bg-success">Passed</span>
                            @else
                              <span class="badge bg-danger">Failed</span>
                            @endif
                          </td>
                          <td>
                            @if($result->submitted_at)
                              <a href="{{ route('staff.cbt.result.view', [$student->id, $result->id]) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i> View Details
                              </a>
                            @else
                              <span class="text-muted">Not completed</span>
                            @endif
                          </td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                  @else
                  <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> This student has not taken any CBT exams yet.
                  </div>
                  @endif
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

      const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            const term = document.getElementById('termSelect').value;
            if (!term) {
                e.preventDefault();
                alert('Please select a term before proceeding.');
            }
        });

    </script>
  </body>
</html>
