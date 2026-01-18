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
        @include('components.school-nav') 

        <div class="page-wrapper">
            <div class="page-breadcrumb">
                <div class="row">
                    <div class="col-12 d-flex no-block align-items-center">
                        <h4 class="page-title">Attendance History</h4>
                        <div class="ms-auto text-end">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('school-dashboard') }}">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('attendance.staff.index') }}">Staff Attendance</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">History</li>
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
                <!-- Date Filter Card -->
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-end">
                            <div class="col-md-4">
                                <label for="dateFilter" class="form-label">Select Date</label>
                                <input type="date" id="dateFilter" class="form-control" value="{{ $selectedDate->format('Y-m-d') }}">
                            </div>
                            <div class="col-md-2">
                                <button id="filterBtn" class="btn btn-primary">
                                    <i class="mdi mdi-filter"></i> Filter
                                </button>
                                <a href="{{ route('attendance.staff.index') }}" class="btn btn-secondary">
                                    <i class="mdi mdi-arrow-left"></i> Back
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="row">
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Total Staff</h5>
                                <h2 class="mb-0">{{ $stats['total'] }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <h5 class="card-title text-white">Present</h5>
                                <h2 class="mb-0 text-white">{{ $stats['present'] }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-danger text-white">
                            <div class="card-body">
                                <h5 class="card-title text-white">Absent</h5>
                                <h2 class="mb-0 text-white">{{ $stats['absent'] }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <h5 class="card-title text-white">Late</h5>
                                <h2 class="mb-0 text-white">{{ $stats['late'] }}</h2>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- History Table -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Attendance for {{ $selectedDate->format('l, F j, Y') }}</h5>

                        <div class="table-responsive">
                            <table id="historyTable" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Photo</th>
                                        <th>Staff Name</th>
                                        <th>Department</th>
                                        <th>Status</th>
                                        <th>Check-in Time</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($attendances as $attendance)
                                    @php
                                        $staff = $attendance->staff;
                                        $staffName = $staff->name ?? 'N/A';
                                        $staffDept = $staff->department ?? 'N/A';
                                        $staffAvatar = $staff->avatar ?? '';
                                        $status = $attendance->status;
                                        $checkIn = $attendance->check_in_time ?? '-';
                                        $remarks = $attendance->remarks ?? '-';
                                    @endphp
                                    <tr>
                                        <td>
                                            <img src="{{ $staffAvatar }}" alt="Avatar" 
                                                 style="height: 50px; width: 50px; border-radius: 50%;">
                                        </td>
                                        <td>{{ $staffName }}</td>
                                        <td>{{ $staffDept }}</td>
                                        <td>
                                            @if($status == 'PRESENT')
                                                <span class="badge bg-success">Present</span>
                                            @elseif($status == 'ABSENT')
                                                <span class="badge bg-danger">Absent</span>
                                            @elseif($status == 'LATE')
                                                <span class="badge bg-warning">Late</span>
                                            @else
                                                <span class="badge bg-info">Half Day</span>
                                            @endif
                                        </td>
                                        <td>{{ $checkIn }}</td>
                                        <td>{{ $remarks }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No attendance records found for this date.</td>
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
        $("#historyTable").DataTable();

        // Date filter
        $('#filterBtn').on('click', function() {
            const date = $('#dateFilter').val();
            if(date) {
                window.location.href = "{{ route('attendance.staff.history') }}?date=" + date;
            }
        });
    </script>
</body>
</html>