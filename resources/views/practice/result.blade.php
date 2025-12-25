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
            <h4 class="page-title">Exam Results</h4>
            <div class="ms-auto text-end">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{ route('practice.index') }}">Practice</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('practice.exams', $class->id) }}">{{ $class->name }}</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Results</li>
                </ol>
              </nav>
            </div>
          </div>
        </div>
      </div>

      <div class="container-fluid">
        <div class="row justify-content-center">
          <div class="col-lg-10">
            <!-- Result Summary Card -->
            <div class="card">
              <div class="card-body text-center">
                <h2 class="card-title">{{ $exam->title }}</h2>
                <p class="text-muted">{{ $exam->subject }} | {{ $class->name }}</p>

                <div class="row mt-4">
                  <div class="col-md-3 col-6">
                    <div class="p-3">
                      <h3 class="text-primary">{{ $attempt->score }}</h3>
                      <p class="text-muted mb-0">Score</p>
                    </div>
                  </div>
                  <div class="col-md-3 col-6">
                    <div class="p-3">
                      <h3 class="text-info">{{ number_format($attempt->percentage, 2) }}%</h3>
                      <p class="text-muted mb-0">Percentage</p>
                    </div>
                  </div>
                  <div class="col-md-3 col-6">
                    <div class="p-3">
                      <h3 class="text-warning">{{ $attempt->time_spent }}</h3>
                      <p class="text-muted mb-0">Time Spent</p>
                    </div>
                  </div>
                  <div class="col-md-3 col-6">
                    <div class="p-3">
                      @if($attempt->passed)
                        <h3 class="text-success"><i class="mdi mdi-check-circle"></i></h3>
                        <p class="text-success mb-0">PASSED</p>
                      @else
                        <h3 class="text-danger"><i class="mdi mdi-close-circle"></i></h3>
                        <p class="text-danger mb-0">FAILED</p>
                      @endif
                    </div>
                  </div>
                </div>

                @if($attempt->passed)
                  <div class="alert alert-success mt-3">
                    <strong>Congratulations!</strong> You have passed this exam with {{ number_format($attempt->percentage, 2) }}%. 
                    The passing score was {{ $exam->passing_score }}%.
                  </div>
                @else
                  <div class="alert alert-danger mt-3">
                    <strong>Keep Practicing!</strong> You scored {{ number_format($attempt->percentage, 2) }}%, but the passing score is {{ $exam->passing_score }}%. 
                    Review the explanations below and try again.
                  </div>
                @endif

                <div class="mt-3">
                  <p><strong>Completed At:</strong> {{ $attempt->completed_at->format('M d, Y h:i A') }}</p>
                </div>
              </div>
            </div>

            <!-- Questions Review -->
            @if($exam->show_explanations)
              <div class="card mt-4">
                <div class="card-body">
                  <h4 class="card-title">Detailed Review</h4>
                  
                  @foreach($attempt->answers as $index => $answer)
                    <div class="border-bottom pb-4 mb-4">
                      <div class="d-flex justify-content-between align-items-start">
                        <h5>Question {{ $index + 1 }}</h5>
                        @if($answer->is_correct)
                          <span class="badge bg-success"><i class="mdi mdi-check"></i> Correct</span>
                        @else
                          <span class="badge bg-danger"><i class="mdi mdi-close"></i> Incorrect</span>
                        @endif
                      </div>

                      <div class="mt-3">
                        <p><strong>{{ $answer->pQuestion->question_text }}</strong></p>
                        
                        @if($answer->pQuestion->question_image)
                          <img src="{{ asset('storage/' . $answer->pQuestion->question_image) }}" 
                               alt="Question Image" 
                               class="img-fluid mb-3" 
                               style="max-height: 300px;">
                        @endif

                        <div class="mt-3">
                          @foreach($answer->pQuestion->options as $option)
                            <div class="form-check mb-2 p-3 rounded
                              @if($option->is_correct) bg-light-success border border-success
                              @elseif($answer->p_question_option_id === $option->id && !$option->is_correct) bg-light-danger border border-danger
                              @else border
                              @endif">
                              <label class="form-check-label w-100">
                                <span class="me-2">
                                  @if($option->is_correct)
                                    <i class="mdi mdi-check-circle text-success"></i>
                                  @elseif($answer->p_question_option_id === $option->id && !$option->is_correct)
                                    <i class="mdi mdi-close-circle text-danger"></i>
                                  @else
                                    <i class="mdi mdi-circle-outline"></i>
                                  @endif
                                </span>
                                {{ $option->option_text }}
                                @if($answer->p_question_option_id === $option->id)
                                  <span class="badge bg-primary ms-2">Your Answer</span>
                                @endif
                                @if($option->is_correct)
                                  <span class="badge bg-success ms-2">Correct Answer</span>
                                @endif
                              </label>
                            </div>
                          @endforeach
                        </div>

                        @if($answer->pQuestion->explanation)
                          <div class="alert alert-info mt-3">
                            <strong><i class="mdi mdi-information"></i> Explanation:</strong><br>
                            {{ $answer->pQuestion->explanation }}
                          </div>
                        @endif

                        <div class="text-muted mt-2">
                          <small>Marks: {{ $answer->marks_obtained }} / {{ $answer->pQuestion->mark }}</small>
                        </div>
                      </div>
                    </div>
                  @endforeach
                </div>
              </div>
            @endif

            <!-- Action Buttons -->
            <div class="card mt-4">
              <div class="card-body">
                <div class="d-flex justify-content-between">
                  <a href="{{ route('practice.exams', $class->id) }}" class="btn btn-secondary">
                    <i class="mdi mdi-arrow-left"></i> Back to Exams
                  </a>
                  <div>
                    <a href="{{ route('practice.attempts') }}" class="btn btn-info me-2">
                      <i class="mdi mdi-history"></i> My Attempts
                    </a>
                    <a href="{{ route('practice.instructions', [$class->id, $exam->id]) }}" class="btn btn-primary">
                      <i class="mdi mdi-refresh"></i> Retake Exam
                    </a>
                  </div>
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