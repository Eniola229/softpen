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
                        <h4 class="page-title">Student Attendance - {{ $class->name }}</h4>
                        <div class="ms-auto text-end">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('staff-dashboard') }}">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="{{ url('staff/view/class/' . $class->id) }}">{{ $class->name }}</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Attendance</li>
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
                    $totalStudents = 0;
                    $presentCount = 0;
                    $absentCount = 0;
                    $lateCount = 0;
                    $notMarkedCount = 0;
                    
                    foreach($studentAttendances as $item) {
                        $totalStudents++;
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
                                <h5 class="card-title">Total Students</h5>
                                <h2 class="mb-0">{{ $totalStudents }}</h2>
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
                                <a href="{{ route('attendance.student.history', $class->id) }}" class="btn btn-info me-2">
                                    <i class="mdi mdi-history"></i> View History
                                </a>
                                <a href="{{ route('attendance.student.report', $class->id) }}" class="btn btn-secondary me-2">
                                    <i class="mdi mdi-file-chart"></i> Reports
                                </a>
                                <button id="markAllPresentBtn" class="btn btn-success" data-class-id="{{ $class->id }}">
                                    <i class="mdi mdi-check-all"></i> Mark All Present
                                </button>
                                <a href="{{ url('staff/view/class/' . $class->id) }}" class="btn btn-dark">
                                    <i class="mdi mdi-arrow-left"></i> Back to Class
                                </a>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table id="attendanceTable" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Photo</th>
                                        <th>Name</th>
                                        <th>Class</th>
                                        <th>Status</th>
                                        <th>Check-in Time</th>
                                        <th>Remarks</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($studentAttendances as $item)
                                    @php
                                        $studentData = $item->student;
                                        $isMarked = $item->marked;
                                        $attendanceData = $item->attendance;
                                        $studentId = $studentData->id ?? '';
                                        $studentName = $studentData->name ?? '';
                                        $studentClass = $studentData->class ?? 'N/A';
                                        $studentAvatar = $studentData->avatar ?? '';
                                        
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
                                    <tr data-student-id="{{ $studentId }}">
                                        <td>
                                            <img src="{{ $studentAvatar }}" alt="Avatar" 
                                                 style="height: 50px; width: 50px; border-radius: 50%;">
                                        </td>
                                        <td>{{ $studentName }}</td>
                                        <td>{{ $studentClass }}</td>
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
                                                    data-student-id="{{ $studentId }}"
                                                    data-student-name="{{ $studentName }}"
                                                    data-class-id="{{ $class->id }}">
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
            const studentId = $(this).data('student-id');
            const studentName = $(this).data('student-name');
            const classId = $(this).data('class-id');
            
            Swal.fire({
                title: 'Mark Attendance',
                html: `
                    <p class="mb-3">Student: <strong>${studentName}</strong></p>
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
                    markAttendance(studentId, classId, result.value);
                }
            });
        });

        function markAttendance(studentId, classId, data) {
            $.ajax({
                url: "{{ route('attendance.student.mark') }}",
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    student_id: studentId,
                    class_id: classId,
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
            const classId = $(this).data('class-id');
            
            Swal.fire({
                title: 'Mark All Present?',
                text: 'This will mark all students as present for today',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, Mark All'
            }).then((result) => {
                if (result.isConfirmed) {
                    const btn = $(this);
                    btn.prop('disabled', true).html('<i class="mdi mdi-loading mdi-spin"></i> Processing...');
                    
                    $.ajax({
                        url: "{{ route('attendance.student.markAll') }}",
                        method: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            class_id: classId
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