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
            <h4 class="page-title">Exam Instructions</h4>
            <div class="ms-auto text-end">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{ route('practice.index') }}">Practice</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('practice.exams', $class->id) }}">{{ $class->name }}</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Instructions</li>
                </ol>
              </nav>
            </div>
          </div>
        </div>
      </div>

      @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          {{ session('error') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      <div class="container-fluid">
        <div class="row justify-content-center">
          <div class="col-lg-8">
            <div class="card">
              <div class="card-body">
                <h2 class="card-title">{{ $exam->title }}</h2>
                <p class="text-muted">{{ $exam->subject }} | {{ $class->name }}</p>

                <div class="alert alert-warning">
                  <strong><i class="mdi mdi-alert"></i> Important Notice:</strong><br>
                  ₦20 will be deducted from your balance when you start this exam.<br>
                  Current Balance: <strong>₦{{ number_format($user->balance, 2) }}</strong>
                </div>

                <h5 class="mt-4">Exam Details</h5>
                <ul class="list-group list-group-flush">
                  <li class="list-group-item d-flex justify-content-between">
                    <span><i class="mdi mdi-clock-outline text-primary"></i> Duration</span>
                    <strong>{{ $exam->duration }} minutes</strong>
                  </li>
                  <li class="list-group-item d-flex justify-content-between">
                    <span><i class="mdi mdi-help-circle-outline text-primary"></i> Total Questions</span>
                    <strong>{{ $exam->total_questions }}</strong>
                  </li>
                  <li class="list-group-item d-flex justify-content-between">
                    <span><i class="mdi mdi-check-circle-outline text-primary"></i> Passing Score</span>
                    <strong>{{ $exam->passing_score }}%</strong>
                  </li>
                  <li class="list-group-item d-flex justify-content-between">
                    <span><i class="mdi mdi-lightbulb-outline text-primary"></i> Explanations</span>
                    <strong>
                      @if($exam->show_explanations)
                        <span class="badge bg-success">Available</span>
                      @else
                        <span class="badge bg-secondary">Not Available</span>
                      @endif
                    </strong>
                  </li>
                </ul>

                @if($exam->instructions)
                  <h5 class="mt-4">Instructions</h5>
                  <div class="alert alert-info">
                    {!! nl2br(e($exam->instructions)) !!}
                  </div>
                @endif

                <h5 class="mt-4">General Instructions</h5>
                <ul>
                  <li>This is a practice exam to help you prepare for the real test</li>
                  <li>You will see detailed explanations after submitting your answers</li>
                  <li>Make sure you have a stable internet connection</li>
                  <li>Once you start, the timer will begin counting down</li>
                  <li>You must complete the exam before the time runs out</li>
                  <li>You can review your answers before final submission</li>
                </ul>

                <div class="d-flex justify-content-between mt-4">
                  <a href="{{ route('practice.exams', $class->id) }}" class="btn btn-secondary">
                    <i class="mdi mdi-arrow-left"></i> Cancel
                  </a>
                  <form action="{{ route('practice.start', [$class->id, $exam->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to start this exam? ₦20 will be deducted from your balance.');">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-lg">
                      <i class="mdi mdi-play"></i> Start Exam (Deduct ₦20)
                    </button>
                  </form>
                </div>
              </div>
            </div>
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