@include('components.header') 
<link href="{{ asset('assets/libs/jquery-steps/jquery.steps.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/libs/jquery-steps/steps.css') }}" rel="stylesheet" />
<link href="{{ asset('dist/css/style.min.css') }}" rel="stylesheet" />
<body>
<div class="preloader">
  <div class="lds-ripple"><div class="lds-pos"></div><div class="lds-pos"></div></div>
</div>
<div id="main-wrapper" data-layout="vertical" data-navbarbg="skin5" data-sidebartype="full" 
     data-sidebar-position="absolute" data-header-position="absolute" data-boxed-layout="full">
  @include('components.nav')
  @include('components.student-nav')
  <div class="page-wrapper">
    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
          <h4 class="page-title">Start Exam: {{ $exam->title }}</h4>
          <div class="ms-auto text-end">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Softpen</a></li>
                <li class="breadcrumb-item"><a href="#">{{ $subject->name ?? 'N/A' }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Start Exam</li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
    </div>

    <div class="container-fluid">
      @if(session('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          {{ session('message') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      @endif

      <div class="row">
        {{-- Exam Info Card --}}
        <div class="col-md-4 mb-4">
          <div class="card h-100 border-0 shadow-sm">
            <div class="card-header bg-primary text-white">
              <h5 class="mb-0"><i class="mdi mdi-information-outline me-2"></i>Exam Details</h5>
            </div>
            <div class="card-body">
              <ul class="list-unstyled mb-0">
                <li class="mb-3">
                  <small class="text-muted d-block">Subject</small>
                  <strong>{{ $subject->name ?? 'N/A' }}</strong>
                </li>
                <li class="mb-3">
                  <small class="text-muted d-block">Duration</small>
                  <strong>{{ $exam->duration }} minutes</strong>
                </li>
                <li class="mb-3">
                  <small class="text-muted d-block">Total Questions</small>
                  <strong>{{ $exam->total_questions }}</strong>
                </li>
                <li class="mb-3">
                  <small class="text-muted d-block">Passing Score</small>
                  <strong>{{ $exam->passing_score }}%</strong>
                </li>
                <li class="mb-3">
                  <small class="text-muted d-block">Session</small>
                  <strong>{{ $exam->session ?? 'N/A' }}</strong>
                </li>
                <li class="mb-0">
                  <small class="text-muted d-block">Term</small>
                  <strong>{{ $exam->term ?? 'N/A' }}</strong>
                </li>
              </ul>
            </div>
          </div>
        </div>

        {{-- Instructions + Start --}}
        <div class="col-md-8 mb-4">
          <div class="card border-0 shadow-sm">
            <div class="card-body wizard-content">
              <h4 class="card-title">{{ $exam->title }}</h4>
              <p class="text-muted">Please read the instructions carefully before starting.</p>

              <form id="start-exam-form" method="GET" action="{{ route('student.exam.take', $exam->id) }}">
                <div>
                  <h3>Instructions</h3>
                  <section>
                    @if($exam->instructions)
                      <div class="alert alert-info">
                        <i class="mdi mdi-alert-circle-outline me-2"></i>
                        {!! nl2br(e($exam->instructions)) !!}
                      </div>
                    @else
                      <p class="text-muted">No specific instructions provided for this exam.</p>
                    @endif

                    <div class="alert alert-warning mt-3">
                      <strong><i class="mdi mdi-shield-alert me-1"></i> General Rules:</strong>
                      <ul class="mb-0 mt-2">
                        <li>Do not close the browser during the exam.</li>
                        <li>Your answers are saved automatically.</li>
                        <li>The exam will submit automatically when time runs out.</li>
                        <li>You cannot retake the exam once submitted.</li>
                      </ul>
                    </div>
                  </section>

                  <h3>Start</h3>
                  <section>
                    <div class="text-center py-4">
                      <div class="mb-4">
                        <div class="exam-ready-badge">
                          <i class="mdi mdi-check-circle" style="font-size:3rem; color:#28a745;"></i>
                          <h5 class="mt-2 mb-1">Exam Code Verified</h5>
                          <p class="text-muted mb-0">
                            Code: <strong class="text-primary">{{ $exam->exam_code }}</strong>
                          </p>
                        </div>
                      </div>
                      <p class="text-muted mb-4">You are about to start a <strong>{{ $exam->duration }}-minute</strong> exam. Once started, the timer cannot be paused.</p>
                      <button type="submit" class="btn btn-success btn-lg px-5" id="startExamBtn">
                        <i class="mdi mdi-play-circle me-2"></i> Start Exam Now
                      </button>
                    </div>
                  </section>
                </div>
              </form>

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

<script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('dist/js/custom.min.js') }}"></script>
<script src="{{ asset('assets/libs/jquery-steps/build/jquery.steps.min.js') }}"></script>
<script src="{{ asset('assets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>

<script>
var form = $("#start-exam-form");
form.validate();
form.children("div").steps({
  headerTag: "h3",
  bodyTag: "section",
  transitionEffect: "slideLeft",
  labels: {
    finish: "Start Exam",
    next: "Next",
    previous: "Back"
  },
  onFinishing: function(){ return form.valid(); },
  onFinished: function(){
    document.getElementById('startExamBtn') && (document.getElementById('startExamBtn').disabled = true);
    form.submit();
  }
});
</script>

<style>
.exam-ready-badge {
  display: inline-block;
  background: #f8fff9;
  border: 2px dashed #28a745;
  border-radius: 12px;
  padding: 20px 40px;
}
</style>
</body>
</html>