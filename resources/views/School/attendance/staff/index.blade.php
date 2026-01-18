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
                        <h4 class="page-title">Staff Attendance</h4>
                        <div class="ms-auto text-end">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('school-dashboard') }}">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Staff Attendance</li>
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

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="container-fluid">
                <!-- Stats Cards -->
                @php
                    $totalStaff = 0;
                    $presentCount = 0;
                    $absentCount = 0;
                    $lateCount = 0;
                    $notMarkedCount = 0;
                    
                    foreach($staffAttendances as $item) {
                        $totalStaff++;
                        if($item->marked && $item->attendance) {
                            $attendanceStatus = $item->attendance->status;
                            if($attendanceStatus == 'PRESENT') {
                                $presentCount++;
                            } elseif($attendanceStatus == 'ABSENT') {
                                $absentCount++;
                            } elseif($attendanceStatus == 'LATE') {
                                $lateCount++;
                            }
                        } else {
                            $notMarkedCount++;
                        }
                    }
                @endphp

                <div class="row">
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Total Staff</h5>
                                <h2 class="mb-0">{{ $totalStaff }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <h5 class="card-title text-white">Present</h5>
                                <h2 class="mb-0 text-white" id="presentCount">{{ $presentCount }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-danger text-white">
                            <div class="card-body">
                                <h5 class="card-title text-white">Absent</h5>
                                <h2 class="mb-0 text-white" id="absentCount">{{ $absentCount }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <h5 class="card-title text-white">Not Marked</h5>
                                <h2 class="mb-0 text-white" id="notMarkedCount">{{ $notMarkedCount }}</h2>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Attendance Table -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title">Mark Attendance - {{ $today->format('l, F j, Y') }}</h5>
                            <div>
                                <a href="{{ route('attendance.staff.history') }}" class="btn btn-info me-2">
                                    <i class="mdi mdi-history"></i> View History
                                </a>
                                <a href="{{ route('attendance.staff.report') }}" class="btn btn-secondary me-2">
                                    <i class="mdi mdi-file-chart"></i> Reports
                                </a>
                                <button id="markAllPresentBtn" class="btn btn-success">
                                    <i class="mdi mdi-check-all"></i> Mark All Present
                                </button>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table id="attendanceTable" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Photo</th>
                                        <th>Name</th>
                                        <th>Department</th>
                                        <th>Subject</th>
                                        <th>Status</th>
                                        <th>Check-in Time</th>
                                        <th>Remarks</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($staffAttendances as $item)
                                    @php
                                        $staffData = $item->staff;
                                        $isMarked = $item->marked;
                                        $attendanceData = $item->attendance;
                                        $staffId = $staffData->id ?? '';
                                        $staffName = $staffData->name ?? '';
                                        $staffDept = $staffData->department ?? 'N/A';
                                        
                                        // Get subject names from IDs
                                        $staffSubject = 'N/A';
                                        if($staffData->subject) {
                                            if(is_string($staffData->subject)) {
                                                $subjectIds = explode(',', $staffData->subject);
                                                $subjects = \App\Models\Subject::whereIn('id', $subjectIds)->pluck('name')->toArray();
                                                $staffSubject = !empty($subjects) ? implode(', ', $subjects) : 'N/A';
                                            }
                                        }
                                        
                                        $staffAvatar = $staffData->avatar ?? '';
                                        
                                        // Extract attendance details only if attendance exists
                                        $attendanceStatus = null;
                                        $checkInTime = null;
                                        $remarks = null;
                                        
                                        if($attendanceData) {
                                            $attendanceStatus = $attendanceData->status ?? null;
                                            $checkInTime = $attendanceData->check_in_time ?? null;
                                            $remarks = $attendanceData->remarks ?? null;
                                        }
                                    @endphp
                                    <tr data-staff-id="{{ $staffId }}">
                                        <td>
                                            <img src="{{ $staffAvatar }}" alt="Avatar" 
                                                 style="height: 50px; width: 50px; border-radius: 50%;">
                                        </td>
                                        <td>{{ $staffName }}</td>
                                        <td>{{ $staffDept }}</td>
                                        <td>{{ $staffSubject }}</td>
                                        <td class="attendance-status">
                                            @if($isMarked && $attendanceStatus)
                                                @if($attendanceStatus == 'PRESENT')
                                                    <span class="badge bg-success">Present</span>
                                                @elseif($attendanceStatus == 'ABSENT')
                                                    <span class="badge bg-danger">Absent</span>
                                                @elseif($attendanceStatus == 'LATE')
                                                    <span class="badge bg-warning">Late</span>
                                                @else
                                                    <span class="badge bg-info">Half Day</span>
                                                @endif
                                            @else
                                                <span class="badge bg-secondary">Not Marked</span>
                                            @endif
                                        </td>
                                        <td class="check-in-time">
                                            {{ $checkInTime ?? '-' }}
                                        </td>
                                        <td class="remarks">
                                            {{ $remarks ?? '-' }}
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-primary mark-attendance-btn" 
                                                    data-staff-id="{{ $staffId }}"
                                                    data-staff-name="{{ $staffName }}">
                                                <i class="mdi mdi-pencil"></i> Mark
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $("#attendanceTable").DataTable();

        // Mark individual attendance
        $(document).on('click', '.mark-attendance-btn', function() {
            const staffId = $(this).data('staff-id');
            const staffName = $(this).data('staff-name');
            
            Swal.fire({
                title: 'Mark Attendance',
                html: `
                    <p class="mb-3">Staff: <strong>${staffName}</strong></p>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select id="status" class="form-select">
                            <option value="PRESENT">Present</option>
                            <option value="ABSENT">Absent</option>
                            <option value="LATE">Late</option>
                            <option value="HALF_DAY">Half Day</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Check-in Time</label>
                        <input type="time" id="checkInTime" class="form-control" value="${new Date().toTimeString().slice(0,5)}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Remarks (Optional)</label>
                        <textarea id="remarks" class="form-control" rows="2"></textarea>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Mark Attendance',
                preConfirm: () => {
                    return {
                        status: document.getElementById('status').value,
                        check_in_time: document.getElementById('checkInTime').value,
                        remarks: document.getElementById('remarks').value
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    markAttendance(staffId, result.value);
                }
            });
        });

        function markAttendance(staffId, data) {
            $.ajax({
                url: "{{ route('attendance.staff.mark') }}",
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    staff_id: staffId,
                    status: data.status,
                    check_in_time: data.check_in_time,
                    remarks: data.remarks
                },
                success: function(response) {
                    Swal.fire('Success!', response.message, 'success').then(() => {
                        location.reload();
                    });
                },
                error: function(xhr) {
                    Swal.fire('Error!', 'Failed to mark attendance', 'error');
                }
            });
        }

        // Mark all present
        $('#markAllPresentBtn').on('click', function() {
            Swal.fire({
                title: 'Mark All Present?',
                text: 'This will mark all staff as present for today',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, Mark All'
            }).then((result) => {
                if (result.isConfirmed) {
                    const btn = $(this);
                    btn.prop('disabled', true).html('<i class="mdi mdi-loading mdi-spin"></i> Processing...');
                    
                    $.ajax({
                        url: "{{ route('attendance.staff.markAll') }}",
                        method: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            Swal.fire('Success!', response.message, 'success').then(() => {
                                location.reload();
                            });
                        },
                        error: function() {
                            Swal.fire('Error!', 'Failed to mark attendance', 'error');
                            btn.prop('disabled', false).html('<i class="mdi mdi-check-all"></i> Mark All Present');
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>