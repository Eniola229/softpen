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
      @include('components.school-nav') 
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
                      View Teacher Profile
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
              <img src="{{ $teacher->avatar }}" class="img-fluid rounded-start h-100" alt="teacher Image">
            </div>
            <!-- Teacher Details Section -->
            <div class="col-md-8">
              <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                  <h5 class="card-title mb-0">Teacher Details</h5>
                </div>
                <div class="card-body p-4">
                  
                  <!-- Teacher Name -->
                  <div class="row mb-3">
                    <div class="col-12">
                      <div class="d-flex">
                        <span class="text-muted" style="min-width: 150px;">Teacher Name</span>
                        <strong>{{ $teacher->name }}</strong>
                      </div>
                    </div>
                  </div>

                  <!-- Email -->
                  <div class="row mb-3">
                    <div class="col-12">
                      <div class="d-flex">
                        <span class="text-muted" style="min-width: 150px;">Email</span>
                        <strong>{{ $teacher->email }}</strong>
                      </div>
                    </div>
                  </div>

                  <!-- Mobile -->
                  <div class="row mb-3">
                    <div class="col-12">
                      <div class="d-flex">
                        <span class="text-muted" style="min-width: 150px;">Mobile</span>
                        <strong>{{ $teacher->mobile }}</strong>
                      </div>
                    </div>
                  </div>

                  <!-- Address -->
                  <div class="row mb-3">
                    <div class="col-12">
                      <div class="d-flex">
                        <span class="text-muted" style="min-width: 150px;">Address</span>
                        <strong>{{ $teacher->address }}</strong>
                      </div>
                    </div>
                  </div>

                  <!-- Department -->
                  <div class="row mb-3">
                    <div class="col-12">
                      <div class="d-flex">
                        <span class="text-muted" style="min-width: 150px;">Department</span>
                        <strong>{{ $teacher->department ?? 'N/A' }}</strong>
                      </div>
                    </div>
                  </div>

                  <!-- Class -->
                  <div class="row mb-3">
                    <div class="col-12">
                      <div class="d-flex">
                        <span class="text-muted" style="min-width: 150px;">Class</span>
                        <strong>{{ is_array($teacher->class) ? implode(', ', $teacher->class) : $teacher->class }}</strong>
                      </div>
                    </div>
                  </div>

                  <!-- Subject -->
                  <div class="row mb-4">
                    <div class="col-12">
                      <div class="d-flex">
                        <span class="text-muted" style="min-width: 150px;">Subject</span>
                        <strong>{{ implode(', ', $subjectNames) }}</strong>
                      </div>
                    </div>
                  </div>

                  <!-- Action Buttons -->
                  <div class="row">
                    <div class="col-12">
                      <div class="d-flex flex-wrap gap-2">
                        @if ($teacher->status === 'ACTIVE')
                          <button class="btn btn-warning btn-sm" onclick="confirmStatusChange('{{ url('school/change', $teacher->id) }}')">
                            Deactivate Account
                          </button>
                        @elseif ($teacher->status === 'DISACTIVATE')
                          <button class="btn btn-success btn-sm" onclick="confirmStatusChange('{{ url('school/change', $teacher->id) }}')">
                            Activate Account
                          </button>
                        @endif
                        
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editSchoolModal">
                          Edit Teacher
                        </button>
                        
                        <button class="btn btn-danger btn-sm" onclick="confirmStatusChange('{{ url('school/teacher/delete', $teacher->id) }}')">
                          Delete Teacher
                        </button>
                      </div>
                    </div>
                  </div>

                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Modal -->
      <div class="modal fade" id="editSchoolModal" tabindex="-1" aria-labelledby="editSchoolModalLabel" aria-hidden="true">
          <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="editSchoolModalLabel">Edit Teacher</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                      <form action="{{ route('school/add/teacher') }}" method="POST" enctype="multipart/form-data"> 
                          @csrf
                          <input type="hidden" name="id" value="{{ $teacher->id }}">

                          <div class="mb-3">
                              <label for="name" class="form-label">Teacher Name</label>
                              <input type="text" class="form-control" id="name" name="name" 
                                     value="{{ old('name', $teacher->name) }}" required>
                          </div>

                          <div class="mb-3">
                              <label for="email" class="form-label">Email</label>
                              <input type="email" class="form-control" id="email" name="email" 
                                     value="{{ old('email', $teacher->email) }}" required>
                          </div>

                          <div class="mb-3">
                              <label for="address" class="form-label">Address</label>
                              <input type="text" class="form-control" id="address" name="address" 
                                     value="{{ old('address', $teacher->address) }}" required>
                          </div>

                          <div class="mb-3">
                              <label for="mobile" class="form-label">Mobile</label>
                              <input type="text" class="form-control" id="mobile" name="mobile" 
                                     value="{{ old('mobile', $teacher->mobile) }}" required>
                          </div>

                          <!-- Multi-Select Class -->
                          <div class="mb-3">
                              <label for="class">Class</label>
                              <select id="class" name="class[]" class="form-control" multiple size="6" style="height:auto;">
                                  @php
                                      $selectedClasses = collect(old('class', $teacher->class ?? []));
                                  @endphp
                                  @foreach ($classes as $c)
                                      <option value="{{ $c->name }}" 
                                          {{ $selectedClasses->contains($c->name) ? 'selected' : '' }}>
                                          {{ $c->name }}
                                      </option>
                                  @endforeach
                              </select>
                              <small class="text-muted">Hold CTRL (Windows) or CMD (Mac) to select multiple.</small>
                          </div>

                          <!-- Multi-Select Subject -->
                          <div class="mb-3">
                              <label for="subject">Subject</label>
                              <select id="subject" name="subject[]" class="form-control" multiple size="6" style="height:auto;">
                                  @php
                                      $selectedSubjects = collect(old('subject', $teacher->subject ?? []));
                                  @endphp
                                  @foreach ($subjects as $s)
                                      <option value="{{ $s->id }}" 
                                          {{ $selectedSubjects->contains($s->name) ? 'selected' : '' }}>
                                          {{ $s->name }} - {{ $s->for }} 
                                      </option>
                                  @endforeach
                              </select>
                              <small class="text-muted">Hold CTRL (Windows) or CMD (Mac) to select multiple.</small>
                          </div>

                          <!-- Department -->
                          <div class="mb-3">
                              <label for="department" class="form-label">Department (Optional)</label>
                              <select id="department" name="department" class="form-control">
                                  <option value="">-- Select Department --</option>
                                  @foreach ($depts as $dept)
                                      <option value="{{ $dept->name }}" 
                                          {{ old('department', $teacher->department) == $dept->name ? 'selected' : '' }}>
                                          {{ $dept->name }}
                                      </option>
                                  @endforeach
                              </select>
                          </div>

                          <div class="mb-3">
                              <label for="avatar" class="form-label">Teacher Passport</label>
                              <input type="file" class="form-control" id="avatar" name="avatar">
                              <small class="form-text text-muted">Leave blank to keep the current image.</small>
                          </div>

                          <div class="mb-3">
                              <label for="password" class="form-label">Password</label>
                              <input type="password" class="form-control" id="password" name="password">
                              <small class="form-text text-muted">Leave blank to keep the current password.</small>
                          </div>

                          <button type="submit" class="btn btn-success">Update Teacher</button>
                      </form>
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
              text: "You are about this action?",
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
