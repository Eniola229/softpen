@include('components.p-header') 
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

<div id="main-wrapper"
  data-layout="vertical"
  data-navbarbg="skin5"
  data-sidebartype="full"
  data-sidebar-position="absolute"
  data-header-position="absolute"
  data-boxed-layout="full">

  @include('components.nav')
  @include('components.p-side-nav')

  <div class="page-wrapper">

    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
          <h4 class="page-title">Profile</h4>
          <div class="ms-auto text-end">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Softpen</a></li>
                <li class="breadcrumb-item active" aria-current="page">Profile</li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
    </div>

    <div class="container my-5">
      @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
      @endif

      @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
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

      <div class="card mb-4 shadow">
        <div class="row g-0">
          <div class="col-md-8">
            <div class="card-body">
              <h2 class="card-title">{{ Auth::user()->name }}</h2>
              <p class="card-text"><strong>Email:</strong> {{ Auth::user()->email }}</p>
              <p class="card-text">
              <strong>Wallet Balance:</strong>
              {{ isset(Auth::user()->balance) ? '₦' . number_format(Auth::user()->balance, 2) : 'N/A' }}
            </p>

            </div>
          </div>
        </div>
      </div>

      <ul class="nav nav-tabs" id="profileTabs" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active" id="edit-tab" data-bs-toggle="tab" data-bs-target="#edits" type="button" role="tab">Edit Profile</button>
        </li>
      </ul>

      <div class="tab-content" id="profileTabsContent">

        <div class="tab-pane fade show active" id="edits" role="tabpanel">
          <div class="card">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="card-title">Profile name {{ Auth::user()->name }}</h5>
              </div>

              <div class="card">
                <form action="{{ route('profile.update') }}" method="POST">
                  @csrf
                  
                  <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" class="form-control" name="name"
                      value="{{ old('name', Auth::user()->name) }}" required>
                  </div>

                  <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email"
                      value="{{ old('email', Auth::user()->email) }}" required>
                  </div>

                  <div class="mb-3">
                    <label class="form-label">Class</label>
                    <input type="text" class="form-control" name="class"
                      value="{{ old('class', Auth::user()->class) }}">
                  </div>

                  <div class="mb-3">
                    <label class="form-label">School</label>
                    <input type="text" class="form-control" name="school"
                      value="{{ old('school', Auth::user()->school) }}">
                  </div>


                  <div class="mb-3">
                    <label class="form-label">Department</label>
                    <input type="text" class="form-control" name="department"
                      value="{{ old('department', Auth::user()->department ?? 'N/A') }}">
                  </div>

                  <div class="mb-3">
                    <label class="form-label">New Password</label>
                    <input type="password" class="form-control" name="password">
                    <small class="form-text text-muted">Leave blank to keep the current password.</small>
                  </div>

                  <button class="btn btn-success">Update Profile</button>
                </form>
              </div>

            </div>
          </div>
        </div>

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
</script>

</body>
</html>
