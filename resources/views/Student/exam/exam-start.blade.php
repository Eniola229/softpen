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
                <li class="breadcrumb-item"><a href="#">{{ $exam->subject->name ?? 'N/A' }}</a></li>
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

      <div class="card">
        <div class="card-body wizard-content">
          <h1 class="card-title">{{ $exam->title }}</h1>
          <h6 class="card-subtitle">Exam instructions and start</h6>

          <form id="start-exam-form" method="GET" action="{{ route('student.exam.take', $exam->id) }}">
            <div>
              <h3>Instructions</h3>
              <section>
                <p>{{ $exam->instructions ?? 'No instructions provided for this exam.' }}</p>
              </section>

              <h3>Start</h3>
              <section>
                <button type="submit" class="btn btn-primary">Start Exam</button>
              </section>
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
  onFinishing: function(){ return form.valid(); },
  onFinished: function(){ form.submit(); }
});
</script>
</body>
</html>
