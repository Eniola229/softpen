@include('components.p-header') 
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
    @include('components.p-side-nav')

    <div class="page-wrapper">
      <div class="page-breadcrumb">
        <div class="row">
          <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">{{ $class->name }} - Practice Exams</h4>
            <div class="ms-auto text-end">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{ route('practice.index') }}">Practice</a></li>
                  <li class="breadcrumb-item active" aria-current="page">{{ $class->name }}</li>
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

      <div class="container-fluid">
        <!-- Balance Display -->
        <div class="alert alert-info">
          <strong>Your Balance:</strong> ₦{{ number_format($user->balance, 2) }} | 
          <strong>Cost per exam:</strong> ₦20.00
        </div>

        <!-- Available Exams -->
        <div class="row">
          @if($exams->count() > 0)
            @foreach($exams as $exam)
              <div class="col-md-6 col-lg-4">
                <div class="card">
                  <div class="card-body">
                    <h5 class="card-title">{{ $exam->title }}</h5>
                    <p class="text-muted">{{ $exam->subject }}</p>
                    
                    @if($exam->description)
                      <p class="card-text">{{ Str::limit($exam->description, 100) }}</p>
                    @endif

                    <ul class="list-unstyled">
                      <li><i class="mdi mdi-clock-outline"></i> Duration: {{ $exam->duration }} minutes</li>
                      <li><i class="mdi mdi-help-circle-outline"></i> Questions: {{ $exam->total_questions }}</li>
                      <li><i class="mdi mdi-check-circle-outline"></i> Pass Score: {{ $exam->passing_score }}%</li>
                      <li><i class="mdi mdi-lightbulb-outline"></i> Explanations: 
                        @if($exam->show_explanations)
                          <span class="badge bg-success">Yes</span>
                        @else
                          <span class="badge bg-secondary">No</span>
                        @endif
                      </li>
                    </ul>

                    <div class="d-grid gap-2 mt-3">
                      <a href="{{ route('practice.instructions', [$class->id, $exam->id]) }}" class="btn btn-primary">
                        <i class="mdi mdi-play"></i> Start Practice (₦20)
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            @endforeach
          @else
            <div class="col-12">
              <div class="alert alert-warning">
                <strong>No Practice Exams Available</strong><br>
                There are currently no practice exams available for {{ $class->name }}.
              </div>
            </div>
          @endif
        </div>

        <div class="row mt-3">
          <div class="col-12">
            <a href="{{ route('practice.index') }}" class="btn btn-secondary">
              <i class="mdi mdi-arrow-left"></i> Back
            </a>
          </div>
        </div>
      </div>

      <footer class="footer text-center">
        All Rights Reserved by SoftPen Technologies | Develop by Softpen Tech
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
</body>
</html>