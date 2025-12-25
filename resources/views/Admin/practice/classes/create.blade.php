@include('components.header')
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
            <h4 class="page-title">Create Practice Class</h4>
            <div class="ms-auto text-end">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#">Softpen</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('classes.index') }}">Practice Classes</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Create</li>
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

      <div class="container-fluid">
        <div class="card">
          <div class="card-body">
            <h1 class="card-title">Create New Practice Class</h1>
            <h6 class="card-subtitle mb-4">Add a new practice class for students</h6>

            <form action="{{ route('classes.store') }}" method="POST">
              @csrf

              <div class="form-group mb-3">
                <label for="name">Class Name *</label>
                <input type="text" id="name" name="name" class="form-control" placeholder="e.g., JSS 1, SS 2, Year 7" value="{{ old('name') }}" required>
              </div>

          <div class="form-group mb-3">
              <label for="subject">Subject *</label>
              <select id="subject" name="subject" class="form-control select2" required>
                  <option value="">-- Select Subject --</option>

                  <!-- PRIMARY SCHOOL SUBJECTS -->
                  <optgroup label="Primary School">
                      <option value="english">English Language</option>
                      <option value="mathematics">Mathematics</option>
                      <option value="basic_science">Basic Science</option>
                      <option value="social_studies">Social Studies</option>
                      <option value="yoruba">Yoruba</option>
                      <option value="igbo">Igbo</option>
                      <option value="hausa">Hausa</option>
                      <option value="civic_education">Civic Education</option>
                      <option value="religious_knowledge">Religious Knowledge</option>
                      <option value="physical_education">Physical Education</option>
                  </optgroup>

                  <!-- JUNIOR SECONDARY SCHOOL (JSS1-JSS3) -->
                  <optgroup label="Junior Secondary School (JSS)">
                      <option value="english">English Language</option>
                      <option value="mathematics">Mathematics</option>
                      <option value="basic_science">Basic Science</option>
                      <option value="basic_technology">Basic Technology</option>
                      <option value="social_studies">Social Studies</option>
                      <option value="yoruba">Yoruba</option>
                      <option value="igbo">Igbo</option>
                      <option value="hausa">Hausa</option>
                      <option value="civic_education">Civic Education</option>
                      <option value="religious_knowledge">Religious Knowledge</option>
                      <option value="computer_studies">Computer Studies</option>
                      <option value="physical_education">Physical Education</option>
                  </optgroup>

                  <!-- SENIOR SECONDARY SCHOOL (SS1-SS3) -->
                  <optgroup label="Senior Secondary School (SS)">
                      <option value="english">English Language</option>
                      <option value="mathematics">Mathematics</option>
                      <option value="physics">Physics</option>
                      <option value="chemistry">Chemistry</option>
                      <option value="biology">Biology</option>
                      <option value="economics">Economics</option>
                      <option value="commerce">Commerce</option>
                      <option value="government">Government</option>
                      <option value="literature">Literature in English</option>
                      <option value="accounting">Accounting</option>
                      <option value="financial_accounting">Financial Accounting</option>
                      <option value="computer_science">Computer Science</option>
                      <option value="yoruba">Yoruba</option>
                      <option value="igbo">Igbo</option>
                      <option value="hausa">Hausa</option>
                      <option value="civic_education">Civic Education</option>
                      <option value="religious_knowledge">Religious Knowledge</option>
                      <option value="physical_education">Physical Education</option>
                  </optgroup>

              </select>
          </div>

              <div class="form-group mt-4">
                <button type="submit" class="btn btn-primary">
                  <i class="fas fa-save"></i> Create Practice Class
                </button>
                <a href="{{ route('classes.index') }}" class="btn btn-secondary">
                  <i class="fas fa-times"></i> Cancel
                </a>
              </div>
            </form>
          </div>
        </div>
      </div>

      <footer class="footer text-center">
        All Rights Reserved by SoftPenTech | Developed by SoftpenTech
      </footer>
    </div>
  </div>

  <script>
  $(document).ready(function() {
      $('#subject').select2({
          placeholder: "-- Select Subject --",
          allowClear: true,
          width: '100%'
      });
  });
  </script>


  <script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
  <script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js') }}"></script>
  <script src="{{ asset('assets/extra-libs/sparkline/sparkline.js') }}"></script>
  <script src="{{ asset('dist/js/waves.js') }}"></script>
  <script src="{{ asset('dist/js/sidebarmenu.js') }}"></script>
  <script src="{{ asset('dist/js/custom.min.js') }}"></script>
</body>
</html>