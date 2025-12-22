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
    @include('components.side-nav')

    <div class="page-wrapper">
      <div class="page-breadcrumb">
        <div class="row">
          <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">Practice Classes</h4>
            <div class="ms-auto text-end">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#">Softpen</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Practice Classes</li>
                </ol>
              </nav>
            </div>
          </div>
        </div>
      </div>

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
            <h5 class="card-title">Practice Classes</h5>
            <a href="{{ route('classes.create') }}">
              <button class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Practice Class
              </button>
            </a>
          </div>

          @if($classes->count() > 0)
            <div class="table-responsive">
              <table id="classes_table" class="table table-striped table-bordered">
                <thead>
                  <tr>
                    <th>Name</th>
                    <th>Subject</th>
                    <th>Created At</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($classes as $class)
                    <tr>
                      <td>{{ $class->name }}</td>
                      <td>{{ $class->subject }}</td>
                      <td>{{ $class->created_at->format('M d, Y') }}</td>
                      <td>
                        <a href="{{ route('exams.index', $class->id) }}">
                          <button class="btn btn-info btn-sm" title="View Exams">
                            <i class="fas fa-eye"></i> View Exams
                          </button>
                        </a>
                        <a href="{{ route('classes.edit', $class->id) }}">
                          <button class="btn btn-warning btn-sm" title="Edit Class">
                            <i class="fas fa-edit"></i> Edit
                          </button>
                        </a>
                        <button class="btn btn-danger btn-sm" onclick="confirmDelete('{{ route('classes.destroy', $class->id) }}', '{{ $class->name }}')" title="Delete Class">
                          <i class="fas fa-trash"></i> Delete
                        </button>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>

            <div class="d-flex justify-content-center mt-3">
              {{ $classes->links() }}
            </div>
          @else
            <div class="alert alert-info">
              <strong>No Practice Classes Found</strong><br>
              There are currently no practice classes.
              <a href="{{ route('classes.create') }}" class="alert-link">Create one now</a>
            </div>
          @endif
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
    $("#classes_table").DataTable();

    function confirmDelete(url, className) {
      Swal.fire({
        title: "Are you sure?",
        text: "You are about to delete the practice class: " + className,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
      }).then((result) => {
        if (result.isConfirmed) {
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