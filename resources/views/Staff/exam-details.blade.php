@include('components.header') 
    <link
      rel="stylesheet"
      type="text/css"
      href="../assets/extra-libs/multicheck/multicheck.css"
    />
    <link
      href="../assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css"
      rel="stylesheet"
    />
    <link href="../dist/css/style.min.css" rel="stylesheet" />
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
              <h4 class="page-title">{{ $exam->title }}</h4>
              <div class="ms-auto text-end">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Softpen</a></li>
                    <li class="breadcrumb-item"><a href="#">{{ $class->name }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                      {{ $exam->title }}
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

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
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

        <!-- Exam Details Card -->
        <div class="card mb-4">
          <div class="card-body">
            <div class="row">
              <div class="col-md-8">
                <h5 class="card-title">{{ $exam->title }}</h5>
                <p class="text-muted">{{ $subject->name }} | Class: {{ $class->name }} | Department: {{ $department->name ?? 'N/A'}}</p>
                
                @if($exam->description)
                  <p class="mt-3"><strong>Description:</strong> {{ $exam->description }}</p>
                @endif

                <div class="row mt-4">
                  <div class="col-md-6">
                    <p><strong>Duration:</strong> {{ $exam->duration }} minutes</p>
                    <p><strong>Total Questions Required:</strong> {{ $exam->total_questions }}</p>
                    <p><strong>Passing Score:</strong> {{ $exam->passing_score }}%</p>
                    <p><strong>Session:</strong> {{ $exam->session ?? "N/A" }}</p>
                    <p><strong>Term:</strong> {{ $exam->term ?? "N/A"}}</p>
                  </div>
                  <div class="col-md-6">
                    <p><strong>Status:</strong> 
                      @if($exam->is_published == 1 || $exam->is_published === true)
                       Published
                      @else
                       Draft
                      @endif
                    </p>
                    <p><strong>Exam Date and Time:</strong> {{ $exam->exam_date_time ?? 'N/A' }}</p>
                    <p><strong>Created:</strong> {{ $exam->created_at->format("M d, Y") }}</p>
                    <p><strong>Last Updated:</strong> {{ $exam->updated_at->format("M d, Y") }}</p>
                  </div>
                </div>

                @if($exam->instructions)
                  <div class="alert alert-info mt-3">
                    <strong>Instructions:</strong><br>
                    {{ $exam->instructions }}
                  </div>
                @endif
              </div>
              <div class="col-md-4">
                <!-- Progress Card -->
                <div class="card border-primary mb-3">
                  <div class="card-body">
                    <h6 class="card-title">Questions Progress</h6>
                    <div class="progress mb-3" style="height: 25px;">
                      @php
                        $percentage = ($questions->count() / $exam->total_questions) * 100;
                      @endphp
                      <div class="progress-bar" role="progressbar" style="width: {{ $percentage }}%" aria-valuenow="{{ $questions->count() }}" aria-valuemin="0" aria-valuemax="{{ $exam->total_questions }}">
                        {{ $questions->count() }}/{{ $exam->total_questions }}
                      </div>
                    </div>
                    @if($questions->count() >= $exam->total_questions)
                      <span class="badge badge-success"><i class="fas fa-check-circle"></i> Complete</span>
                    @else
                      <span class="badge badge-warning"><i class="fas fa-exclamation-circle"></i> {{ $exam->total_questions - $questions->count() }} remaining</span>
                    @endif
                  </div>
                </div>

                <!-- Settings Card -->
                <div class="card border-secondary">
                  <div class="card-body">
                    <h6 class="card-title">Settings</h6>
                    <ul class="list-unstyled">
                      <li>
                        <i class="fas fa-random"></i>
                        @if($exam->randomize_questions)
                          <span class="badge badge-success">Questions Randomized</span>
                        @else
                          <span class="badge badge-secondary">Sequential</span>
                        @endif
                      </li>
                      <li class="mt-2">
                        <i class="fas fa-list"></i>
                        @if($exam->show_one_question_at_time)
                          <span class="badge badge-success">One at a Time</span>
                        @else
                          <span class="badge badge-secondary">All Visible</span>
                        @endif
                      </li>
                      <li class="mt-2">
                        <i class="fas fa-chart-bar"></i>
                        @if($exam->show_results)
                          <span class="badge badge-success">Show Results</span>
                        @else
                          <span class="badge badge-secondary">Hide Results</span>
                        @endif
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Questions Table -->
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <h5 class="card-title">
                Questions 
                @if($questions->count() >= $exam->total_questions)
                  <span class="badge badge-success">{{ $questions->count() }}/{{ $exam->total_questions }} Complete</span>
                @else
                  <span class="badge badge-warning">{{ $questions->count() }}/{{ $exam->total_questions }}</span>
                @endif
              </h5>
              @if($questions->count() < $exam->total_questions)
                <a href="{{ route("staff.questions.create", [$class->id, $exam->id]) }}">
                  <button class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add Question
                  </button>
                </a>
              @else
                <div>
                  <span class="badge badge-success mr-2">All questions added!</span>
                  @if(!$exam->is_published)
                    <button class="btn btn-success btn-sm" onclick="publishExam('{{ route("staff.exams.publish", [$class->id, $exam->id]) }}')">
                      <i class="fas fa-check"></i> Publish Exam
                    </button>
                  @endif
                </div>
              @endif
            </div>

            @if($questions->count() > 0)
              <div class="table-responsive">
                <table id="questions_table" class="table table-striped table-bordered">
                  <thead>
                    <tr>
                      <th>Order</th>
                      <th>Question</th>
                      <th>Type</th>
                      <th>Mark</th>
                      <th>Options</th>
                      <th>Created At</th>
                      <th>Action</th>
                    </tr> 
                  </thead>
                  <tbody>
                    @foreach($questions as $question)
                      <tr>
                        <td>Q{{ $question->order }}</td>
                        <td>
                          {{ Str::limit($question->question_text, 50) }}
                          @if($question->question_image)
                            <br><small class="text-muted"><i class="fas fa-image"></i> Has Image</small>
                          @endif
                        </td>
                        <td>
                            @if($question->question_type === 'multiple_choice')
                              Multiple Choice
                            @elseif($question->question_type === 'true_false')
                              True/False
                            @else
                              Short Answer
                            @endif
                          </td>
                        <td><strong>{{ $question->mark }} pts</strong></td>
                        <td>
                          @if($question->question_type === 'multiple_choice' || $question->question_type === 'true_false')
                            {{ $question->options->count() }} options
                          @else
                            -
                          @endif
                        </td>
                        <td>{{ $question->created_at->format("M d, Y") }}</td>
                        <td>
                          <a href="{{ route("staff.questions.edit", [$class->id, $exam->id, $question->id]) }}">
                            <button class="btn btn-warning btn-sm" title="Edit Question">
                              <i class="fas fa-edit"></i>
                            </button>
                          </a>
                          <button class="btn btn-danger btn-sm" onclick="confirmDelete('{{ route("staff.questions.destroy", [$class->id, $exam->id, $question->id]) }}', '{{ addslashes($question->question_text) }}')" title="Delete Question">
                            <i class="fas fa-trash"></i>
                          </button>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            @else
              <div class="alert alert-warning">
                <strong><i class="fas fa-exclamation-triangle"></i> No Questions Yet</strong><br>
                You need to add <strong>{{ $exam->total_questions }} questions</strong> to this exam before you can publish it.
                <a href="{{ route("staff.questions.create", [$class->id, $exam->id]) }}" class="btn btn-primary btn-sm mt-2">
                  <i class="fas fa-plus"></i> Add First Question
                </a>
              </div>
            @endif
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="card mt-4">
          <div class="card-body">
            <div class="d-flex gap-2">
              <a href="{{ route("staff.exams.edit", [$class->id, $exam->id]) }}">
                <button class="btn btn-warning">
                  <i class="fas fa-edit"></i> Edit Exam Details
                </button>
              </a>
              @if($questions->count() >= $exam->total_questions && !$exam->is_published)
                <button class="btn btn-success" onclick="publishExam('{{ route("staff.exams.publish", [$class->id, $exam->id]) }}')">
                  <i class="fas fa-check"></i> Publish Exam
                </button>
              @endif
              <a href="{{ route("staff-cbt", $class->id) }}">
                <button class="btn btn-secondary">
                  <i class="fas fa-arrow-left"></i> Back to Exams
                </button>
              </a>
            </div>
          </div>
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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
      /****************************************
       *       Questions Table                *
       ****************************************/
      $("#questions_table").DataTable();
    </script>

    <script type="text/javascript">
      function confirmDelete(url, questionText) {
          Swal.fire({
              title: "Are you sure?",
              text: "You are about to delete this question. This action cannot be undone.",
              icon: "warning",
              showCancelButton: true,
              confirmButtonColor: "#3085d6",
              cancelButtonColor: "#d33",
              confirmButtonText: "Yes, delete it!"
          }).then((result) => {
              if (result.isConfirmed) {
                  let form = document.createElement("form");
                  form.method = "POST";
                  form.action = url;
                  
                  let csrfToken = document.createElement("input");
                  csrfToken.type = "hidden";
                  csrfToken.name = "_token";
                  csrfToken.value = "{{ csrf_token() }}";
                  
                  let methodInput = document.createElement("input");
                  methodInput.type = "hidden";
                  methodInput.name = "_method";
                  methodInput.value = "DELETE";
                  
                  form.appendChild(csrfToken);
                  form.appendChild(methodInput);
                  document.body.appendChild(form);
                  form.submit();
              }
          });
      }

      function publishExam(url) {
          Swal.fire({
              title: "Publish Exam?",
              text: "Once published, students will be able to take this exam.",
              icon: "info",
              showCancelButton: true,
              confirmButtonColor: "#3085d6",
              cancelButtonColor: "#d33",
              confirmButtonText: "Yes, publish it!"
          }).then((result) => {
              if (result.isConfirmed) {
                  // Create a form to submit as POST request
                  let form = document.createElement("form");
                  form.method = "POST";
                  form.action = url;
                  
                  let csrfToken = document.createElement("input");
                  csrfToken.type = "hidden";
                  csrfToken.name = "_token";
                  csrfToken.value = "{{ csrf_token() }}";
                  
                  form.appendChild(csrfToken);
                  document.body.appendChild(form);
                  form.submit();
              }
          });
      }
    </script>
  </body>
</html>