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
        @include('components.staff-nav') 

        <div class="page-wrapper">
            <div class="page-breadcrumb">
                <div class="row">
                    <div class="col-12 d-flex no-block align-items-center">
                        <h4 class="page-title">Attendance Report - {{ $class->name }}</h4>
                        <div class="ms-auto text-end">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('staff-dashboard') }}">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="{{ url('staff/view/class/' . $class->id) }}">{{ $class->name }}</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('attendance.student.index', $class->id) }}">Attendance</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Report</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

            @if(session('message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('message') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="container-fluid">
                <!-- Date Range Filter Card -->
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-end">
                            <div class="col-md-3">
                                <label for="startDate" class="form-label">Start Date</label>
                                <input type="date" id="startDate" class="form-control" value="{{ $startDate }}">
                            </div>
                            <div class="col-md-3">
                                <label for="endDate" class="form-label">End Date</label>
                                <input type="date" id="endDate" class="form-control" value="{{ $endDate }}">
                            </div>
                            <div class="col-md-4">
                                <button id="filterBtn" class="btn btn-primary">
                                    <i class="mdi mdi-filter"></i> Filter
                                </button>
                                <button id="printBtn" class="btn btn-info">
                                    <i class="mdi mdi-printer"></i> Print
                                </button>
                                <a href="{{ route('attendance.student.index', $class->id) }}" class="btn btn-secondary">
                                    <i class="mdi mdi-arrow-left"></i> Back
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Report Table -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Student Attendance Report ({{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }})</h5>

                        <div class="table-responsive">
                            <table id="reportTable" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Photo</th>
                                        <th>Student Name</th>
                                        <th>Class</th>
                                        <th>Total Days</th>
                                        <th>Present</th>
                                        <th>Absent</th>
                                        <th>Late</th>
                                        <th>Half Day</th>
                                        <th>Attendance %</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($reportData as $data)
                                    @php
                                        $student = $data['student'];
                                        $studentName = $student->name ?? 'N/A';
                                        $studentClass = $student->class ?? 'N/A';
                                        $studentAvatar = $student->avatar ?? '';
                                        $totalDays = $data['total_days'];
                                        $present = $data['present'];
                                        $absent = $data['absent'];
                                        $late = $data['late'];
                                        $halfDay = $data['half_day'];
                                        $attendancePercentage = $totalDays > 0 ? round(($present / $totalDays) * 100, 2) : 0;
                                    @endphp
                                    <tr>
                                        <td>
                                            <img src="{{ $studentAvatar }}" alt="Avatar" 
                                                 style="height: 50px; width: 50px; border-radius: 50%;">
                                        </td>
                                        <td>{{ $studentName }}</td>
                                        <td>{{ $studentClass }}</td>
                                        <td>{{ $totalDays }}</td>
                                        <td><span class="badge bg-success">{{ $present }}</span></td>
                                        <td><span class="badge bg-danger">{{ $absent }}</span></td>
                                        <td><span class="badge bg-warning">{{ $late }}</span></td>
                                        <td><span class="badge bg-info">{{ $halfDay }}</span></td>
                                        <td>
                                            @if($attendancePercentage >= 90)
                                                <span class="badge bg-success">{{ $attendancePercentage }}%</span>
                                            @elseif($attendancePercentage >= 75)
                                                <span class="badge bg-warning">{{ $attendancePercentage }}%</span>
                                            @else
                                                <span class="badge bg-danger">{{ $attendancePercentage }}%</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="9" class="text-center">No attendance records found for this date range.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <footer class="footer text-center">
                All Rights Reserved by SoftPenTech | Developed by SoftpenTech
            </footer>
        </div>
    </div>

    <script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js') }}"></script>
    <script src="{{ asset('assets/extra-libs/sparkline/sparkline.js') }}"></script>
    <script src="{{ asset('dist/js/waves.js') }}"></script>
    <script src="{{ asset('dist/js/sidebarmenu.js') }}"></script>
    <script src="{{ asset('dist/js/custom.min.js') }}"></script>
    <script src="{{ asset('assets/extra-libs/DataTables/datatables.min.js') }}"></script>

    <script>
        $("#reportTable").DataTable({
            dom: 'Bfrtip',
            buttons: ['copy', 'csv', 'excel', 'pdf']
        });

        // Date range filter
        $('#filterBtn').on('click', function() {
            const startDate = $('#startDate').val();
            const endDate = $('#endDate').val();
            
            if(startDate && endDate) {
                window.location.href = "{{ route('attendance.student.report', $class->id) }}?start_date=" + startDate + "&end_date=" + endDate;
            }
        });

        // Print functionality
        $('#printBtn').on('click', function() {
            window.print();
        });
    </script>

    <style>
        @media print {
            .page-breadcrumb, .btn, footer, .sidebar, .topbar, .card:first-child {
                display: none !important;
            }
            
            .card {
                box-shadow: none !important;
                border: none !important;
            }
        }
    </style>
</body>
</html>