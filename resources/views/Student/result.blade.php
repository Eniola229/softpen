@include('components.header') 
<link rel="stylesheet" type="text/css" href="{{ asset('assets/extra-libs/multicheck/multicheck.css') }}" />
<link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet" />
<link href="{{ asset('dist/css/style.min.css') }}" rel="stylesheet" />
<body>
    <div class="preloader">
      <div class="lds-ripple">
        <div class="lds-pos"></div>
        <div class="lds-pos"></div>
      </div>
    </div>

    <div id="main-wrapper" data-layout="vertical" data-navbarbg="skin5" data-sidebartype="full" 
         data-sidebar-position="absolute" data-header-position="absolute" data-boxed-layout="full">
      
      @include('components.nav') 
      @include('components.student-nav') 

      <div class="page-wrapper">
        <div class="page-breadcrumb">
          <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
              <h4 class="page-title"></h4>
              <div class="ms-auto text-end">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Softpen</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                    Student Profile
                    </li>
                  </ol>
                </nav>
              </div>
            </div>
          </div>
        </div>

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

          
        <!-- Student Info Section -->
        <div class="card mb-4 shadow">
          <div class="row g-0">
            <div class="col-md-4 p-2 p-md-0">
              <img src="{{ $student->avatar }}" class="img-fluid rounded-start h-100" alt="Student Image">
            </div>
            <div class="col-md-8">
              <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                  <h5 class="card-title mb-0">Student Details</h5>
                </div>
                <div class="card-body p-4">
                  
                  <div class="row mb-3">
                    <div class="col-12">
                      <div class="d-flex">
                        <span class="text-muted" style="min-width: 150px;">Student Name</span>
                        <strong>{{ $student->name }}</strong>
                      </div>
                    </div>
                  </div>

                  <div class="row mb-3">
                    <div class="col-12">
                      <div class="d-flex">
                        <span class="text-muted" style="min-width: 150px;">Email</span>
                        <strong>{{ $student->email }}</strong>
                      </div>
                    </div>
                  </div>

                  <div class="row mb-3">
                    <div class="col-12">
                      <div class="d-flex">
                        <span class="text-muted" style="min-width: 150px;">Address</span>
                        <strong>{{ $student->address }}</strong>
                      </div>
                    </div>
                  </div>

                  <div class="row mb-3">
                    <div class="col-12">
                      <div class="d-flex">
                        <span class="text-muted" style="min-width: 150px;">Class</span>
                        <strong>{{ $student->class }}</strong>
                      </div>
                    </div>
                  </div>

                  <div class="row mb-4">
                    <div class="col-12">
                      <div class="d-flex">
                        <span class="text-muted" style="min-width: 150px;">Department</span>
                        <strong>{{ $student->department ?? 'N/A' }}</strong>
                      </div>
                    </div>
                  </div>

                </div>
              </div>
            </div>
          </div> 
        </div>

      </div>

        <!-- Tabs -->
        <ul class="nav nav-tabs" id="profileTabs" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="result-tab" data-bs-toggle="tab" data-bs-target="#result" type="button" role="tab" aria-controls="result" aria-selected="false">Result's</button>
          </li>
          
          @if($attendanceActive)
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="attendance-tab" data-bs-toggle="tab" data-bs-target="#attendance" type="button" role="tab" aria-controls="attendance" aria-selected="false">My Attendance</button>
          </li>
          @endif
        </ul>

        <div class="tab-content" id="profileTabsContent">
          
          <!-- Results Section -->
          <div class="tab-pane fade" id="result" role="tabpanel" aria-labelledby="result-tab">
            <div class="card">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <h5 class="card-title">{{ $student->name }}'s Results ({{ $student->class }})</h5>
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
                    <tbody>
                      @foreach($results as $session => $terms)
                        @foreach($terms as $term => $records)
                          <tr>
                            <td>{{ $session }}</td>
                            <td>{{ $term }}</td>
                            <td>
                              @if($records->isNotEmpty())
                                <a href="{{ route('student.result.report', $records->first()->id) }}" class="btn btn-info btn-sm">
                                  View Report
                                </a>
                              @else
                                <span class="text-muted">No data</span>
                              @endif
                            </td>
                          </tr>
                        @endforeach
                      @endforeach
                    </tbody>
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

          <!-- Attendance Section -->
          @if($attendanceActive)
          <div class="tab-pane fade" id="attendance" role="tabpanel" aria-labelledby="attendance-tab">
            
            <!-- Stats Cards -->
            <div class="row mb-4">
              <div class="col-md-3">
                <div class="card">
                  <div class="card-body text-center">
                    <h6 class="card-title text-muted">This Month</h6>
                    <h2 class="mb-0">{{ $attendanceStats['total'] }}</h2>
                    <small class="text-muted">Total Days</small>
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="card bg-success text-white">
                  <div class="card-body text-center">
                    <h6 class="card-title text-white">Present</h6>
                    <h2 class="mb-0 text-white">{{ $attendanceStats['present'] }}</h2>
                    <small class="text-white">Days</small>
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="card bg-danger text-white">
                  <div class="card-body text-center">
                    <h6 class="card-title text-white">Absent</h6>
                    <h2 class="mb-0 text-white">{{ $attendanceStats['absent'] }}</h2>
                    <small class="text-white">Days</small>
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="card 
                  @if($attendanceStats['percentage'] >= 90) bg-success
                  @elseif($attendanceStats['percentage'] >= 75) bg-warning
                  @else bg-danger
                  @endif
                  text-white">
                  <div class="card-body text-center">
                    <h6 class="card-title text-white">Attendance Rate</h6>
                    <h2 class="mb-0 text-white">{{ $attendanceStats['percentage'] }}%</h2>
                    <small class="text-white">This Month</small>
                  </div>
                </div>
              </div>
            </div>

            <!-- Attendance Records Table -->
            <div class="card">
              <div class="card-body">
                <h5 class="card-title mb-3">My Attendance Records (Last 20 Days)</h5>

                <div class="table-responsive">
                  <table id="attendanceTable" class="table table-striped table-bordered">
                    <thead>
                      <tr>
                        <th>Date</th>
                        <th>Day</th>
                        <th>Status</th>
                        <th>Check-in Time</th>
                        <th>Remarks</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse($attendanceRecords as $record)
                      <tr>
                        <td>{{ $record->attendance_date->format('M d, Y') }}</td>
                        <td>{{ $record->attendance_date->format('l') }}</td>
                        <td>
                          @if($record->status == 'PRESENT')
                            <span class="badge bg-success">Present</span>
                          @elseif($record->status == 'ABSENT')
                            <span class="badge bg-danger">Absent</span>
                          @elseif($record->status == 'LATE')
                            <span class="badge bg-warning">Late</span>
                          @else
                            <span class="badge bg-info">Half Day</span>
                          @endif
                        </td>
                        <td>{{ $record->check_in_time ?? '-' }}</td>
                        <td>{{ $record->remarks ?? '-' }}</td>
                      </tr>
                      @empty
                      <tr>
                        <td colspan="5" class="text-center">No attendance records found.</td>
                      </tr>
                      @endforelse
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

          </div>
          @endif

        </div>
      
      </div>

        <footer class="footer text-center">
            All Rights Reserved by SoftPenTech | Developed by SoftpenTech
        </footer>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js') }}"></script>
    <script src="{{ asset('assets/extra-libs/sparkline/sparkline.js') }}"></script>
    <script src="{{ asset('dist/js/waves.js') }}"></script>
    <script src="{{ asset('dist/js/sidebarmenu.js') }}"></script>
    <script src="{{ asset('dist/js/custom.min.js') }}"></script>
    <script src="{{ asset('assets/extra-libs/multicheck/datatable-checkbox-init.js') }}"></script>
    <script src="{{ asset('assets/extra-libs/multicheck/jquery.multicheck.js') }}"></script>
    <script src="{{ asset('assets/extra-libs/DataTables/datatables.min.js') }}"></script>

    <script>
      $("#zero_config").DataTable();
      
      @if($attendanceActive)
      $("#attendanceTable").DataTable({
        "order": [[ 0, "desc" ]] // Sort by date descending
      });
      @endif
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
                  window.location.href = url;
              }
          });
      }
    </script>
</body>
</html>