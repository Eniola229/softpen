@include('components.p-header') 
<style>
  .question-card {
    margin-bottom: 30px;
  }
  .option-card {
    cursor: pointer;
    transition: all 0.3s;
    border: 2px solid #e0e0e0;
  }
  .option-card:hover {
    border-color: #1e88e5;
    background-color: #f5f5f5;
  }
  .option-card.selected {
    border-color: #1e88e5;
    background-color: #e3f2fd;
  }
  .timer-box {
    position: fixed;
    top: 80px;
    right: 20px;
    z-index: 1000;
    background: white;
    padding: 15px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
  }
  .timer-text {
    font-size: 24px;
    font-weight: bold;
    color: #1e88e5;
  }
  .timer-warning {
    color: #ff9800;
  }
  .timer-danger {
    color: #f44336;
  }
</style>
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

    <!-- Timer Box -->
    <div class="timer-box">
      <div class="text-center">
        <i class="mdi mdi-clock-outline" style="font-size: 30px;"></i>
        <div class="timer-text" id="timer">{{ $exam->duration }}:00</div>
        <small>Time Remaining</small>
      </div>
    </div>

    <div class="page-wrapper">
      <div class="page-breadcrumb">
        <div class="row">
          <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">{{ $exam->title }}</h4>
            <div class="ms-auto text-end">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{ route('practice.index') }}">Practice</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('practice.exams', $class->id) }}">{{ $class->name }}</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Taking Exam</li>
                </ol>
              </nav>
            </div>
          </div>
        </div>
      </div>

      <div class="container-fluid">
        <form action="{{ route('practice.submit', [$class->id, $exam->id, $attempt->id]) }}" method="POST" id="examForm">
          @csrf

          @foreach($questions as $index => $question)
            <div class="card question-card">
              <div class="card-body">
                <h5 class="card-title">Question {{ $index + 1 }} of {{ $questions->count() }} ({{ $question->mark }} mark{{ $question->mark > 1 ? 's' : '' }})</h5>
                
                <p class="mb-3">{!! $question->question_text !!}</p>

                @if($question->question_image)
                  <div class="mb-3">
                    <img src="{{ $question->question_image }}" alt="Question Image" class="img-fluid" style="max-width: 500px;">
                  </div>
                @endif

                @if($question->hint)
                  <div class="alert alert-secondary">
                    <strong><i class="mdi mdi-lightbulb-outline"></i> Hint:</strong> {{ $question->hint }}
                  </div>
                @endif

                @if($question->question_type === 'multiple_choice' || $question->question_type === 'true_false')
                  <div class="row">
                    @foreach($question->options as $option)
                      <div class="col-md-6 mb-2">
                        <div class="option-card p-3" onclick="selectOption(this, '{{ $question->id }}', '{{ $option->id }}')">
                          <div class="form-check">
                            <input class="form-check-input" type="radio" name="question_{{ $question->id }}" id="option_{{ $option->id }}" value="{{ $option->id }}">
                            <label class="form-check-label" for="option_{{ $option->id }}">
                              {{ $option->option_text }}
                              @if($option->option_image)
                                <br>
                                <img src="{{ $option->option_image }}" alt="Option Image" class="img-thumbnail mt-2" style="max-width: 200px;">
                              @endif
                            </label>
                          </div>
                        </div>
                      </div>
                    @endforeach
                  </div>
                @elseif($question->question_type === 'short_answer')
                  <div class="form-group">
                    <textarea name="question_{{ $question->id }}_text" class="form-control" rows="4" placeholder="Type your answer here..."></textarea>
                  </div>
                @endif
              </div>
            </div>
          @endforeach

          <div class="card">
            <div class="card-body">
              <div class="d-flex justify-content-between">
                <a href="{{ route('practice.exams', $class->id) }}" class="btn btn-secondary" onclick="return confirm('Are you sure you want to leave? Your progress will be lost.');">
                  <i class="mdi mdi-close"></i> Cancel Exam
                </a>
                <button type="submit" class="btn btn-success btn-lg" onclick="return confirm('Are you sure you want to submit your answers?');">
                  <i class="mdi mdi-check"></i> Submit Exam
                </button>
              </div>
            </div>
          </div>
        </form>
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

  <script>
    function selectOption(card, questionId, optionId) {
      // Remove selected class from all options of this question
      const allOptions = document.querySelectorAll(`input[name="question_${questionId}"]`).forEach(input => {
        input.closest('.option-card').classList.remove('selected');
      });
      
      // Add selected class to clicked option
      card.classList.add('selected');
      
      // Check the radio button
      document.getElementById(`option_${optionId}`).checked = true;
    }

    // Timer functionality
    let duration = {{ $exam->duration }} * 60; // Convert to seconds
    const timerElement = document.getElementById('timer');

    function updateTimer() {
      const minutes = Math.floor(duration / 60);
      const seconds = duration % 60;
      
      timerElement.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
      
      // Change color based on time remaining
      if (duration <= 60) {
        timerElement.classList.add('timer-danger');
      } else if (duration <= 300) {
        timerElement.classList.add('timer-warning');
      }
      
      if (duration <= 0) {
        alert('Time is up! Submitting your exam...');
        document.getElementById('examForm').submit();
      }
      
      duration--;
    }

    // Update timer every second
    setInterval(updateTimer, 1000);
    updateTimer();

    // Prevent accidental page refresh
    window.addEventListener('beforeunload', function (e) {
      e.preventDefault();
      e.returnValue = '';
    });
  </script>
</body>
</html>