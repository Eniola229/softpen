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
      @include('components.side-nav') 
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
                      View Profile
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
              <img src="{{ $school->avatar }}" class="img-fluid rounded-start" alt="School Image">
            </div>
            <div class="col-md-8">
              <div class="card-body">
                <h2 class="card-title">{{ $school->name }}</h2>
                <p class="card-text"><strong>Email:</strong> {{ $school->email }}</p>
                <p class="card-text"><strong>Phone:</strong> {{ $school->mobile }}</p>
                <p class="card-text"><strong>Address:</strong> {{ $school->address }}</p>
                <p class="card-text">
                  @if ($school->status === 'ACTIVE')
                      <a onclick="confirmStatusChange('{{ url('admin/change', $school->id) }}')">
                      <button class="btn btn-danger">Disactivate Account</button>
                      </a>
                  @elseif ($school->status === 'DISACTIVATE')
                     <a onclick="confirmStatusChange('{{ url('admin/change', $school->id) }}')">
                      <button class="btn btn-success" style="color: white;">Activate Account</button>
                    </a>
                  @endif
                     <button class="btn btn-success" style="color: white;" data-bs-toggle="modal" data-bs-target="#editSchoolModal">
                        Edit School
                    </button>                   
                  </p>
              </div>
            </div>
          </div>
        </div>

        <!-- Modal -->
      <div class="modal fade" id="editSchoolModal" tabindex="-1" aria-labelledby="editSchoolModalLabel" aria-hidden="true">
          <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="editSchoolModalLabel">Edit School</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                      <!-- Form for Editing School -->
                      <form action="{{ route('admin/schools') }}" method="POST" enctype="multipart/form-data"> 
                          @csrf
                          <input type="hidden" name="id" value="{{ $school->id }}">
                          <div class="mb-3">
                              <label for="name" class="form-label">School Name</label>
                              <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $school->name) }}" required>
                          </div>

                          <div class="mb-3">
                              <label for="email" class="form-label">Email</label>
                              <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $school->email) }}" required>
                          </div>

                          <div class="mb-3">
                              <label for="mobile" class="form-label">Mobile</label>
                              <input type="text" class="form-control" id="mobile" name="mobile" value="{{ old('mobile', $school->mobile) }}" required>
                          </div>

                          <div class="mb-3">
                              <label for="motto" class="form-label">Motto</label>
                              <input type="text" class="form-control" id="motto" name="motto" value="{{ old('motto', $school->motto) }}" required>
                          </div>

                          <div class="mb-3">
                              <label for="address" class="form-label">Address</label>
                              <input type="text" class="form-control" id="address" name="address" value="{{ old('address', $school->address) }}" required>
                          </div>

                           <div class="mb-3">
                              <label for="avatar" class="form-label">School Pictire</label>
                              <input type="file" class="form-control" id="avatar" name="avatar">
                              <small class="form-text text-muted">Leave blank to keep the current image.</small>
                          </div>

                          <div class="mb-3">
                              <label for="password" class="form-label">Password</label>
                              <input type="password" class="form-control" id="password" name="password">
                              <small class="form-text text-muted">Leave blank to keep the current password.</small>
                          </div>

                          <button type="submit" class="btn btn-success">Update School</button>
                      </form>
                  </div>
              </div>
          </div>
      </div>


        <!-- Tabs for Students and Staff -->
        <ul class="nav nav-tabs" id="profileTabs" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active" id="students-tab" data-bs-toggle="tab" data-bs-target="#students" type="button" role="tab" aria-controls="students" aria-selected="true">Students</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="staff-tab" data-bs-toggle="tab" data-bs-target="#staff" type="button" role="tab" aria-controls="staff" aria-selected="false">Staff</button>
          </li>
            <li class="nav-item" role="presentation">
            <button class="nav-link" id="class-tab" data-bs-toggle="tab" data-bs-target="#class" type="button" role="tab" aria-controls="class" aria-selected="false">CLass</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="subject-tab" data-bs-toggle="tab" data-bs-target="#subject" type="button" role="tab" aria-controls="subject" aria-selected="false">Subject</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="department-tab" data-bs-toggle="tab" data-bs-target="#department" type="button" role="tab" aria-controls="department" aria-selected="false">Department</button>
          </li>
        </ul>
        <div class="tab-content" id="profileTabsContent">
          <!-- Students Section -->
          <div class="tab-pane fade show active" id="students" role="tabpanel" aria-labelledby="students-tab">
                <div class="card">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-center mb-2">
                      <h5 class="card-title">Students under {{ $school->name }}</h5>
                  </div>

                  <div class="table-responsive">
                    <table
                      id="zero_config"
                      class="table table-striped table-bordered"
                    >
                      <thead>
                        <tr>
                          <th>Picture</th>
                          <th>Name</th>
                          <th>Class</th>
                          <th>Age</th>
                          <th>Status</th>
                          <th>Created At</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                    @foreach($students as $student)
                      <tbody>
                        <tr>
                          <td><img 
                            src="{{ $student->avatar }}" 
                            alt="{{ $student->name }}" 
                            class="img-thumbnail" 
                            style="width: 150px; height: 150px; object-fit: cover;" 
                          />
                          </td>
                          <td>{{ $student->name }}</td>
                          <td>{{ $student->class }} {{ $student->department }}</td>
                          <td>{{ $student->age }}</td>
                         <td class="{{ $student->status === 'ACTIVE' ? 'text-success' : 'text-danger' }}">
                              {{ $student->status }}
                          </td>
                          <td>{{ $student->created_at ? $student->created_at->format('F j, Y g:i A') : 'N/A' }}</td>
                          <td class="gap-2"><a  href="{{ url('admin/view/students/' . $student->id) }}"><button class="btn btn-info m-2">View</button></a>
                          </td>
                        </tr>
                      <tfoot>
                      @endforeach
                        <tr>
                          <th>Picture</th>
                          <th>Name</th>
                          <th>Class</th>
                          <th>Age</th>
                          <th>Status</th>
                          <th>Created At</th>
                          <th>Action</th>
                        </tr>
                      </tfoot>
                    </table>
                  </div>
                </div>
              </div>
          </div>
          <!-- Staff Section -->
          <div class="tab-pane fade" id="staff" role="tabpanel" aria-labelledby="staff-tab">
                  <div class="card">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-center mb-2">
                      <h5 class="card-title">Staff under {{ $school->name }}</h5>
                      <a href="{{ url('admin/add/schools') }}">
                      <button class="btn btn-primary">Add New School</button>
                    </a>
                  </div>

                  <div class="table-responsive">
                    <table
                      id="zero_config"
                      class="table table-striped table-bordered"
                    >
                      <thead>
                        <tr>
                          <th>Picture</th>
                          <th>Name</th>
                          <th>Email</th>
                          <th>Class | Department</th>
                          <th>Status</th>
                          <th>Created At</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                     @foreach($staffs as $staff)
                      <tbody>
                        <tr>
                          <td><img 
                            src="{{ $staff->avatar }}" 
                            alt="{{ $staff->name }}" 
                            class="img-thumbnail" 
                            style="width: 150px; height: 150px; object-fit: cover;" 
                          />
                          </td>
                          <td>{{ $staff->name }}</td>
                          <td>{{ $staff->email }}</td>
                          <td>{{ is_array($staff->class) ? implode(', ', $staff->class) : $staff->class }} | {{ $staff->department ?? 'N/A' }} | </td>
                         <td class="{{ $staff->status === 'ACTIVE' ? 'text-success' : 'text-danger' }}">
                              {{ $staff->status }}
                          </td>
                          <td>{{ $staff->created_at ? $staff->created_at->format('F j, Y g:i A') : 'N/A' }}</td>
                          <td class="gap-2"><a  href="{{ url('admin/view/staffs/' . $staff->id) }}"><button class="btn btn-info m-2">View</button></a>
                          </td>
                        </tr>
                      <tfoot>
                      @endforeach
                        <tr>
                          <th>Picture</th>
                          <th>Name</th>
                          <th>Email</th>
                          <th>Class / Department</th>
                          <th>Status</th>
                          <th>Created At</th>
                          <th>Action</th>
                        </tr>
                      </tfoot>
                    </table>
                  </div>
                </div>
              </div>
          </div>
          <!-- Class Section -->
          <div class="tab-pane fade" id="class" role="tabpanel" aria-labelledby="class-tab">
                  <div class="card">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-center mb-2">
                      <h5 class="card-title">Class under {{ $school->name }}</h5>
                  </div>

                  <div class="table-responsive">
                    <table
                      id="zero_config"
                      class="table table-striped table-bordered"
                    >
                      <thead>
                        <tr>
                          <th>Name</th>
                          <th>Description</th>
                          <th>Created At</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                     @foreach($classes as $class)
                      <tbody>
                        <tr>
                          <td>{{ $class->name }}</td>
                          <td>{{ $class->description }}</td>
                          <td>{{ $class->created_at ? $class->created_at->format('F j, Y g:i A') : 'N/A' }}</td>
                          <td class="gap-2"><a  href="{{ url('admin/view/class/' . $class->id) }}"><button class="btn btn-info m-2">View</button></a>
                          </td>
                        </tr>
                      <tfoot>
                      @endforeach
                        <tr>
                          <th>Name</th>
                          <th>Description</th>
                          <th>Created At</th>
                          <th>Action</th>
                        </tr>
                      </tfoot>
                    </table>
                  </div>
                </div>
              </div>
          </div>
          <!-- Class Section -->
          <div class="tab-pane fade" id="subject" role="tabpanel" aria-labelledby="subject-tab">
                  <div class="card">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-center mb-2">
                      <h5 class="card-title">Subject under {{ $school->name }}</h5>
                  </div>

                  <div class="table-responsive">
                    <table
                      id="zero_config"
                      class="table table-striped table-bordered"
                    >
                      <thead>
                        <tr>
                          <th>Name</th>
                          <th>Department</th>
                          <th>Created At</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                     @foreach($subjects as $subject)
                      <tbody>
                        <tr>
                          <td>{{ $subject->name }}</td>
                          <td>{{ $subject->department }}</td>
                          <td>{{ $subject->created_at ? $staff->created_at->format('F j, Y g:i A') : 'N/A' }}</td>
                          <td class="gap-2"><a  href="{{ url('admin/view/class/' . $subject->id) }}"><button class="btn btn-info m-2">View</button></a>
                          </td>
                        </tr>
                      <tfoot>
                      @endforeach
                        <tr>
                          <th>Name</th>
                          <th>Department</th>
                          <th>Created At</th>
                          <th>Action</th>
                        </tr>
                      </tfoot>
                    </table>
                  </div>
                </div>
              </div>
          </div>
          <div class="tab-pane fade" id="department" role="tabpanel" aria-labelledby="department-tab">
                  <div class="card">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-center mb-2">
                      <h5 class="card-title">Department under {{ $school->name }}</h5>
                  </div>

                  <div class="table-responsive">
                    <table
                      id="zero_config"
                      class="table table-striped table-bordered"
                    >
                      <thead>
                        <tr>
                          <th>Name</th>
                          <th>Description</th>
                          <th>Created At</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                     @foreach($departments as $department)
                      <tbody>
                        <tr>
                          <td>{{ $department->name }}</td>
                          <td>{{ $department->description }}</td>
                          <td>{{ $department->created_at ? $department->created_at->format('F j, Y g:i A') : 'N/A' }}</td>
                          <td class="gap-2"><a  href="{{ url('admin/view/class/' . $department->id) }}"><button class="btn btn-info m-2">View</button></a>
                          </td>
                        </tr>
                      <tfoot>
                      @endforeach
                        <tr>
                          <th>Name</th>
                          <th>Description</th>
                          <th>Created At</th>
                          <th>Action</th>
                        </tr>
                      </tfoot>
                    </table>
                  </div>
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
            All Rights Reserved by SoftPen Technologies | Developed by Softpen Tech
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
