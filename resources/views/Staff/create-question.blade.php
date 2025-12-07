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
      @include('components.side-nav') 
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
              <h4 class="page-title">Add Question to {{ $exam->title }}</h4>
              <div class="ms-auto text-end">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Softpen</a></li>
                    <li class="breadcrumb-item"><a href="#">{{ $class->name }}</a></li>
                    <li class="breadcrumb-item"><a href="#">{{ $exam->title }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                      Add Question
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
              <h1 class="card-title">Add a new question</h1>
              <h6 class="card-subtitle">Fill in the question details step by step</h6>
              <form id="question-form" action="{{ route("staff.questions.store", [$class->id, $exam->id]) }}" method="post" class="mt-5" enctype="multipart/form-data"> 
                @csrf
                <div>
                  <!-- Step 1: Question Text -->
                  <h3>Question</h3>
                  <section>
                    <label for="question_text">Question Text *</label>
                    <textarea
                      id="question_text"
                      name="question_text"
                      class="required form-control"
                      rows="4"
                      placeholder="Enter the question text..."
                      value="{{ old('question_text') }}"></textarea>

                    <label for="question_image" class="mt-3">Question Image (optional)</label>
                    <input
                      id="question_image"
                      name="question_image"
                      type="file"
                      class="form-control"
                      accept="image/*"
                    />
                    <small class="text-muted">For diagrams or case images. Max 2MB</small>

                    <p class="mt-3">(*) Mandatory</p>
                  </section>

                  <!-- Step 2: Question Type & Mark -->
                  <h3>Question Type</h3>
                  <section>
                    <label for="question_type">Question Type *</label>
                    <select
                      id="question_type"
                      name="question_type"
                      class="required form-control"
                      onchange="updateQuestionType()"
                    >
                      <option value="">-- Select Question Type --</option>
                      <option value="multiple_choice" {{ old('question_type') == 'multiple_choice' ? 'selected' : '' }}>Multiple Choice</option>
                      <option value="true_false" {{ old('question_type') == 'true_false' ? 'selected' : '' }}>True/False</option>
                    </select>

                    <label for="mark" class="mt-3">Mark/Points *</label>
                    <input
                      id="mark"
                      name="mark"
                      type="number"
                      class="required form-control"
                      placeholder="e.g., 1"
                      value="{{ old('mark', 1) }}"
                      min="1"
                    />

                    <p class="mt-3">(*) Mandatory</p>
                  </section>

                  <!-- Step 3: Options (for Multiple Choice and True/False) -->
                  <h3 id="options-header" style="display: none;">Options</h3>
                  <section id="options-section" style="display: none;">
                    <div id="options-container">
                      <div class="option-group mb-3 option-0">
                        <div class="row">
                          <div class="col-md-8">
                            <label>Option 1 Text *</label>
                            <input
                              type="text"
                              name="options[0][text]"
                              class="form-control option-text"
                              placeholder="Enter option text"
                            />
                          </div>
                          <div class="col-md-4">
                            <label>Option Image</label>
                            <input
                              type="file"
                              name="options[0][image]"
                              class="form-control"
                              accept="image/*"
                            />
                          </div>
                        </div>
                        <div class="form-check mt-2">
                          <input
                            class="form-check-input correct-option"
                            type="radio"
                            name="correct_option"
                            value="0"
                            id="correct_0"
                          />
                          <label class="form-check-label" for="correct_0">
                            This is the correct answer
                          </label>
                        </div>
                      </div>

                      <div class="option-group mb-3 option-1">
                        <div class="row">
                          <div class="col-md-8">
                            <label>Option 2 Text *</label>
                            <input
                              type="text"
                              name="options[1][text]"
                              class="form-control option-text"
                              placeholder="Enter option text"
                            />
                          </div>
                          <div class="col-md-4">
                            <label>Option Image</label>
                            <input
                              type="file"
                              name="options[1][image]"
                              class="form-control"
                              accept="image/*"
                            />
                          </div>
                        </div>
                        <div class="form-check mt-2">
                          <input
                            class="form-check-input correct-option"
                            type="radio"
                            name="correct_option"
                            value="1"
                            id="correct_1"
                          />
                          <label class="form-check-label" for="correct_1">
                            This is the correct answer
                          </label>
                        </div>
                      </div>

                      <div class="option-group mb-3 option-2">
                        <div class="row">
                          <div class="col-md-8">
                            <label>Option 3 Text *</label>
                            <input
                              type="text"
                              name="options[2][text]"
                              class="form-control option-text"
                              placeholder="Enter option text"
                            />
                          </div>
                          <div class="col-md-4">
                            <label>Option Image</label>
                            <input
                              type="file"
                              name="options[2][image]"
                              class="form-control"
                              accept="image/*"
                            />
                          </div>
                        </div>
                        <div class="form-check mt-2">
                          <input
                            class="form-check-input correct-option"
                            type="radio"
                            name="correct_option"
                            value="2"
                            id="correct_2"
                          />
                          <label class="form-check-label" for="correct_2">
                            This is the correct answer
                          </label>
                        </div>
                      </div>

                      <div class="option-group mb-3 option-3">
                        <div class="row">
                          <div class="col-md-8">
                            <label>Option 4 Text *</label>
                            <input
                              type="text"
                              name="options[3][text]"
                              class="form-control option-text"
                              placeholder="Enter option text"
                            />
                          </div>
                          <div class="col-md-4">
                            <label>Option Image</label>
                            <input
                              type="file"
                              name="options[3][image]"
                              class="form-control"
                              accept="image/*"
                            />
                          </div>
                        </div>
                        <div class="form-check mt-2">
                          <input
                            class="form-check-input correct-option"
                            type="radio"
                            name="correct_option"
                            value="3"
                            id="correct_3"
                          />
                          <label class="form-check-label" for="correct_3">
                            This is the correct answer
                          </label>
                        </div>
                      </div>
                    </div>
                    <button type="button" id="add-option-btn" class="btn btn-secondary btn-sm" onclick="addOption()">
                      <i class="fas fa-plus"></i> Add More Options
                    </button>

                      <input
                      id="acceptTerms"
                      name="acceptTerms"
                      type="checkbox"
                      class="required"
                      checked
                    />

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
  var form = $("#question-form");
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
      form.validate().settings.ignore = ":disabled,:hidden";
      return form.valid();
    },
    onFinished: function (event, currentIndex) {
      // Make sure disabled fields are not submitted
      form.find(':input:disabled').prop('disabled', false).addClass('temp-disabled');
      form.find('.temp-disabled').remove();
      form.submit();
    },
    onInit: function (event, currentIndex) {
      // Initialize question type on wizard load
      updateQuestionType();
    }
  });

  // Show/hide options section based on question type
  function updateQuestionType() {
    console.log('updateQuestionType called');
    var type = $('#question_type').val();
    console.log('Question type:', type);
    
    // Use jQuery to find elements
    var $option0 = $('.option-0');
    var $option1 = $('.option-1');
    var $option2 = $('.option-2');
    var $option3 = $('.option-3');
    var $addOptionBtn = $('#add-option-btn');
    var $allOptionInputs = $('.option-text');

    if (type === 'multiple_choice') {
      // Show all 4 options for multiple choice
      $option0.show();
      $option1.show();
      $option2.show();
      $option3.show();
      $addOptionBtn.show();
      
      // Enable inputs for options 2 and 3
      $option2.find('input, select, textarea').prop('disabled', false);
      $option3.find('input, select, textarea').prop('disabled', false);
      
      // Reset all inputs
      $allOptionInputs.each(function() {
        $(this).prop('readOnly', false);
        if ($(this).val() === 'True' || $(this).val() === 'False') {
          $(this).val('');
        }
      });
      
    } else if (type === 'true_false') {
      console.log('Setting True/False options');
      
      // Show only first 2 options and hide others
      $option0.show();
      $option1.show();
      $option2.hide();
      $option3.hide();
      $addOptionBtn.hide();
      
      // Disable all inputs in hidden options so they won't be submitted
      $option2.find('input, select, textarea').prop('disabled', true);
      $option3.find('input, select, textarea').prop('disabled', true);
      
      console.log('Options 2 and 3 should be hidden and disabled now');
      
      // Set True/False values
      $allOptionInputs.eq(0).val('True').prop('readOnly', true);
      $allOptionInputs.eq(1).val('False').prop('readOnly', true);
      
    } else {
      // Reset all inputs when no type selected
      $allOptionInputs.each(function() {
        $(this).prop('readOnly', false).val('');
      });
      
      // Enable all inputs
      $('.option-group').find('input, select, textarea').prop('disabled', false);
    }
  }

  // Add more options dynamically (only for multiple choice)
  let optionCount = 4;
  function addOption() {
    var type = document.getElementById('question_type').value;
    if (type !== 'multiple_choice') {
      return; // Don't add options for True/False
    }

    const container = document.getElementById('options-container');
    const newOption = document.createElement('div');
    newOption.className = 'option-group mb-3 option-' + optionCount;
    newOption.innerHTML = `
      <div class="row">
        <div class="col-md-8">
          <label>Option ${optionCount + 1} Text</label>
          <input
            type="text"
            name="options[${optionCount}][text]"
            class="form-control option-text"
            placeholder="Enter option text"
          />
        </div>
        <div class="col-md-4">
          <label>Option Image</label>
          <input
            type="file"
            name="options[${optionCount}][image]"
            class="form-control"
            accept="image/*"
          />
        </div>
      </div>
      <div class="form-check mt-2">
        <input
          class="form-check-input correct-option"
          type="radio"
          name="correct_option"
          value="${optionCount}"
          id="correct_${optionCount}"
        />
        <label class="form-check-label" for="correct_${optionCount}">
          This is the correct answer
        </label>
      </div>
      <button type="button" class="btn btn-danger btn-sm mt-2" onclick="removeOption(this)">
        <i class="fas fa-trash"></i> Remove
      </button>
    `;
    container.appendChild(newOption);
    optionCount++;
  }

  // Remove option
  function removeOption(button) {
    button.parentElement.remove();
  }

  // Initialize on page load
  document.addEventListener('DOMContentLoaded', function() {
    updateQuestionType();
  });
</script>
  </body>
</html>