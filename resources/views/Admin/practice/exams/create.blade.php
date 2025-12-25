@include('components.header') 
<link href="{{ asset('assets/libs/jquery-steps/jquery.steps.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/libs/jquery-steps/steps.css') }}" rel="stylesheet" />
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
            <h4 class="page-title">Create Practice Exam</h4>
            <div class="ms-auto text-end">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#">Softpen</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('classes.index') }}">Practice Classes</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('exams.index', $class->id) }}">{{ $class->name }}</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Create Exam</li>
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
          <div class="card-body wizard-content">
            <h1 class="card-title">Create Practice Exam for {{ $class->name }}</h1>
            <h6 class="card-subtitle">Follow the steps to create a new practice exam</h6>

            <form id="exam-form" action="{{ route('exams.store', $class->id) }}" method="post" class="mt-5">
              @csrf
              <div>
                <!-- Step 1: Basic Information -->
                <h3>Basic Information</h3>
                <section>
                  <label for="title">Exam Title *</label>
                  <input id="title" name="title" type="text" class="required form-control" placeholder="e.g., Mathematics Practice Test 1" value="{{ old('title') }}" />
                  
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

                  
                  <div class="form-group mb-3 mt-2">
                    <label for="department">Department (Optional)</label>
                      <select id="department" name="department" class="form-control select2">
                          <option value="">-- Select Department --</option>
                          <option value="science" {{ old('department') == 'science' ? 'selected' : '' }}>Science</option>
                          <option value="art" {{ old('department') == 'art' ? 'selected' : '' }}>Art</option>
                          <option value="commercial" {{ old('department') == 'commercial' ? 'selected' : '' }}>Commercial</option>
                      </select>
                  </div>


                  <label for="description">Description</label>
                  <textarea id="description" name="description" class="form-control" rows="4" placeholder="Add exam description...">{{ old('description') }}</textarea>

                  <p>(*) Mandatory</p>
                </section>

                <!-- Step 2: Exam Settings -->
                <h3>Exam Settings</h3>
                <section>
                  <label for="duration">Duration (minutes) *</label>
                  <input id="duration" name="duration" type="number" class="required form-control" placeholder="e.g., 60" value="{{ old('duration', 60) }}" min="1" />

                  <label for="total_questions">Total Questions *</label>
                  <input id="total_questions" name="total_questions" type="number" class="required form-control" placeholder="e.g., 20" value="{{ old('total_questions', 20) }}" min="1" />

                  <label for="passing_score">Passing Score (%) *</label>
                  <input id="passing_score" name="passing_score" type="number" class="required form-control" placeholder="e.g., 40" value="{{ old('passing_score', 40) }}" min="0" max="100" />

                  <p>(*) Mandatory</p>
                </section>

                <!-- Step 3: Session/Term -->
                <h3>Session/Term</h3>
                <section>
                  <label for="session" class="mt-3">Session *</label>
                  <input id="session" name="session" type="text" class="required form-control" placeholder="e.g., 2024/2025" value="N/A" />

                  <label for="term" class="mt-3">Term *</label>
                  <select id="term" name="term" class="required form-control" required>
                    <option disabled selected>Select a term</option>
                    <option value="First Term" {{ old('term') == 'First Term' ? 'selected' : '' }}>First Term</option>
                    <option value="Second Term" {{ old('term') == 'Second Term' ? 'selected' : '' }}>Second Term</option>
                    <option value="Third Term" {{ old('term') == 'Third Term' ? 'selected' : '' }}>Third Term</option>
                  </select>
                </section>

                <!-- Step 4: Options -->
                <h3>Options</h3>
                <section>
                  <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="randomize_questions" name="randomize_questions" value="1" {{ old('randomize_questions') ? 'checked' : '' }}>
                    <label class="form-check-label" for="randomize_questions">
                      Randomize Questions
                    </label>
                  </div>

                  <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="show_one_question_at_time" name="show_one_question_at_time" value="1" {{ old('show_one_question_at_time') ? 'checked' : '' }}>
                    <label class="form-check-label" for="show_one_question_at_time">
                      Show One Question At A Time
                    </label>
                  </div>

                  <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="show_results" name="show_results" value="1" {{ old('show_results', true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="show_results">
                      Show Results After Submission
                    </label>
                  </div>

                  <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="show_explanations" name="show_explanations" value="1" {{ old('show_explanations', true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="show_explanations">
                      Show Explanations (Practice Mode)
                    </label>
                  </div>
                </section>

                <!-- Step 5: Instructions -->
                <h3>Instructions</h3>
                <section>
                  <label for="instructions">Exam Instructions</label>
                  <textarea id="instructions" name="instructions" class="form-control" rows="6" placeholder="Add instructions for students...">{{ old('instructions') }}</textarea>
                  <small class="text-muted">These instructions will be shown to students before they start the exam</small>
                </section>

                <!-- Step 6: Finish -->
                <h3>Finish</h3>
                <section>
                  <input id="acceptTerms" name="acceptTerms" type="checkbox" class="required" />
                  <label for="acceptTerms">I confirm that all information is correct and want to create this practice exam.</label>
                  <button class="btn btn-primary mt-3" type="submit">
                    <i class="fas fa-save"></i> Create Practice Exam
                  </button>
                  <a href="{{ route('exams.index', $class->id) }}" class="btn btn-secondary mt-3">Cancel</a>
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

  
  <script>
  $(document).ready(function() {
      $('#subject').select2({
          placeholder: "-- Select Subject --",
          allowClear: true,
          width: '100%'
      });
  });
  </script>
<script>
$(document).ready(function() {
    $('#department').select2({
        placeholder: "-- Select Department --",
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
  <script src="{{ asset('assets/libs/jquery-steps/build/jquery.steps.min.js') }}"></script>
  <script src="{{ asset('assets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>

  <script>
    var form = $("#exam-form");
    form.validate({
      errorPlacement: function errorPlacement(error, element) {
        element.before(error);
      },
    });
    form.children("div").steps({
      headerTag: "h3",
      bodyTag: "section",
      transitionEffect: "slideLeft",
      onStepChanging: function (event, currentIndex, newIndex) {
        form.validate().settings.ignore = ":disabled,:hidden";
        return form.valid();
      },
      onFinishing: function (event, currentIndex) {
        form.validate().settings.ignore = ":disabled";
        return form.valid();
      },
      onFinished: function (event, currentIndex) {
        form.submit();
      },
    });
  </script>
</body>
</html>