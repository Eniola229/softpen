@include('components.header') 
<link href="{{ asset('assets/libs/jquery-steps/jquery.steps.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/libs/jquery-steps/steps.css') }}" rel="stylesheet" />
<link href="{{ asset('dist/css/style.min.css') }}" rel="stylesheet" />
<script src="https://cdn.tiny.cloud/1/9lcsi17by61qxgfug4h9ns3wl0mkdwithf1yovboozc6qd27/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
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
              <h4 class="page-title">Edit Question</h4>
              <div class="ms-auto text-end">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Softpen</a></li>
                    <li class="breadcrumb-item"><a href="#">{{ $class->name }}</a></li>
                    <li class="breadcrumb-item"><a href="#">{{ $exam->title }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                      Edit Question
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
              <h1 class="card-title">Edit Question Q{{ $question->order }}</h1>
              <h6 class="card-subtitle">Modify the question details</h6>
              <form id="question-form" action="{{ route("staff.questions.update", [$class->id, $exam->id, $question->id]) }}" method="post" class="mt-5" enctype="multipart/form-data"> 
                @csrf
                @method('PUT')
                <div>
                  <!-- Step 1: Question Text -->
                  <h3>Question</h3>
                  <section>
                    <label for="question_text">Question Text *</label>
                    <textarea
                        id="question_text"
                        name="question_text"
                        class="rich-editor required form-control"
                        rows="4"
                        placeholder="Enter the question text..."
                    >{{ old('question_text', $question->question_text ?? '') }}</textarea>


                    <label for="question_image" class="mt-3">Question Image (optional)</label>
                    @if($question->question_image)
                      <div class="mb-3">
                        <img src="{{ $question->question_image }}" alt="Question Image" style="max-width: 200px; height: auto;">
                        <p class="text-muted small">Current Image</p>
                      </div>
                    @endif
                    <input
                      id="question_image"
                      name="question_image"
                      type="file"
                      class="form-control"
                      accept="image/*"
                    />
                    <small class="text-muted">For diagrams or case images. Max 2MB. Leave empty to keep current image.</small>

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
                      disabled
                      style="background-color: #e9ecef; cursor: not-allowed;"
                    >
                      <option value="multiple_choice" {{ $question->question_type == 'multiple_choice' ? 'selected' : '' }}>Multiple Choice</option>
                      <option value="true_false" {{ $question->question_type == 'true_false' ? 'selected' : '' }}>True/False</option>
                    </select>
                    <!-- Hidden input to send the value since disabled fields aren't submitted -->
                    <input type="hidden" name="question_type" value="{{ $question->question_type }}">
                    <small class="text-muted mt-2 d-block"><i class="fas fa-lock"></i> Question type is locked and cannot be changed.</small>

                    <label for="mark" class="mt-3">Mark/Points *</label>
                    <input
                      id="mark"
                      name="mark"
                      type="number"
                      class="required form-control"
                      placeholder="e.g., 1"
                      value="{{ old('mark', $question->mark) }}"
                      min="1"
                    />

                    <p class="mt-3">(*) Mandatory</p>
                  </section>

                  <!-- Step 3: Options Info (Read-only) -->
                  @if($question->question_type === 'multiple_choice' || $question->question_type === 'true_false')
                    <h3>Options</h3>
                    <section>
                      <div class="alert alert-info">
                        <strong><i class="fas fa-info-circle"></i> Note:</strong> To edit options, you need to delete this question and create a new one with updated options.
                      </div>
                      <div class="card">
                        <div class="card-body">
                          <h6 class="card-title">Current Options:</h6>
                          <table class="table table-sm table-striped">
                            <thead>
                              <tr>
                                <th>Order</th>
                                <th>Option Text</th>
                                <th>Correct</th>
                              </tr>
                            </thead>
                            <tbody>
                              @foreach($question->options as $option)
                                <tr>
                                  <td><strong>{{ chr(64 + $option->order) }}</strong></td>
                                  <td>{{ Str::limit($option->option_text, 50) }}</td>
                                  <td>
                                    @if($option->is_correct)
                                      <span class="badge badge-success"><i class="fas fa-check"></i> Correct</span>
                                    @else
                                      <span class="badge badge-secondary">-</span>
                                    @endif
                                  </td>
                                </tr>
                              @endforeach
                            </tbody>
                          </table>
                        </div>
                      </div>
                      <input
                      id="acceptTerms"
                      name="acceptTerms"
                      type="checkbox"
                      class="required"
                      checked
                    />


                    </section>
                  @endif
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
tinymce.init({
    selector: 'textarea.rich-editor',
    height: 400,
    menubar: false,
   plugins: 'preview importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap quickbars emoticons',
     toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | table | fullscreen  preview save print | insertfile image media template link anchor codesample | ltr rtl',
    toolbar_mode: 'sliding',
    contextmenu: 'link image table',
    branding: false
});
</script>
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
      form.submit();
    },
  });
</script>
  </body>
</html>