@include('components.header') 
<link href="{{ asset('assets/libs/jquery-steps/jquery.steps.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/libs/jquery-steps/steps.css') }}" rel="stylesheet" />
<link href="{{ asset('dist/css/style.min.css') }}" rel="stylesheet" />
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
      @include('components.staff-nav') 
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
              <h4 class="page-title">Create New Exam</h4>
              <div class="ms-auto text-end">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Softpen</a></li>
                    <li class="breadcrumb-item"><a href="#">{{ $class->name }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                      Create Exam
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
          <!-- ============================================================== -->
          <!-- Start Page Content -->
          <!-- ============================================================== -->
          <div class="card">
            <div class="card-body wizard-content">
              <h1 class="card-title">Create a new exam for {{ $class->name }}</h1>
              <h6 class="card-subtitle">Fill in the exam details step by step</h6>
              <form id="exam-form" action="{{ route('staff.exams.store', $class->id) }}" method="post" class="mt-5" enctype="multipart/form-data"> 
                @csrf
                <div>
                  <!-- Step 1: Basic Information -->
                  <h3>Basic Information</h3>
                  <section>
                    <label for="title">Exam Title *</label>
                    <input
                      id="title"
                      name="title"
                      type="text"
                      class="required form-control"
                      placeholder="e.g., Mathematics Final Exam"
                      value="{{ old('title') }}"
                    />
                                      
                  <label for="subject">Subject *</label>
                  <select
                    id="subject"
                    name="subject"
                    class="required form-control"
                  >
                    <option value="">-- Select Subject --</option>
                    @foreach($subjects as $subject)
                      <option value="{{ $subject->id }}" {{ old('subject') == $subject->name ? 'selected' : '' }}>
                        {{ $subject->name }} - {{ $subject->for }}
                      </option>
                    @endforeach
                  </select>

                 <label for="subject">Department (Optional)</label>
                  <select
                    id="department"
                    name="department"
                    class="form-control"
                  >
                    <option value="">-- Select department --</option>
                    @foreach($departments as $department)
                      <option value="{{ $department->id }}" {{ old('department') == $department->name ? 'selected' : '' }}>
                        {{ $department->name }}
                      </option>
                    @endforeach
                  </select>

                    <label for="description">Description</label>
                    <textarea
                      id="description"
                      name="description"
                      class="form-control"
                      rows="4"
                      placeholder="Add exam description...">{{ old('description') }}</textarea>

                    <p>(*) Mandatory</p>
                  </section>

                  <!-- Step 2: Exam Settings -->
                  <h3>Exam Settings</h3>
                  <section>
                    <label for="duration">Duration (minutes) *</label>
                    <input
                      id="duration"
                      name="duration"
                      type="number"
                      class="required form-control"
                      placeholder="e.g., 60"
                      value="{{ old('duration') }}"
                      min="1"
                    />

                    <label for="total_questions">Total Questions *</label>
                    <input
                      id="total_questions"
                      name="total_questions"
                      type="number"
                      class="required form-control"
                      placeholder="e.g., 50"
                      value="{{ old('total_questions') }}"
                      min="1"
                    />

                    <label for="passing_score">Passing Score (%) *</label>
                    <input
                      id="passing_score"
                      name="passing_score"
                      type="number"
                      class="required form-control"
                      placeholder="e.g., 40"
                      value="{{ old('passing_score') }}"
                      min="0"
                      max="100"
                    />

                    <label for="exam_date_time" class="mt-3">Exam Date & Time *</label>
                    <input
                      id="exam_date_time"
                      name="exam_date_time"
                      type="datetime-local"
                      class="required form-control"
                      value="{{ old('exam_date_time') }}"
                    />

                    <p>(*) Mandatory</p>
                  </section>
 
                  <!-- Step 3: Exam Options -->
                  <h3>Session/Term</h3>
                  <section>
                      <label for="session" class="mt-3">Session *</label>
                    <input
                      id="session"
                      name="session"
                      type="text"
                      class="required form-control"
                      value="{{ old('session') }}"
                    />
                    <label for="term" class="mt-3">Term *</label>
                    <select
                        id="term"
                        name="term"
                        class="required form-control"
                        required
                    >
                        <option disabled selected>Select a term</option>
                        <option value="First Term" {{ old('term')=='First Term' ? 'selected' : '' }}>First Term</option>
                        <option value="Second Term" {{ old('term')=='Second Term' ? 'selected' : '' }}>Second Term</option>
                        <option value="Third Term" {{ old('term')=='Third Term' ? 'selected' : '' }}>Third Term</option>
                    </select>

                  </section>

                  <!-- Step 3: Exam Options -->
                  <h3>Options</h3>
                  <section>
                    <div class="form-check mb-3">
                      <input 
                        class="form-check-input" 
                        type="checkbox" 
                        id="randomize_questions"
                        name="randomize_questions"
                        value="1"
                        {{ old('randomize_questions') ? 'checked' : '' }}
                      >
                      <label class="form-check-label" for="randomize_questions">
                        Randomize Questions
                      </label>
                    </div>

                    <div class="form-check mb-3">
                      <input 
                        class="form-check-input" 
                        type="checkbox" 
                        id="show_one_question_at_time"
                        name="show_one_question_at_time"
                        value="1"
                        {{ old('show_one_question_at_time') ? 'checked' : '' }}
                      >
                      <label class="form-check-label" for="show_one_question_at_time">
                        Show One Question At A Time
                      </label>
                    </div>

                    <div class="form-check mb-3">
                      <input 
                        class="form-check-input" 
                        type="checkbox" 
                        id="show_results"
                        name="show_results"
                        value="1"
                        {{ old('show_results', true) ? 'checked' : '' }}
                      >
                      <label class="form-check-label" for="show_results">
                        Show Results After Submission
                      </label>
                    </div>
                  </section>

                  <!-- Step 4: Instructions -->
                  <h3>Instructions</h3>
                  <section>
                    <label for="instructions">Exam Instructions</label>
                    <textarea
                      id="instructions"
                      name="instructions"
                      class="form-control"
                      rows="6"
                      placeholder="Add instructions for students...">{{ old('instructions') }}</textarea>
                    <small class="text-muted">These instructions will be shown to students before they start the exam</small>
                  </section>

                  <!-- Step 5: Finish -->
                  <h3>Finish</h3>
                  <section>
                    <input
                      id="acceptTerms"
                      name="acceptTerms"
                      type="checkbox"
                      class="required"
                    />
                    <label for="acceptTerms">
                      I confirm that all the information is correct and want to create this exam.
                    </label>
                    <button class="btn btn-primary mt-3" type="submit">Create Exam</button>
                  </section>
                </div>
              </form>
            </div>
          </div>
          <!-- ============================================================== -->
          <!-- End Page Content -->
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
<script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
<!-- Bootstrap tether Core JavaScript -->
<script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
<!-- Slimscrollbar JavaScript -->
<script src="{{ asset('assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js') }}"></script>
<script src="{{ asset('assets/extra-libs/sparkline/sparkline.js') }}"></script>
<!-- Wave Effects -->
<script src="{{ asset('dist/js/waves.js') }}"></script>
<!-- Menu Sidebar -->
<script src="{{ asset('dist/js/sidebarmenu.js') }}"></script>
<!-- Custom JavaScript -->
<script src="{{ asset('dist/js/custom.min.js') }}"></script>
<!-- This Page JS -->
<script src="{{ asset('assets/libs/jquery-steps/build/jquery.steps.min.js') }}"></script>
<script src="{{ asset('assets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>

<script>
  // Basic Example with form
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